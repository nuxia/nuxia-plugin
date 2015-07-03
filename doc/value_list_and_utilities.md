# Valuelist yml #

Les `valuelist` sont des données d'application stockés dans un fichier. 

## Stockage ##

Elles sont stockées en `yml` dans le fichier `Namespace/MyBundle/Resources/config/valuelist/%file%.yml` sous la forme d'un tableau.

```
# Namespace/MyBundle/Resources/config/valuelist.yml
valuelist1: ["value1", "value2"]
valuelist2: "value3"
valuelist3:
	subvaluelist3: "value4"
		
```

## Récupération ##

Pour récupérer une `valuelist` on utilise la méthode getValueList($bundlename, $file, $valuelist) de la classe `Nuxia\Component\Parser`.

- `$bundlename` correspond au nom raccourci du bundle (`Namespace/MyBundle` dans notre exemple)
- `$file` correspond au nom du fichier `yml` sans l'extension (`valuelist` dans notre exemple)
- `$valuelist` correspond à la `valuelist`que l'on veut récupérer (`valuelist1` par exemple)

Ainsi 

```
# Sample.php
Parser::getValueList('Namespace/MyBundle', 'valuelist', 'valuelist2');
```

Retourne

```
value3
```

Pour récupérer la `subvaluelist3` dans notre exemple on utilise le séparateur `.` dans le paramètre `$valuelist` de la méthode `getValueList()`

Exemple: 

```
# Sample.php
Parser::getValueList('Namespace/MyBundle', 'valuelist', 'valuelist3.subvaluelist3');
```

Retourne

```
value4
```

# Méthodes utilitaires #

## AbstractModel ##

En plus de la manipulation `json` (*todo lien vers la documentation `json_form_and_object.md`), la classe `Nuxia\Component\AbstractModel` fournit plusieurs méthodes aux objets qui y étendent : 
- La méthode `getModelName($format)` permet de récupérer la classe de l'objet courant en fonction d'un format (`lower`, `camelize` ou `underscore`) passé en paramètre.
- La méthode `getLocation($format)` permet de récupérer le `bundle` ou le `namespace` de l'objet courant en fonction d'un format (`namespace` ou `bundle`).
- La méthode `fromArrayObject($input, array $fields = array())` permet de remplir un objet depuis un autre objet ou un tableau (paramètre $input). Le paramètre `$fields` correspond aux champs à remplir. Lors que $input est un tableau, la valeur par défaut de `$fields` est `array_key($input)`.
- La méthode toArray(array $fields) transforme l'objet courant en tableau. Le paramètre `$fields` correspond aux champs à récupérer.

## Parser ##

`Nuxia\Component\Parser` est une classe utilitaires qui fournit un ensemble de méthodes statiques :

### getBundleClass($bundlename) ###

Cette méthode permet de récupérer la classe d'un bundle (avec son namespace) à partir de son nom court. Cette méthode fonctionne uniquement si le nommage du `bundle` et de son `namespace` doivent respecter les standards de nommage (http://symfony.com/doc/master/cookbook/bundles/best_practices.html).

```
Sample.php
Parser::getBundleClass('Nuxia\Bundle');
```

Retourne

```
Nuxia\Bundle\NuxiaBundle
```

### camelize($string) et underscore($string) ###

C'est deux méthodes sont des alias des méthodes `camelize` et `underscore` de la classe `Symfony\Component\DependencyInjection\Container`. Elles sont à utiliser au sein du `plugin` si on veut casser la dépendance avec `Symfony2` un jour. 

```
Sample.php
Parser::camelize('model_name');
Parser::underscore('ModelName');
```

Retourne

```
Modelname
model_name
```

Par convention, on crée une méthode statique sur la classe utilisant la `valuelist`. 

```
# Entity.php
public static function getStatusChoices($combine = true)
{
	$statuses = Parser::getValuelist('Namespace\MyBundle', 'entity', 'status');
    if ($combine) {
    	return Parser::prefixArray($statuses, 'status');
    }
    return $statuses;
}
```

Ici Le paramètre `combine` permet la traduction dans les formulaires.

## prefixArray(array $array, $prefix = '', $type = 'ARRAY_ASSOC') ##

Cette méthode permet préfixer toutes les valeurs d'un tableau `$array` avec une chaine `$prefix`. Le paramètre `$type peut prendre `ARRAY_ASSOC` ou `ARRAY_INDEX` comme valeur. IL permet de définir le type de tableau à retourner.

```
Sample.php
$array('posted', 'draft');
Parser::prefixArray($array, 'status');
Parser::underscore($array, 'status', ARRAY_INDEX);
```

Retourne

```
array('posted' => 'status.posted', 'draft' => 'status.draft')
array('status.posted', 'status.draft')
```

### cleanArray($array) ###

Cette méthode permet de supprimer les clés d'un tableau non associées à une valeur.

```
Sample.php
$array = array('key1' => value1', 'key2' => array('subkey2' => array()), 'key3' => null);
Parser::cleanArray($array);
```

Retourne

```
array('key1' => value1')
```

## filterArrayByKeys(array $array, array $keys) ##

Cette méthode permet de récupérer une partie d'un tableau correspondant aux clés passées en paramètre.

```
Sample.php
$array = array('key1' => value1', 'key2' => 'value2', 'key3' => 'value3');
Parser::filterArrayByKeys($array, array('key1', 'key3');
```

Retourne

```
array('key1' => value1', 'key3' => 'value3');
```
