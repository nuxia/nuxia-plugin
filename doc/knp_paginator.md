# Knp paginator #

## Installation #

```
# AppKernel.php
public function registerBundles()
{
   // ...
   new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
   // ...
}
```

## Configuration ##

Pour customizer les templates et le nom du paramètre page il suffit d'ajouter les lignes suivantes le fichier config.yml du projet (/app/config/config.yml)

```
knp_paginator:
    page_range: 5                      # default page range used in pagination control
    default_options:
        page_name: page                # page query parameter name
    template:
        pagination: :Pagination:sliding.html.twig
        sortable: :Pagination:sortable_link.html.twig
```

- template.pagination correspond au template affichant les liens vers les pages.
- template.sortable correspond au template affichant le lien de tri

## Le template sliding ##

Dans le template `sliding` plusieurs variables sont accessibles :

- first, last, previous et next correspondent aux numéros des pages (première, dernière, précédentes et suivantes respectivement).
- current correspond à la page courante.
- route correspond à la route définie dans le `pager` (par défaut c'est la route courante)
- query correspond aux paramètres de la `query string` (par défaut c'est ceux de l'url courange)
- pagesInRange correspond à la liste des pages à afficher (le nombre est personnalisable avec l'option `page_range` de `config/yml`)
- pageParameterName permet de connaître le nom du paramètre de page (option `page_name`et de `config.yml`).

Exemple d'affichage de la liste des liens "numériques"

```
# Pagination:sliding.html.twig
{% for page in pagesInRange %}
	{% if page != current %}
    	<a href="{{ path(route, query|merge({(pageParameterName): page})) }}">{{ page }}</a>
    {% else %}
        <span>{{ page }}</span>
    {% endif %}
{% endfor %}
```

Exemple d'affichage d'un lien page précédente: 

```
# Pagination:sliding.html.twig
{% if first is defined and current != first %}
	<a class="first sprite" href="{{ path(route, query|merge({(pageParameterName): first})) }}"></a>
{% endif %}
```

## Le template sortable ##

```
# app/Resources/Paginator/sortable_link.html.twig
<a {% for attr, value in options %} {{ attr }}="{{ value }}"{% endfor %}>{{ title }}</a>
```
Pour afficher le lien de tri on utilise la méthode `knp_pagination_sortable`.

```
# Namespace\MyBundle\Resources\views\paginator.html.twig
{{ knp_pagination_sortable(paginator, 'label|trans({}, 'messages', 'alias.entity_field')|raw}
```

### Divers ###

Dans le cas où on veut avoir plusieurs pager sur la même page il faut passer un autre nom pour le paramètre page à l'un des pagers. Pour se faire on utilise l'option `pageParameterName` de la méthode `paginate`.

```  
# Controller.php
$request = $this->container->get('request');
$page = $request->query->get('p_manager', 1);
$paginator = $this->container->get('knp_paginator');
$paginator->paginate($query, $page, 10, array('pageParameterName' => 'p_manager'));
```

De la même façon (ou avec la méthode `setParam`) on peut surcharger toutes autres les options de `config.yml`. Par exemple avec les options `sort_field_name` et `sort_direction_name` on peut surcharger les tris par défaut.

```
# Controller.php
if (!$request->query->has($paginator->getPaginatorOption('sortFieldParameterName'))) {
	$buffer = $query->getDQLPart('orderBy');
    $order = explode(' ', $buffer[0]);
    $paginator->setParam($paginator->getPaginatorOption('sortFieldParameterName') $order[0]);
    $paginator->setParam($paginator->getPaginatorOption('sortDirectionParameterName'),$order[1]);
}
```

### SortListener ###

La classe `Nuxia\Component\Pager\EventListener\SortListener` permet d'injecter le tri par défaut de la requête dans le `pager` (Les lignes précédentes ne sont donc pas à préciser dans le `controller`). Grâce à cette classe, l'utilisation de la méthode `isSorted` de la classe `Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination` (méthde permettant de savoir si une colonne est triée ou non) est alors possible avec le tri par défaut de `Doctrine`. 

### Liens utilises ###

https://github.com/KnpLabs/KnpPaginatorBundle