# Formulaire #

## Généralités ##

### Créer un type personnalisé ###

Il y a 3 choses importantes à savoir pour créer son propre type : 
- Il faut faire étendre la classe de `Symfony\Component\Form\AbstractType`. 
- Dans le cas d'un héritage, il ne faut pas toucher à l'`extends` de la classe mais définir le parent grâce à la méthode `getParent`.
- La méthode `getName` est obligatoire. Par convention, on préfixe le type par le nom du bundle `underscored`. 

### Enregistrer un type ###

Pour enregistrer un type de formulaire on utilise le fichier `services.yml` et on utilise le tag `form.type`

```
my_registered.form.type:
	class: Nuxia\Component\Form\Type\CaptchaType
    arguments:
        - "@service1"
    tags:
    	- { name: "form.type", alias: "my_alias" }
```

L'alias doit correspondre à la valeur de retour de la méthode `getName()` du `FormType`. Cette chaîne permet de faciliter l'instanciation du formulaire depuis un `FormBuilder` ou depuis la `FormFactory`.

```
# FormType.php
$builder->add('my_form', 'my_alias');
```

```
# Controller.php
$this->container->get('form.factory')->createNamed('my_form', 'my_alias');
```

L'enregistrement d'un type n'est pas forcément nécessaire c'est surtout utile lorsque le `FormType` a besoin de services supplémentaires.

### Séparer la vue dans le FormBuilder ###

Beaucoup de variables de vue sont définissables via les options du builder. Cependant il faut définir ces variables (`label`, `help`, `attr` `label_attr`, `empty_value`, `required`..) dans la méthode `finishView` pour ne allourdir le `FormBuilder` lors du traitement des données.

## Liens utiles ##

 - Form Types Reference : http://symfony.com/doc/master/reference/constraints.html

## FormType ##

### Fonctionnement ###

La classe `Nuxia\Component\Form\Type\FormType` sert de parent pour tous les formulaires `root`.

Elle ajoute un champ `submit` au formulaire et l'aide globale `* Champs obligatoires`

### Configuration ###

Dans ce formulaire, on peut juste modifier les variables de vues de `submit`et le label de l'aide globale.

### Affichage ###

On peut utiliser le template `NuxiaBundle:Form:form.html.twig` comme affichage par défaut pour ce type.

## FilterType ##

### Fonctionnement ###

La classe `Nuxia\Component\Form\Type\FilterType` sert de parent pour tous les formulaires `filter`.

Elle ajoute un champ `filter` au formulaire et un champ `reset` si l'option `reset` est activée.

Par défault, ces formulaires ont comme méthode `GET` et la protection `CSRF` est désactivée.

### Configuration ###

Les options disponibles sont :

- process_data : Si cette option est à `true` l'`app_data` des champs de type `text` sera entourée par des `%` (pour le `LIKE`)
- reset : Si cette option est à `true` le champ `reset` est ajouté au formulaire

Comme pour `FormType`, on peut modifier les variables de vues des champs `filter` et `reset`

### Affichage ###

Etant donné qu'un `filter_form` est automatiquement `bind` la variable `display_filter` est disponible dans les `vars` pour savoir si des filtres sont actifs ou non.

On peut utiliser le template `NuxiaBundle:Form:form.html.twig` comme affichage par défaut pour ce type.

## ArrayType ##

### Fonctionnement ###

La classe `Nuxia\Component\Form\Type\ArrayType` permet de créer un formulaire dynamique en fonction d'un tableau. 

L'alias du formulaire est `nuxia_array`.

### Configuration ###

Les options disponibles sont :

- fields : Définition du formulaire. Cette option est obligatoire.
- constraint_messages : Message par défaut des validateurs. Cette option est optionnelle.

### Définition du formulaire ###

On l'instancie en envoyant l'option `fields` un tableau formé comme ceci :

```
array('%nom du champ%' => 
	array(
		'type' => string '%type%, 
		'options' => array %options%, 
		'view' => array %view%
		'constraints => array %constraints%
	)
)
```

