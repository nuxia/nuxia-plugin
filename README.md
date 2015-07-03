# Plugin Nuxia Symfony2 #

## Sommaire ##

- Mise en place du `pager` de `KnpLabs`:  `/doc/knp_paginator.md`
- Mise en place des `valuelist` et utilisation du `Parser` : `/doc/value_list_and_utilities.md`
- Utiliser un objet dans une route : `/doc/router.md`
- Manipulation des champs `jsons` : `/doc/json_form_and_object.md`
- Manipulation des médias: `/doc/media.md`
- Contraintes de validations personnalisées : `/doc/validation_constraints.md`
- Gestion de la securité à l'aide d'un manager : `/doc/security.md`
- Utilisation de type personnalisé : `/doc/form_type.md`
- Customizer le rendu des formulaires : `/doc/form_layout.md`

## Généralités Symfony2 ##

### Traduction d'un formulaire  ###

La traduction des `labels`, `help`, `empty_value`, `choices` etc.. se fait à l'aide de l'option `translation_domain` qui est disponible pour chaque champ du formulaire. Pour utiliser le même domaine pour tout le formulaire on pourra ajouter la ligne suivante dans la méthode `setDefaultsOptions` du formulaire. Tout en sachant, qu'on pourra toujours personnaliser le domaine pour un champ précis.

```
$resolver->setDefaults(array('translation_domain' => 'form', /*autres options*/));
```

Par convention, on utilise `form `comme domaine

###  Notes pour les formulaires possédant un champ d'upload ###

Souvent pour traiter un formulaire depuis le contrôleur, on vérifiera que la requête possède le paramètre correspondant au nom du formulaire pour ne pas faire de `bind` inutile. Dans le cas d'un formulaire possédant un champ d'upload il faut omettre cette instruction car la taille du fichier peut dépasser la taille autorisée par php (paramètre `post_max_size` du fichier `php.ini`) et cela crée des bugs.


### Injecter un paramètre/service dans tous les templates twig ###

Il faut ajouter les lignes suivantes dans le fichier `app/config.yml` :

```
twig:
	globals:
    	parametre: "%parametre%"
      	service: "@monservice"
```

## NuxiaBundle ##

### Général ###

### Créer un manager d'entité personnalisé ###

Les managers d'entité sont très utiles pour centraliser l'exécution
Notre bundle fournit une classe abstraite (`Nuxia\Component\Doctrine\Manager\AbstractEntityManager`) et une interface (`Nuxia\Component\Doctrine\Manager\EntityManagerInterface`)


### Installation ###

Pour ajouter le bundle Nuxia à un projet, il faut modifier deux fichiers:

- Ajouter le bundle dans la méthode registerBundles du fichier AppKenrel.php.

```
public function registerBundles()
{
    ...
    // Nuxia vendors
    new Nuxia\Bundle\NuxiaBundle\NuxiaBundle(),
    ...
```

- Modifier le bloc autoload du fichier composer.json

```
"autoload": {
    "psr-0": {
        "Nuxia": "vendor/nuxia/src/",
        "": "src/"
    }
},
```

### Configuration ###

Certaines options sont configurables depuis le fichier `app/config.yml` de l'application dans lequel le bundle Nuxia est chargé.
Voici le squelette de configuration avec les valeurs par défaut :

```
nuxia:
	disable_password: false
	mailer:
		from:
			email: "noreply@nuxia.fr"
			name: "Nuxia"
	validator:
		reserved_words: "[]"
```

Nous verrons en détails chaque ligne du fichier configuration dans la fonctionnalité qui l'utilise.

### Fonctionnalités ###

#### Login sans saisir le mot de passe ####

Cette fonctionnalité permet à l'utilisateur de se connecter sans taper le mot de passe lorsque `disable-password` est à `true` (Voir configuration) . Pour se faire, on a surchargé le service `security.authentication.provider.dao`avec notre classe `Nuxia\Bundle\NuxiaBundle\Security\AuthenticationProvider`.

#### Controlleur ####

