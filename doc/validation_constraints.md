# Constraints #

## Général ##

### Les types de contraintes ###

Une contrainte (classe héritant de `Symfony\Component\Validator\Constraint`) peut-être de type `PROPERTY_CONSTRAINT` ou/et `CLASS_CONSTRAINT`. Pour définir le type on utilise la méthode `getTargets`.


```
public function getTargets()
{
    return Constraint::CLASS_CONSTRAINT;
}
```

Ou pour définir une contrainte ayant les deux types : 

```
public function getTargets()
{
    return array(Constraint::CLASS_CONSTRAINT, Constraint::PROPERTY_CONSTRAINT);
}
```

Pour être utilisé en tant que `PROPERTY_CONSTRAINT` la contrainte doit-être défini sous le noeud `properties` du fichier `validation.yml`. On ne pourra accéder qu'à la valeur du champ auquel elle est attachée dans la méthode `validate`. 

```
# Namespace\Bundle\Resources\config\validation.yml
Namespace\Bundle\Entity\MyEntity:
    properties:
    	field1:
        	- MyPropertyContraint:
				option1 "%value%"
				option2: "%value%"
```

Pour le type `CLASS_CONSTRAINT` a contrainte doit-être défini sous le noeud `constraints`. On pourra accéder à l'ensemble des valeurs de l'objet dans la méthode `validate`. 

```
# Namespace\Bundle\Resources\config\validation.yml
Namespace\Bundle\Entity\MyEntity:
    constraints:
        - MyClassContraint:
			option1 "%value%"
			option2: "%value%"
```

### Validateur associé ###

Une contrainte doit toujours être attachée à un validateur. C'est ce validateur qui va appeler la méthode `validate` qui effectuera le processus de validation et qui renverra les erreurs s'il y'en a. Le validateur associé à la contrainte est spécifié dans la méthode `validatedBy`. 

Par convention (et par défaut) `ContraintClass` => `ContraintClassValidator`.

```
public function validatedBy()
{
    return 'myCustomValidatorClass';
}
```

La méthode `validatedBy`peut également faire référence à un service. Le service doit posséder le tag `validator.constraint_validator`.

### Utilisation de la contrainte depuis un formulaire ###

Dans certains cas, on veut appliquer une contrainte à un champ du formulaire (et au non au champ de l'objet). Il faut donc spécifier la contrainte dans le formulaire. Pour se faire, on utilise l'option `constraints` qui est disponible pour toute classe héritant d'`AbstractType`. On ne peut utiliser que des `PROPERTY_CONSTRAINT` avec cette option.

```
$builder->add('field', 'text', array(
	'constraints' => array(new NotBlank(array('message' => 'field.required')),
	),
));
```

Quand on définit notre propre type on peut également spécifier les contraintes depuis la méthode `setDefaultsOptions`.


```
$resolver->setDefaults(array(
	'constraints' => array(new NotBlank(array('message' => 'field.required')),
	),
));
```

### Traduction ###

Les traductions des erreurs se font dans le domaine `validators`. Ce domaine peut-être changé via `app/config.yml`.

```
framemwork:
	validation:
		translation_domain: "domain"
```

### Valider un objet en cascade ###

Quand une table possède une clé étrangère on peut définir imbriqué un sous-formulaire (`Embed form) dans le formulaire courant. Pour utiliser valider l'objet en cascade il y a deux options :

On peut utiliser la contrainte `Valid`

```
# Namespace\Bundle\Resources\config\validation.yml
Namespace\Bundle\Entity\MyEntity:
    properties:
        - MyEmbedProperty:
            - Valid : 
```

Ou l'option `cascade_validation` sur le formulaire parent

```
# Namespace\Bundle\Form\FormType.php
public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'cascade_validation' => true,
        ));
    }
```

### Liens utiles ###

 - Utiliser les groupes de validation : http://symfony.com/doc/master/book/validation.html#validation-groups
 - Cookbooks Symfony : http://symfony.com/doc/current/cookbook/validation/index.html
 - Validation Constraints Reference : http://symfony.com/doc/master/reference/constraints.html
 
## UniqueEntity ##

### Fonctionnement ###

Cette contrainte permet de vérifier qu'un ou plusieurs champs d'une entité `Doctrine` sont uniques.
Cette contrainte est de type `CLASS_CONSTRAINT`.

### Configuration ###

Les options disponibles sont :

- fields : Champ ou groupe de champs à vérifier. 
- message : Message en cas d'erreur.

```
# Namespace\Bundle\Resources\config\validation.yml
Namespace\Bundle\Entity\EntityName:
    constraints:
 		 - Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity:
         	fields: ["field1","field2"]
         	message: "entity_name.already.exists"
