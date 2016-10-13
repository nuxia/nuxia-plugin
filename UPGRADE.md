# UPGRADE 3.0

- The deep parameter doesn't exist anymore in method ParameterBag::get. You should store the result in an array instead

# UPGRADE 2.8

- NuxiaDynamicMediaBundle has been removed. If you need it add nuxia/dynamic-media-bundle on your project composer
- AbstractMedia and MediaExtension has been removed. If you need it add nuxia/media-bundle on your project composer
- NuxiaResetType has been deleted. You must rely on ResetType if you used it or overrided the nuxia_reset_widget layout block.

#### Bundle

- Il faut supprimer le scope `request` dans tous les fichiers services `.yml`.

### FrontendBundle

The bundle has been removed.

- You must use `bower` to download the css and js packages needed :

 - select2
 - bootstrap3
 - charcounter
 - bootstrap-datepicker

- You must delete the following line from you AppKernel.php :

 ```
  new Nuxia\Bundle\FrontendBundle\NuxiaFrontendBundle()
 ```

- You must delete the 3 following lines from your composer.json under the autoload section :
```
 "Nuxia\\Bundle\\FrontendBundle\\": "vendor/nuxia/nx-frontend-bundle/Nuxia/Bundle/FrontendBundle"
 ```
 ```
 "Nuxia\\Bundle\\": "vendor/nuxia/nuxia/src/Nuxia/Bundle"
 ```
 ```
 "Nuxia\\Component\\": "vendor/nuxia/nuxia/src/Nuxia/Component"
 ```

- You must add the following line on your composer.json under the require section :
  ```
  "nuxia/nuxia-plugin": "2.7.x-dev"
  ```

- The `flashbag.html.twig` file path has changed. You must edit your twig layout.

 Before:
``` twig
   {% include '@NuxiaFrontend//flashbag.html.twig' %}
```

 After:
``` twig
   {% include '@Nuxia/flashbag.html.twig' %}
```

- The `select2-translation.js` file path has changed. You must edit your config.yml.

 Before:
``` yml
- "@NuxiaFrontendBundle/Resources/public/select2/js/select2-translations.js"
```

 After:
``` twig
- "js/select2-translations.js"

```

#### Controller

- The `redirectToUrl` and `redirectFromUrl` methods has been deleted in favor of `redirect`
- The `getReferer` and `redirectToReferer` takes now request + route + route parameters as parameters (url before)

Before:
``` php
   public function showAction()
   {
   	  return $this->redirectToReferer($this->generateUrl('homepage',
   	  	array('_locale'=> 'fr));
   }
```

After:
``` php
   public function showAction()
   {
   	  return $this->redirectToReferer('homepage', array('_locale' => 'fr');
   }
```

- The `requestStack` dependency is not injected anymore. `$this->request` wont be available as well. You should use the automatically injected Request parameter instead

Before:
```
   public function showAction()
   {
   	  $parameter = $this->request->query->get('parameter');
   }
```

After:
```
   public function showAction(Request $request)
   {
   	  $parameter = $request->query->get('parameter');
   }
```

- The `forward` method now takes one additional parameter `Request` as first parameter

#### Formulaire

- L'option `is_required` dans `DateRangeType` a été supprimé au profit de `required`. (voir `UPGRADE.md`)

#### HttpFoundation

- The `ControllerBag` and `ControllerBagInterface` classes have been moved in `NuxiaBundle\Controller' namespace. They inherit from the new `Config` component classes (`ParameterBag` and `ParameterBagInterface`).

Before:
```
   use Nuxia\Component\HttpFoundation\ControllerBagInterface;
   use Nuxia\Component\HttpFoundation\Controller;
```

After:
```
   use Nuxia\Bundle\NuxiaBundle\Controller\ControllerBagInterface;
   use Nuxia\Bundle\NuxiaBundle\Controller\ControllerBag;
```

#### Security

- You must use the new `authenticationUtils` on `SecurityController` to handle the login

#### Translation

- Some validation generic translations have been added. You have to delete the duplications on app/Ressources/translations/validators.yml

#### Twig

-  La fonction `twig` `join_attribute` a été renommé `concat_attribute` (voir `UPGRADE.md`)

# UPGRADE 2.5

#### Parser

Désormais le test en amont du `prefix` est inutile lors de l'utilisation de la méthode `prefixArray`.

Avant:
```
   if ($prefix !== null) {
   		Parser::prefixArray($array, $prefix);
   }
```

Après:
```
   Parser::prefixArray($array, $prefix);
```


# UPGRADE 2.4

#### Console

* Pour utiliser l'api du bundle tous les commandes existantes doivent être définies comme service.
* Le component `Command` s'appelle désormais `Console` pour correspondre à Symfony

#### Flashbag

 * Ajout des flashs statiques et des paramètres de traduction.

Avant:
```
   {% for flash in flashes %}
      {{ flash|trans({}, 'flash) }}
   {% endfor %}
```

Après:
```
   {% for flash in flashes %}
	  #flash.autoclose : Permet de savoir si le flash est statique ou non
      {{ flash.message|trans(flash.translation_parameters, 'flash) }}
   {% endfor %}
```

## Mailer

- La classe `Mailer` (`nuxia.mailer`) a été supprimée et remplacée par `NewMailer` (`nuxia.new_mailer`)
- Suppression de la gestion de queue dans les emails

Avant:
```
   $this->mailer->addMail($mail1);
   $this->mailer->addMail($mail2);
   $this->mailer->sendMails();
```

Après:
```
   $this->mailer->sendMail($mail1);
   $this->mailer->sendMail($mail2);
```


## Sortable

- La classe `SortableManager` a été renommée en `AbstractSortableManager`
- La classe `SortableRepository` a été renommée en `AbstractSortableRepository`
- Refactoriser toutes les traductions des messages d'erreur des `flashbags` provenant du component `Sortable` (il faut remplacer `error` par `danger`

## Form

- Le formulaire `embed_media` n'existe plus il faut utiliser le nouveau système de média (`NuxiaMediaBundle` plus tard)
- La surcharge de `choice_widget_expanded` dans `form_layout` a été supprimée.
Si votre projet utilise la classe `expanded` il faudra peut-être la remettre la surcharge

```
{% block choice_widget_expanded %}
    {% spaceless %}
        {%- for child in form -%}
            {{ form_widget(child) }}
            {{ form_label(child, null, {'required': false, 'label_attr': {'class': 'expanded' } }) }}
        {%- endfor -%}
    {% endspaceless %}
{% endblock choice_widget_expanded %}
```
- Les messages `form.submit`, `filter.submit`, `filter.reset` et `required_fields.help` ont été passés dans le bundle. Il faut donc les supprimer de `app/Resources/translations\form.xx.yml` s'ils sont présents et semblables à ceux de Nuxia
- Deplacer les required dans `buildForm` (avant `finishView`) et vérifier plus particulièrement le type `entity`.

### Validator

- Les messages `slug.invalid`, `reserved_words.invalid`, 'captcha.invalid` et `field.required` ont été passés dans le bundle. Il faut donc les supprimer de `app/Resources/translations\validator.xx.yml` s'ils sont présents et semblables à ceux de Nuxia
