# Gestion des médias #

Dans la plupart de nos projets, nous avons pour habitude de stocker en base données dans une table `media` les fichiers uploadés via les formulaires. Par convention, les fichiers seront sauvegardés dans le dossier `web/uploads/%oject%/%slug%`(spécifié dans l'onglet media du fichier référence du projet) où `%object%`et `%slug%` sont des champs de l'entity `Media`.

## Validation et traduction ##

Dans le fichier validation.yml, il faut mettre les contraintes communes à l'enregistrement simple et en cascade d'un media. Les contraintes propres à l'enregistrement d'un media simple (comme les contraintes sur le libellé) doivent être placées directement dans le formulaire.

```
# Namespace/Bundlename/Resources/config/validation.yml
Namespace\Bundlename\Entity\Media:
    properties:
        file:
            - File:
                maxSize: "5M"
                maxSizeMessage: "media.file.max_size"
                uploadIniSizeErrorMessage: "media.file.max_size"
                uploadFormSizeErrorMessage: "media.file.max_size"
                uploadErrorMessage: "media.file.upload_error"
                notReadableMessage: "media.file.upload_error"
                notFoundMessage: "media.file.upload_error"
```

```
# Namespace/Bundlename/Resources/translations/validators.fr.yml
media:
    file:
        max_size: "Le fichier ne doit pas dépasser {{ limit }} {{ suffix }}"
        invalid:
            mime: "Ce type de fichier est incorrect"
            image: "Ce fichier n'est pas une image valide"
            upload_error: "Une erreur s'est produite pendant le téléchargement du fichier"

```

## Configuration doctrine ##

Dans le fichier `orm`, il faut écouter les événements `postPersist` et `postRemove` afin d'appeler respectivement les méthodes `upload` et `removeUpload` de la classe `Nuxia\Component\Media\AbstractMedia`.

```
#Namespace\Bundlename\config\doctrine\Media.orm.yml:
lifecycleCallbacks:
        prePersist: ["prePersist"]
        postPersist: ["upload"]
        postRemove: ["removeUpload"]

```

## Entité Media.php ##

Coté modèle, il suffit d'étendre de faire étendre votre classe `%Namespace%\%Bundlename%\Entity\Media` de `Nuxia\Component\Media\AbstractMedia`. Cette classe implémente l'interface `Nuxia\Component\Media\UploadableInterface`. Il faut donc définir deux méthodes :

- `getPath()` : Cette méthode doit retourner le nom du fichier avec son extension (par convention c'est la valeur du champ `data`).
- `getUploadDir()` : Cette méthode doit retourner le dossier où d'être uploadé le média (par convention : web/uploads/%oject%).


```
#Namespace\Bundlename\Entity\Media.php:
public function getPath()
{
	return $this->data;
}
    
public function getUploadDir()
{
	return 'uploads/'.$this->getObject();
}
```

# Media distant #

## Formulaire ##

```
DOCUMENTATION OUTDATED. Mettre a jour avec NuxiaMediaBundle
```

Quand une table possède une clé étrangère `media_id` on utilise la classe `Nuxia\Component\Form\Type\EmbedMedia` (alias `nuxia_embed_media`) dans le formulaire pour sauvegarder un objet `media` en cascade.

```
# FormType.php
$builder->add('media', 'nuxia_embed_media', array('data_class' => 'Namespace\Bundlename\Entity\Media'));
```

## Validation ##

* todo lien vers la documentation form_constraint.md / Valider un objet en cascade

## Configuration doctrine ##

Dans le fichier `orm`, il faut écouter les événements `postPersist` et `postRemove` afin d'appeler respectivement les méthodes `upload` et `removeUpload` de la classe `Nuxia\Component\Media\AbstractMedia`.

```
#Namespace\Bundlename\config\doctrine\ObjetDistant.orm.yml:
oneToOne:
    media: 
      targetEntity: %media_class%
      cascade: ["all"]
      joinColumn:
        name: "media_id"
        referencedColumnName: "id"
        nullable: true
        orphanRemoval: true
```
Explication des options persist et orphanRemoval : http://docs.doctrine-project.org/en/latest/reference/working-with-associations.html#transitive-persistence-cascade-operations

## Affichage ##

Au niveau du layout, c'est le bloc `nuxia_embed_media_widget` qui s'occupe du rendu. 
Si aucun media n'est uploadé le rendu est un `input de type file` sinon on affiche le media dans une balise `<img>` si cela est possible. Dans le cas contraire, un lien de téléchargement vers le fichier sera proprosé (Voir divers/Liens de téléchargement).

## Sauvegarde en base ##

Pour la sauvegarde on doit utiliser un `manager` d'entité pour avoir accès au service `doctrine.orm.entity_manager`.

```
//MyBundle/Manager/MyEntityManager.php
public function persist($object)
{
	$object->processMedia();
	//Autres instructions
    $this->em->persist($object);
    $this->em->flush();
}
```

La méthode processMedia doit remplir les champs dans entité `media` avant de la sauvegarder en base.

```
//MyBundle/Entity/MyEntity.php
public function processMedia()
{
	//Dans ce cas l'edition n'est possible
    if ($this->media !== null && $this->media->getFile()) {
    	if ($this->media->getId() === null) {
        	$this->media->setObject($this->getModelname());
            $this->media->setObjectId($this->getId());
            $this->media->setLabel($this->getLabel());
            $data = Urlizer::urlize($this->getLabel()).'_'.time().'.'.$this->getFile()->guessExtension();
            $this->media->setData($data);
        }
        return true;
    }
    return false;
}
```

Le fichier est automatiquement uploadé à la sauvegarde grâce à l'événement `postPersist`.

## Suppression en base ##

Pour la suppression on doit utiliser un `manager` d'entité pour avoir accès au service `doctrine.orm.entity_manager`.

```
//MyBundle/Manager/MyEntityManager.php
public function deleteMedia($object)
{
	$this->em->remove($object->getMedia());
    $object->setMedia(null);
    $this->em->persist($object);
    $this->em->flush();
}
```
-
Le fichier est automatiquement supprimé en cascade grâce à l'événement `postRemove`.

## Plusieurs Media ##

L'objet distant peut avoir plusieurs `medias`. Dans ce cas, il faut ajouter une propriété virtuelle (appelée `medias` par exemple) et utiliser la classe `Nuxia\Component\Form\Type\MediaCollection`. Ce type possède deux options :
- L'option `nb` qui permet de définir le nombre maximum de `medias` de l'objet.
- L'option `data_class` qui permet la classe de `Media` à utiliser.

# Divers #

## Méthode utilitaires ##

La classe `AbstractMedia` fournit également deux méthodes utiles : 

 - La méthode `isImage()` permet de savoir si votre `media` est une image ou non.
 - La méthode `detectMimeType() permet de connaitre le `mime_type`de votre `media` (indispensable pour créer une action de téléchargement).
 - La méthode `getWebPath() permet de connaitre chemin relatif au dossier web `web`. Elle est à utiliser dans les balises <img>.
 - La méthode `getAbsolutePath()` permet de connaître l'emplacement du fichier sur le serveur. 
 
## Extension du fichier uploadé ##

Pour connaître l'extension du fichier uploadé, on utilise la méthode `guessExtension()` sur l'accesseur `getFile()`.

```
$media->getFile()->guessExtension()
```

## Miniature pour les fichiers non image ##

Dans la bibliothèque de médias dynamiques, on utilise la méthode `getThumbnailPath()` pour afficher la miniature.

```
public function getThumbnailPath()
{
	if($this->isImage()) {
    	return $this->getWebPath();
    }
    return '/web/images/thumbnail.png';
}
```

Dans l'idéal, il faut stocker `/web/images/thumbnail.png` dans un fichier `yml dit de `valuelist`. 
* todo lien vers la documentation valuelist

## Lien de téléchargement ##

```
# routing.yml
media_download:
    path: /media/download/{id}
    defaults: { _controller: "MyBundle:Media:download" }
```

```
# MyBundle/MediaController.php
public function downloadAction($id)
{
	$em = $this->getDoctrine()->getManager();
    $media = $em->getRepository('MediaRepository')->findById($id);
    $path = $media->getAbsolutePath();
    if (file_exists($path)) {
        $headers = array(
        	'Content-Length' => filesize($path), 
        	'Content-Type' => $media->detectMimeType(), 				
        	'Content-Disposition' => 'attachment;filename="' . $media->getPath() . '"'
        );
        return new Response(file_get_contents($path), 200, $headers);
     }
}
```