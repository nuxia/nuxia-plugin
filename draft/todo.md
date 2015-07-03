### Evolutions ###

#### Disable password

Pour améliorer le disable password il faudra ajouter deux surcharges:
- UserProvider de Doctrine ( on peut utiliser le repository)
- La classe LoginFormFactory (méthode CheckAuthProvider) 
Puis ensuite il faudra trouver un moyen d'appeler la méthode setDisablePassword en callback

#### Mailer ####

- Ajout de la possibilité de ne pas traduire un email
- Passage sur le mailer pour vérifier qu'il est facilement compatible avec un object manager pour logguer correctement les mails d'un projet
- Il faut trouver un système astucieux pour pouvoir envoyer plusieurs messages le mailer n'est pour le moment pas adapter à cette utilisation

#### Captcha ####

- Rendre le captcha configurable (width et height) + ajout de defaut dans config + doc
- Captcha : refresh en javascript + doc, vider le champ quand erreur dans le form
- Traduction du message d'erreur (validator?)

#### Paginator ####

- Créer un listener de dans le paginator (QuerySubscriber) afin que la méthode isSorted fonctionne avec le tri par défaut de la query

#### Dynamic media ####

- Listen touche "entrée" dans dynamic media quand on effectue une recherche
- Remplacer le click() par un live() dans le javascript

#### MediaCollection ####

- Mettre en place un système de validation pour vérifier que l'option `nb` n'est pas dépassée.

#### Router ####

- Ajouter une méthode pour slugifier les objets dans le custom router du plugin (exemple: un objet datetime sera directement transformer en string pour l'url)
- Revoir avec Symfony 2.2

#### ArrayType ####

- Mettre le set des champs au niveau du setData et non du buildForm pour pouvoir avoir accès à l'objet
- Mettre en place un système pour customizer les options par défauts

#### StatusValidator ####

Rendre StatusValidator possible en PROPERTY_CONSTRAINT
L'option "status" doit pouvoir être un tableau

### EmbedMedia ###

- Ne pas limiter les embed_media a des images
- Le fait de devoir définir une route de téléchargement (c'est trop instrusif) quand le fichier n'est pas une image je pense qu'il lien avec une balise vers le chemin absolu serait mieux?
- Le rendu du formulaire devra afficher une checkbox pour supprimer le media quand un media sera uploadé
- La classe media doit être un paramètre l'application
- Il faudra ajouter une couche ajax j'avais fait un audit Jquery-File-Upload est super bien foutu. Faudra adapter le code pour symfony2 (https://github.com/punkave/symfony2-file-uploader-bundle)

#### Divers ####

- Faut-il merge le log listener et le default listener?
- Utiliser la classe BrowserKit/History pour le referer
- Passage avec Seb sur l'AbstractController

### Recherche ###

- Définir que l'entité étend d'AbstractModel depuis la configuration globale Doctrine ou depuis entity.orm.yml
- Définir lifecycle d'AbstractMedia depuis le global suscriber de Doctrine

### Controller ###
- Utiliser renderJson dans le controller

### Form layout ###

- Datepicker
