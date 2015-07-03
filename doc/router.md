# Router personnalisé #

## Fonctionnement ##

Le bundle surcharge le paramètre `%router.options.generator_base_class%` par notre classe `Nuxia\Component\Routing\UrlGenerator` permettant ainsi de construire des urls en fonction du paramètre `_object` (s'il est présent) en appelant les accesseurs correspondant à chaque paramètre obligatoire de l'url demandée.

## Exemple d'utilisation ##

La route suivante est définie dans `routing.yml` :

```
# routing.yml
site_show: 
  url: /site/:country/:name
```

Et un objet `$site` de type `Site` ayant pour `name` "Marseille" et pour `country` "France".

```
# template.html.twig
{{ path('site_show', {'_object': site) }} => /site/france/marseille
```

ou

```
# Controller.php
$this->container->get('router')->generate('site_show', array('_object' => $site));
```

Retourne dans les deux cas :
 
```
/site/france/marseille
```

## Surcharge ##

On peut surcharger un ou plusieurs paramètres en le précisant.

```
# Controller.php
$this->container->get('router')->generate('site_show', array('_object' => $site, 'name' => 'toulouse'));
```

Retourne :
 
```
/site/france/toulouse
```

## Clé ètrangère ##

Imaginons à présent que `country`soit un objet avec une propriété `label`et que `site`possède une clé étrangère `country_id` avec l'accesseur `getCountry()`. On peut parcourir l'arborescence de l'objet en l'utilisant le caractère `_` dans notre paramètre obligatoire de route.

Il suffit donc de modifier la route pour obtenir le même résultat que dans le premier exemple:  

```
# routing.yml
site_show: 
  url: /site/:country_label/:name
```

```
# Controller.php
$this->container->get('router')->generate('site_show', array('_object' => $site));
```

Retourne :
 
```
/site/france/marseille
```