- `%type%` correspond au type du champ (exemple `choice`, `text`, `file`)
- `%options%` correspond aux options de la méthode `add` du `FormBuilder`(exemple : `choices`, `empty_value`, `translation_domain`).
- `%view%` correspond aux variables de `FormView` (exemple: `label`, `help`, `attr`(`placeholder`, `class`))
- Le paramètre `%constraints%` correspond aux options de validation.

Pour le type `choice`, une  `%choices_prefix%` permet de définir un préfixe pour la traduction dans les fichiers `yml`.

On peut coupler cette classe avec notre classe `Parser` pour définir le tableau dans un fichier `yml`.

### Validation ###

Pour `%constraints%` plusieurs format sont acceptés :

```
# Format 1
array('NotBlank' => array(), 'Length' => array('min' => 2, 'minMessage => 'field.min_length')
# Format 2
array('NotBlank', 'Choice')
# Format 3
array('NotBlank', 'Length' => array('min' => 2, 'min_message => 'field.min_length', 'Nuxia\Bundle\Validator\Constraint\MyCustomConstraint')
```

Lorsque aucun `\` n'est présent dans le nom de la contrainte le type va cherche dans le namespace `Symfony\Component\Validator\Constraints`.

L'option `constraint_messages` est également disponible pour ce type. Elle permet de spécifier les messages par défaut des contraintes. Plusieurs format sont acceptés :

```
# Format 1
array('NotBlank' => array('message' => 'field.required'), 'Lenght' => array('minMessage' => 'field.min_length')
# Format 2
array('NotBlank' => 'field.required')
# Format 3
array('NotBlank' => 'field.required', 'Lenght' => array('minMessage' => 'field.min_length')
```

Il n'est pas utile de préciser la clé message.

### Exemple d'utilisation ###

```
#Resources/valuelist/form.yml
fields:
	field1:
  		type: textarea
  		constraints: "NotBlank"   		  		
    	view:  
    		label: "ad.condition.place.label"
   
	field2:
		type: choice
  		options:
    		choices_prefix: "ad.condition.mode"
    		choices: ["choice1","choice2"]       
		view:
			label: "ad.condition.mode.label"    
```

```
# FormType.php
$fields = Parser::getValuelist('Namespace\Bundle', 'form', 'fields');
$builder->add('fields', 'nuxia_array', array('fields' => $fields));
```

##  CaptchaType ##

### Fonctionnement ###

La classe `Nuxia\Component\Form\Type\CaptchaType` permet de générer un calcul aléatoire que l'utilisateur doit résoudre afin de prouver qu'il n'est pas un robot.
Par défaut les opérations possibles sont les suivantes : 
- `[6-11] + [1-5]`
- `[6-11] - [1-5]`
- `[6-11] * [1-5]`

L'alias du formulaire est `nuxia_captcha`.

### Configuration ###

```
# FormType.php
$builder->add('captcha', 'nuxia_captcha');
```

Par défaut le layout du formulaire est le suivant (on peut bien évidemment le surcharger si nécessaire) :

```
# Nuxia\NuxiaBundle\Form\form_layout.html.twig
{% block nuxia_captcha_widget %}
    <div class="captcha widget">
        <img src="{{ image }}" />
        {{ form_widget(form) }}
    </div>
{% endblock %}

{% block nuxia_captcha_errors %}
{% spaceless %}
    {% if errors|length > 0 %}
        {% for error in errors %}
            <em class="error">{{ error.message|trans({}, 'validators') }}</em>
        {% endfor %}
    {% endif %}
{% endspaceless %}
{% endblock nuxia_captcha_errors %}
```

Il faut traduire les messages suivants : 

- form.captcha.label dans le domaine `form` par défaut
- captcha.invalid dans le domaine `validators` par défaut

```
# app/Resources/translations/form.fr.yml
form.captcha.label: "Captcha"

# app/Resources/translations/validators.fr.yml
captcha.invalid: "Le resultat de l'operation est incorrect"

```

Pour avoir l'image au dessus du input on peut ajouter les lignes suivantes dans le `css` :

```
# form.css
form div.captcha.widget img {
    display: block;
    margin-bottom: 15px;
}
```