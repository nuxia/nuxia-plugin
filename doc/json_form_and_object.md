# Manipulation des champs jsons #

Dans nos base de données, nous avons pour habitude d'utiliser des champs `json` pour stocker divers informations. Nous avons donc codé plusieurs classes permettant de faciliter le stockage dans ces champs ainsi que la récupération.

## Modèle ##

### Classe AbstractModel ###

Coté modèle, il suffit de faire étendre la classe `entity` de `Nuxia\Component\AbstractModel`. Cette classe permet d'ajouter les méthodes suivantes à notre `entity` :  

- La méthode `getJsonField($field, $path, $filter)` permet de récupérer une valeur ou la totalité d'un champ json. Avec le paramètre `$filter` on peut spécifier les clés que l'on veut récupérer.
- La méthode `setJsonField($field, $path, $value)` permet de modifier la valeur ou la totalité d'un champ json. Cette méthode fonctionne de la même façon que `getJsonField`. On peut également supprimer un champ dans le json en mettant le valeur d'une clé à `null`. 

Exemple avec a un objet `$site` ayant un champ `parameters` avec la valeur suivante:

```
{
  "champ1": "valeur1",
  "champ2": "valeur2",
  "champ3": {
    "souschamp1": "sousvaleur1"
  }
}
```

- `$site->getJsonField('parameters')` retourne la totalité du champ decodé en tableau php
- `$site->getJsonField('parameters', 'champ1')` retourne "valeur1"
- `$site->setJsonField('parameters', 'champ2', 'autrevaleur')` remplacer "valeur2" par "autrevaleur"
- `$site->getJsonField('parameters', 'champ3.souschamp')` retourne "sousvaleur1"
- `$site->setJsonField('parameters', 'champ3.souschamp', null)` supprime la clé "champ3"

Les champs `json` modifiés sont loggués avec la propriété `jsons_to_encode`.

### Le listener JsonListener ###

Grâce à la classe `Nuxia\Component\Doctrine\EventListener\JsonListener` le champ est réencodé en json avant la sauvegarde en base. Pour utiliser `ce subscriber` il faut absolument que la classe `entity` étende de `Nuxia\Component\AbstractModel`. Cet appel est automatique il n'y a rien à implémenter dans le code.

## Formulaire ##

### L'option property_path et DecodeJsonEvent ###

Par défault, un formulaire Symfony2 utilise les getters/setters correspondant au nom du champ de la classe du formulaire (option `data_class`) pour récupérer ou sauvegarder les données. Dans le cas où la valeur du champ est un tableau on peut utiliser l'option `property_path` pour effectuer ce processus à partir d'une clé du tableau. Cette option est indispensable dans le cas d'un champs `json`. Le problème c'est qu'une erreur se produit si la valeur n'est pas converti en tableau avant le chargement des données dans le formulaire. Pour pallier à cela, il faut utiliser l'événement `Nuxia\Component\EventListener\DecodeJsonEvent` en ajoutant la ligne suivante dans la méthode `buildForm : 

```
$builder->addEventListener(FormEvents::PRE_SET_DATA, array(new DecodeJsonEvent(array('parameters')), 'decodeJson'));
```

### Champ dédié au formulaire ###

Quand le champ `json` est le résultat d'un formulaire (ce champ s'appelle en général `fields` dans nos bases de données) on doit utiliser la classe `Nuxia\Component\JsonType` (alias : `nuxia_json`). Cette classe s'utilise de la même façon que `Nuxia\Component\ArrayType`(Voir documentation `README.md` construire un formulaire à partir d'un tableau). Contrairement à `ArrayType`, la méthode `getData` renverra du `json` (à la place d'un tableau). Cela a été réalisé en ajoutant le `transformer`de modèle `Nuxia\Component\Form\DataTransformer\JsonToArrayTransformer`.

```
# FormType.php
$builder->add('fields', 'nuxia_json', array('fields' => array $fields));
```