CHANGELOG
=========

### 3.0

- Fixed Symfony 3.1 and 4.0 deprecations

### 2.8

- NuxiaDynamicMediaBundle has been removed
- AbstractMedia and MediaExtension has been removed
- Fixed twig deprecations : getGlobals and initRuntime
- Fixed Symfony 2.8 deprecations
- Deleted LoginType and LoginFormHandler (useless with the new component Guard Authentification)
- Added json fields management traits and interfaces
- Deleted NuxiaResetType (useless)

### 2.7

#### Captcha

- Ce composant a été supprimé et passer en natif dans les projets le nécessitant.

### 2.6

#### Admin

- Le component a été supprimé. Il sera mis sur github et portera le nom List. Il sera conçu sur ce modèle https://www.youtube.com/watch?v=gDzd1kq8KH4

#### Bundle

- Ajout d'une traduction `choice.filter.placeholder`. Il faut remplacer form.choices.all par cette traduction et la supprimer de `app/Ressources`
- Ajout d'une méthode `is_form_type_of` pour savoir si un formulaire hérite d'un certain type.
- La fonction `twig` `join_attribute` a été renommé `concat_attribute` (voir `UPGRADE.md`)
- Ajout de traductions dans nuxia (voir `UPGRADE.md`).
- Suppression du scope `request` (voir `UPGRADE.md`).
- Ajout d'un html extension
- Ajout de traductions globales
- Ajout d'un template pour afficher les `flashbags` (voir `UPGRADE.md`).
- Passage du plugin sur github (voir `UPGRADE.md`).
- Suppression du bundle `NuxiaFrontendBundle` (voir `UPGRADE.md`).
- Suppression des spaceless inutiles pour les performances.

Controller :

 - Ajout de méthodes raccourcis `generateUrl`, `createAccessDeniedException`, `createNotFoundException`, `addFlash`et `render`. Ces méthodes sont similaires à du base `controller` de `Symfony`
 - Suppression et changement de plusieurs méthodes
 - Le service `requestStack` n'est plus injecté (voir `UPGRADE.md`)
 - Injection service `flashBag` pour ajouter les `flash notices` (voir `UPGRADE.md`)


#### Config

- Nouveau component. Il est sur github


#### Console

- La logique du `persist par lot a été déplacé dans un nouvel `helper` : `PersistHelper`

#### Doctrine
- La classe AbstractLogListener a été supprimée
- Gestion du `not` dans les `query builder` simplifié

#### Form

- L'option `is_required` dans `DateRangeType` a été supprimé au profit de `required`. (voir `UPGRADE.md`)

#### Helper

- Les classes StringHelper est désormais deprecated et seront supprimées en 3.0. Elle doit être remplacée par des solutions déjà existantes (voir commentaire sur la classe).
- La classe StringManipulator a été supprimé. En cas de reliquat, il faut utiliser la classe CriteriaParser.
- Le namespace Model a été supprimé et mis sur Github à l'url suivante https://www.github.com/nuxia/Tools

#### HttpFoundation

- La méthode set a été supprimée dans la classe `FlashBag`
- Les classes `ControllerBag` et `ControllerBagInterface` ont été déplacées dans `NuxiaBundle. Elles se basent sur les classes `ParameterBag` et `ParameterBagInterface` du nouveau composant `Config` (voir `UPGRADE.md`)

#### Mailer

- Possibilité d'envoyer un `SwiftMessage` dans la méthode `sendMail` du `mailer` (utile pour `LexikMailerBundle`)

#### Paginator

- Possibilité de `définir` la limit via la `query_string`.

#### Parser

- Ajout d'une méthode `pickRandomValue` pour piocher une élément aléatoire dans un tableau

#### Routing

- On peut utiliser le router pour pointer sur une url extérieur. Il faut ajouter `_external: true` dans les `defaults` de la route

#### Security

- Utilisation du nouveau service `authenticationUtils` pour le login (voir `UPGRADE.md`)

### 2.5

#### Form

- L'affichage de l'help se fait maintenant grâce à l'`helper` de vue `form_help` ce qui permet d'utiliser la hiérarchie des `FormType` (comme `form_widget`, `form_row` et `form_label`)

### 2.4.00

- Debug global

#### Json

Il ne faut plus utiliser les méthodes pour gérer le `json`de `AbstractModel`. Il suffit d'utiliser les setters et getters par défaut de la classe.

#### Command

* Ajout d'une méthode `addHelper` dans `AbstractCommand` pour utiliser des `custom helper`. Deux sont disponibles aujourd'hui `QuestionHelper` et `FormatterHelper`. Ils améliorent leurs homonymes existant dans Symfony.

#### Controller

- Debug de la méthode `getReferer`
- Ajout d'une méthode `initControllerBag`

#### Form

- Nouveau type `nuxia_form` servant de parent pour les formulaires `root` qui ajoute le `submit` et la mention champs obligatoires
- Amélioration du template `NuxiaBundle:Form:form.html.twig` pour les formulaires pour faciliter l'ajout d'actions sous un formulaire`
- Suppression complète de embed_media il faut maintenant utiliser le nouveau système de media (Voir `UPGRADE.md`)
- Ajout de commentaires dans le `form_layout` pour expliquer chaque surcharge
- Ajout d'une variable `render_as_action` dans les vues des formulaires permettant de `render` certains champs à part (même si on appelle `form_rows`). Cette variable est utilisées pour les regrouper les boutons dans une div `form-actions`.
- Ajout de traductions pour défaut pour les formulaires
- Remise `{{ block('widget_container_attributes') }}` dans `form_widget_compound` pour le type `collection`
- Mise en place des messages globaux (Voir `UPGRADE.md`)
- Conformément à Symfony2.4, l'option `required`doit être définie dans `buildForm` et non dans `finishView`
- Création du type `DateRangeType` pour filtrer dans un intervalle de date

#### Mailer

- La classe `NewMailer` a été renommée `Mailer` et l'ancienne classe `Mailer` a été supprimée (Voir `UPGRADE.md`)
- Suppression de la gestion de queue dans les emails (automatiquement gérée par Symfony) (Voir `UPGRADE.md`)

#### HttpFoundation

- Création du bundle component HttpFoundation
- Ajout d'une méthode `toTemplateVars` sur les `ControllerBag` pour les transformer en paramètres exploitable par la `view

### FileUtils

- Ajout d'un nouveau component `FileUtils` permettant de lire des fichiers

#### Sortable

- La classe `SortableManager` a été renommée en `AbstractSortableManager` (Voir `UPGRADE.md`)
- La classe `SortableRepository` a été renommée en `AbstractSortableRepository` (Voir `UPGRADE.md`)
- AbstractSortableManager renvoie `danger` au lieu de `error`. (Voir `UPGRADE.md`)


### Validator

- Mise en place des messages globaux (Voir `UPGRADE.md`)
