# Securité #

Sous Symfony2, les tests de droits se font grâce la méthode `isGranted` du `SecurityContext`. Dans le cas de nos applications multi-sites cette méthode devient très vite limitée. Pour pallier à cela, le bundle fournit un `manager` de sécurité par défaut à étendre sur chaque projet.

```
nuxia.security.manager.default:
	class: Nuxia\Component\Security\BaseSecurityManager
    public: false
    calls:
    	- [ "setSecurityContext", [ "@security.context" ] ]
```

## Création du manager de sécurité ##

Pour hériter du service instanciant le manager de sécurité par défaut (`nuxia.security.manager.default`) on utilise l'option `parent` dans le fichier `yml`.  Cette option permettra d'appeler d'injecter automatiquement le service `security.context` dans le service enfant mais elle ne remplace pas l'héritage de la classe `Nuxia\Component\Security\BaseSecurityManager` qui doit figurer dans la classe fille.
Le nom du service instanciant le manager doit être `nuxia.security.manager` pour profiter de la surcharger de l'extension `SecurityExtension`.

```
# services.yml
	nuxia.security.manager: 
		parent: "nuxia.security.manager.default"
		class: Namespace\MyBundle\Security\MySecurityManager
		calls:
            - [ "mutateur1", [ "@dependance1" ] ]
            - [ "mutateur2", [ "@dependance2" ] ]		
````

```
# Namespace\MyBundle\Security\MySecurityManager.php
class MySecurityManager extends Nuxia\Component\Security\BaseSecurityManager 
{
	Definition de la classe
}
```

La classe `MySecurityManager` possédera la méthode `getUser()` (par héritage) permettant de récupérer l'utilisateur connecté. 

## Surcharge de l'extension de Twig ##

Le bundle remplace l'extension `Symfony\Bridge\Twig\Extension\SecurityExtension` par `Nuxia\NuxiaBundle\Twig\Extension\SecurityExtension`. Cette extension se contente de rendre accessible le `security_manager` définit précédemment dans chaque template grâce à la variable `security`.

Exemple :

```
# Namespace\MyBundle\Security\MySecurityManager.php
class MySecurityManager extends Nuxia\Component\Security\BaseSecurityManager 
{
	public function canWriteDocumentation()
	{
		return $this->securityContext->isGranted('ROLE_ADMIN');
	}
}
```

```
# template.twig
{% if security.canWriteDocumentation() %}
	<a href="{{ url }}">Modifier</a>
{% endif %)
```