```

Il faut ABSOLUMENT que tous les champs présents dans l'option `fields` soient remplis avant le `bind` du formulaire.

## DateTimeRange ##

### Fonctionnement ###

Cette contrainte permet de vérifier qu'une date est avant ou/et après une autre date.
Cette contrainte est de type `PROPERTY_CONSTRAINT`.

### Configuration ###

Les options disponibles sont :

- format : Format d'affichage de la date dans le message d'erreur (`d/m/Y` par défaut).
- after : Date minimum (`null` par défaut).
- before : Date maximum (`null` par défaut).
- afterMessage : Message d'erreur si la date saisie est avant %after% (`field.datetime.range.after` par défaut).
- beforeMessage : Message d'erreur si la date est saisie après %before% (`field.datetime.range.before` par défaut).

Pour `after` et `before` la valeur `now` est acceptée.
Lorsque before est également à `now` le message d'erreur par défaut (`beforeMessage`) est field.datetime.range.past.

```
# Namespace\Bundle\Resources\config\validation.yml
Namespace\Bundle\Entity\EntityName:
    properties:
        field:
            - Nuxia\Component\Validator\Constraints\DateTimeRange:
                beforeMessage: "my.custom.before.message"
                afterMessage: "my.custom.after.message"
                after: "12/01/2000"
                before: "12/01/2010"
                format: "d/m/Y"
```

## ReservedWords ##

### Fonctionnement ###

Cette contrainte permet d'empêcher l'utilisation de mots réservés. Elle possède une liste de mots réservés par défaut qui sont :

```
site, page, page_content, focus, ordered_list, contact, media, user, message
page_list, item_list, message_recipient
new, edit, delete, move-up, move-down, send, duplicate.
```

Cette contrainte est de type `PROPERTY_CONSTRAINT`.

### Configuration ###

On peut personnaliser le message d'erreur avec l'option `message` :

```
# Namespace\Bundle\Resources\config\validation.yml
Namespace\Bundle\Entity\EntityName:
    properties:
        field:
            - Nuxia\Component\Validator\Constraints\ReservedWords:
                message: "my.custom.message"
```

On peut ajouter d'autres mots réservés avec la clé de configuration `reserved_words` de `validator` :


```
# app/config/config.yml
nuxia:
	validator:
		reserved_words: ["mot1","mot2"]
```	

Le mot non autorisé est envoyé comme paramètre (%word%) lors de la traduction.

## Status ##

### Fonctionnement ###

Cette contrainte permet de rendre obligatoire des champs en fonction d'une valeur d'un statut.
Cette contrainte est de type `CLASS_CONSTRAINT`.

### Configuration ###

Les options disponibles sont :

- status : Valeur du statut concernée par le validator
- required_fields : Champs qui doivent être obligatoires

```
# Namespace\Bundle\Resources\config\validation.yml
Namespace\Bundle\Entity\EntityName:
	constraints:
    	- Nuxia\Component\Validator\Constraints\Status:
        	status: "posted"
            required_fields: ["field1","field2"]
```

En cas d'erreur, le message renvoyé est `%underscore_model_name%_%field%_required_%status%`.

```
# Namespace\Bundle\Resources\translations\validators.yml
entity_name:
	field1:
		required:
			posted: "Le field1 est obligatoire pour publier l'objet"
	field2:
		required:
			posted: "Le field2 est obligatoire pour publier l'objet"
```
## Slug ##

### Fonctionnement ###

Cette contrainte permet vérifier que le slug saisi est valide.
Cette contrainte est de type `PROPERTY_CONSTRAINT`.

### Configuration ###

On peut personnaliser le message d'erreur avec l'option `message` :

```
# Namespace\Bundle\Resources\config\validation.yml
Namespace\Bundle\Entity\EntityName:
    properties:
        field:
            - Nuxia\Component\Validator\Constraints\Slug:
                message: "slug.invalid"
```