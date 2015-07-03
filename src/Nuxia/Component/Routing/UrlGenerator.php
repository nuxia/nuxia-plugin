<?php

namespace Nuxia\Component\Routing;

use Nuxia\Component\Parser;
use Symfony\Component\Routing\Generator\UrlGenerator as SymfonyUrlGenerator;

class UrlGenerator extends SymfonyUrlGenerator
{
    /**
     * Surcharge de l'url generator pour générer une route en fonction d'un objet (paramètre _object)
     * Les paramètres passés directement à la route sont prioritaires par rapport aux champs de _object
     * {@inheritdoc}
     */
    protected function doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens, array $requiredSchemes = array())
    {
        if (isset($defaults['_external']) && '' !== $this->context->getBaseUrl()) {
            $oldBaseUrl = $this->context->getBaseUrl();
            $this->context->setBaseUrl('');
        }
        if (isset($parameters['_object'])) {
            $object = $parameters['_object'];
            $parameters = array_merge($this->getParametersFromObject($variables, $object), $parameters);
            unset($parameters['_object']);
        }

        $url = parent::doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens, $requiredSchemes);

        if (isset($oldBaseUrl)) {
            $this->context->setBaseUrl($oldBaseUrl);
        }

        return $url;
    }

    /**
     * @TODO utilisation d'une interface ? RoutableInterface ou un truc comme ça
     * @TODO utilisation de PropertyPath
     * @TODO Gestion de la datetime et slugify (voir invenio) (setUrlizeStrategy ?)
     * Renvoi les paramètres de la route en fonction de l'objet $objet. (Utilisation de toArray ?)
     * Si le paramètre n'existe pas sur l'objet il est ignoré
     * @param array $keys
     * @param object $object
     * @return array
     */
    protected function getParametersFromObject(array $keys, $object)
    {
        $parameters = array();
        foreach ($keys as $key) {
            $set = false;
            if (strpos($key, '_') !== false) {
                $value = $object;
                foreach (explode('_', $key) as $segment) {
                    $method = 'get' . Parser::camelize($segment);
                    if (method_exists($value, $method)) {
                        $value = $value->$method();
                        $set = true;
                    } else {
                        $set = false;
                        break;
                    }
                }
            } else {
                $method = 'get' . Parser::camelize($key);
                if (method_exists($object, $method)) {
                    $value = $object->$method();
                    $set = true;
                }
            }
            if ($set) {
                $parameters[$key] = $value;
            }
        }
        return $parameters;
    }
}
