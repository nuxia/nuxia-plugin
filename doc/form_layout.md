# Customizer le rendu des formulaires #

## Utiliser le layout du bundle ##

Le bundle fournit un `layout` de formulaire. Pour mettre en place ce layout dans notre projet Symfony il faut modifier le fichier `config.yml`.

```
#app/config/config.yml
twig:
    ...
    form:
      resources:
        - "NuxiaBundle:Form:form_layout.html.twig"
```

Voici quelques spécificités du `layout` :
- Les labels des champs obligatoires sont affichés avec une étoile.
- Les erreurs sont dans affichés dans des `em` ayant pour classe `error`.
- Les aides sont dans affichés dans des `em` ayant pour classe `help`. Pour utiliser l'aide il faut définir une variable help dans la méthode finishView du formulaire.
- Possibilité d'ajouter une aide globale sur le formulaire (exemple : * champs obligatoires).
- Le label n'est pas affiché quand il n'est pas spécifié (Par défaut Symfony2 humanize le nom du champ et l'utilise comme label)
- Les champs ayant la variable `render_as_action` sont affichés dans un div `form-actions` 

Dans la plupart des cas, il est plus judicieux de créer un `layout` pour le projet héritant de `NuxiaBundle:Form:form_layout.html.twig` et de mettre ce `layout` dans le fichier `config.yml`.

Le bundle fournit également un template (`NuxiaBundle:Form:form.html.twig`) pour afficher un formulaire de façon très basique. Ce template prend en paramètre un objet FormView et on peut ajouter des liens en dessous du form en surchargeant le bloc `actions_content`.

## Changer le rendu de la mention champ obligatoire ##

Il suffit de définir la variable `required_render` dans le bloc `form_label`.

## Regrouper les actions dans la div form-actions ##

Il suffit de définir la variable `render_as action` dans la méthode `finishView`

```
$view->children['submit']->vars['render_as_action'] = true;
```

Il suffit de définir la variable `required_render` dans le bloc `form_label`.

```
{% block form_label %}
{% spaceless %}
    {% set required_render = ' <span>*</span>' %}
    {{ parent() }}
{% endspaceless %}
{% endblock form_label %}
```

## Liens utiles ##

- Le layout par défaut de Symfony : http://tinyurl.com/bpqtrlk
- Le cookbook : http://symfony.com/fr/doc/master/cookbook/form/form_customization.html