*todo : Ceci est une ébauche qui est à revoir pour utiliser les bonnes pratiques (utiliser les contrôleurs comme des services et n'injectant que les dépendances utiles).

La classe `Nuxia\Bundle\NuxiaBundle\AbstractController` est la classe de base pour tout les contrôleurs définies en temps que service. Cette classe n'hérite donc pas de `ContainerAware`.

```
parameters:
    #nuxia.controller.class: Nuxia\Controller\AbstractController

services:
	nuxia.controller:
    	class: "%nuxia.controller.class%"
    	scope: "request"
        abstract: true
        public: false
        calls:
            - [ "setRouter", [ "@router" ] ]
            - [ "setTempmlating", [ "@templating" ] ]
            - [ "setRequest", [ "@request" ] ]
```

On note qu'il n'y a pas de constructeur dans cette classe il faut donc le définir dans la classe fille et que la classe est changeable via le paramètre `%nuxia.controller.class%`.

Pour définir un contrôleur comme service il suffira d'écrire l'instruction suivante :

```
services:
	example:controller:
		class: Nuxia/ExampleBundle/ExampleController
		parent: "nuxia.controller"
		arguments:
			#arguments du constructeur
```

En plus d'injecter les services "indispensables" à un contrôleur cette classe fournit un ensemble de méthode dite raccourci :

- `getReferer($default)` permet de générer l'url de la page précédente. Si l'url de la page précédente est égale à celle de la page courante on redirige vers le `$default` pour éviter une boucle infinie. ( Ce système de referer va être revu on va stocker les x dernières url utiliser l'url d'avant plutôt qu'un défaut).

#### Mailer ####

Dans un projet Symfony on a souvent besoin de customizer le rendu des mails avec des templates `Twig`. Le service `nuxia.mailer` permet de réaliser cela :

```
nuxia.mailer:
  class: "%nuxia.mailer.class%"
    arguments:
      - "@mailer"
      - "@templating.engine.twig"
      - {}
      - { language: "fr", template: "NuxiaBundle:Mailer:default"}
```

On constate que le constructeur prend deux tableaux en paramètres (arguments 3 et 4 du constructeur).

##### Le tableau addresses #####

Le premier tableau (3ème argument du constructeur) permet de gérer les différents destinataires et expéditeurs de l'email. Pour chaque élément de ce tableau, on peut définir un email ou un tableau ayant pour clé l'email et valeur le nom a affiché. On peut également définir un tableau d'émail ou de tableaux s'il y a plusieurs destinataires ou expéditeurs.

L'expéditeur par défaut (`from`) est personnalisable via le fichier de configuration (Voir configuration). On peut également écraser la clé `from` du tableau.
Les destinataires `to`, `cc` et `cci` sont définis respectivement avec les clés `to`, `cc` et `bcc`. La clé `to` doit obligatoirement être renseigner avant l'envoi de l'email.

Ce tableau est modifiable uniquement dans le constructeur et dans la méthode `sendMessage` que nous expliquerons ci-dessous.

##### Le tableau template_parameters #####

Le deuxième (4ème argument du constructeur) permet de personnaliser le rendu de l'email.

La clé `language` permet de spécifier le template a utilisé pour le rendu du mail. On la couple avec la clé `language` afin de traduire directement les emails dans les templates (pour ne pas surcharger les fichiers yml).
Avec `NuxiaBundle:Mailer:default` comme template et `fr` comme langage notre template aura le chemin suivante : `NuxiaBundle/Resources/Mailer/fr/default.html.twig`.

La clé `parameters` permet d'ajouter des variables au template. On peut ajouter des simples variable avec la méthode `addTemplate parameters` ou des liens avec la méthodes `addLink`.

##### Objet et l'envoi de l'email #####

Il me manque plus qu 'un objet à notre email. Il y a deux façons de le spécifier :
- On peut utiliser la méthode `setSubject` template.
- On peut définir un block `subject` dans le template.

```
{% block subject %}
    {%- autoescape false -%}
        {{ 'restitution.mail_subject.validated.found'|trans({}, 'messages', language) }}
    {%- endautoescape -%}
{% endblock %}
```

Une fois le mailer correctement configuré, l'envoi de l'email se fait grâce à la méthode `sendMessage($addresses, $template)` dans laquelle on peut surcharger les paramètres du mailer. La variable addresses peut-être un tableau avec les clés vu ci-dessus (Voir le tableau addresses) ou juste un email qui servira de `to`.

##### Envoyer tous les emails à une seule adresse #####

Sur les versions de test et de développement on utilise le paramètre `delivery_address` afin d'envoyer tous les emails à une seul adresse. Ce paramètre est défini dans le fichier `parameters.yml`.

```
parameters:
	delevery_addresse: "example@nuxia.fr"
```

*todo : Il faut trouver un système astucieux pour pouvoir envoyer plusieurs messages le mailer n'est pour le moment pas adapter à cette utilisation.

#### Méthodes supplémentaires dans les templates Twig ####

Le bundle permet de charger des extensions pour `Twig` qui donne accès à des méthodes ou des filtres supplémentaires. Les extensions `Text` et `Intl` qui sont natives à `Twig` (voir <http://tinyurl.com/cer432j>).

Notre extension `Nuxia\Bundle\NuxiaBundle\Twig\NuxiaTwigExtension` ajoute les méthodes et les filtres suivants :

- Le filtre camelize qui permet transformer une chaîne en `CamelCase` ('ordered_list'|camelize -> 'OrderedList').
- Le filtre underscore qui permet transformer une chaîne en `underscore_case`  ('OrderedList'|camelize -> 'ordered_List').


Le chargement d'une extension `Twig` s'effectue dans le fichier `services.yml` avec le tag `twig.extension` :

```
services:
	nuxia.twig.extension:
		class: Nuxia\Bundle\NuxiaBundle\Twig\NuxiaTwigExtension
    	public: false
    	tags:
    		-  { name: "twig.extension" }
```

#### AbstractEntityManager.php ####

Permet d'ajouter des propriétés et des méthodes globales pour les managers d'entity `Doctrine`.


#### LogListener.php ####

Permet d'ajouter des propriétés et des méthodes globales pour les listeners des entités Doctrine `loggable`. Par exemple `getDefaultUser` pour les objets que l'on veut logger.


