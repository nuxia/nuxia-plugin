<?php

namespace Nuxia\Component\Form\Type;

use Nuxia\Component\Parser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

//@TODO ajouter translation domain null pour ne pas avoir de traduction pour les labels et help (form_layout)
class ArrayType extends AbstractType
{
    /**
     * @var string
     */
    const CONSTRAINTS_LOCATION = 'Symfony\Component\Validator\Constraints';

    /**
     * @var array
     */
    protected $parsedConstraints;

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('fields');
        $resolver->setDefaults(array('constraint_messages' => array(), 'root_view' => array()));
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['fields'] as $field => $parameters) {
            $fieldOptions = $this->getArrayFromKey($parameters, 'options');
            if (isset($fieldOptions['choices_prefix'])) {
                $fieldOptions['choices'] = Parser::prefixArray(
                    $fieldOptions['choices'],
                    $fieldOptions['choices_prefix']
                );
                unset($fieldOptions['choices_prefix']);
            }
            $this->parsedConstraints[$field] = $this->parseConstraintsArray(
                $this->getArrayFromKey($parameters, 'constraints')
            );

            $this->addConstraints($fieldOptions, $this->parsedConstraints[$field], $options['constraint_messages']);
            $fieldOptions['required'] = array_key_exists('NotBlank', $this->parsedConstraints[$field]);
            $builder->add($field, $parameters['type'], $fieldOptions);
        }
    }

    /**
     * @param array $fieldOptions
     * @param array $constraints
     * @param array $defaultMessages
     */
    private function addConstraints(array &$fieldOptions, array $constraints, array $defaultMessages)
    {
        $fieldOptions['constraints'] = array();
        $defaultMessages = $this->parseConstraintsArray($defaultMessages);
        foreach ($constraints as $constraint => $options) {
            $class = (strpos($constraint, '\\') === false ? ArrayType::CONSTRAINTS_LOCATION . '\\' : '') . $constraint;
            $options = array_merge($this->getArrayFromKey($defaultMessages, $constraint), $options);
            $fieldOptions['constraints'][] = new $class($options);
        }
    }

    /**
     * @param array $viewOptions
     * @param array $constraints
     */
    private function addViewOptionsFromConstraint(array &$viewOptions, $constraints)
    {
        if (array_key_exists('Range', $constraints)) {
            $attr = isset($viewOptions['attr']) ? $viewOptions['attr'] : array();
            $viewOptions['attr'] = array_merge(
                $attr,
                Parser::filterArrayByKeys($constraints['Range'], array('min', 'max'))
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($options['root_view'] as $var => $value) {
            $view->vars[$var] = $value;
        }
        foreach ($options['fields'] as $field => $parameters) {
            $viewOptions = $this->getArrayFromKey($parameters, 'view');
            $this->addViewOptionsFromConstraint($viewOptions, $this->parsedConstraints[$field]);
            foreach ($viewOptions as $key => $value) {
                $view->children[$field]->vars[$key] = $value;
            }
        }
    }

    /**
     * @param array  $array
     * @param string $key
     *
     * @return array
     */
    private function getArrayFromKey(array $array, $key)
    {
        return isset($array[$key]) ? $array[$key] : array();
    }

    /**
     * @param array $constraints
     *
     * @return array
     */
    private function parseConstraintsArray(array $constraints)
    {
        foreach ($constraints as $constraint => $options) {
            if (!is_string($constraint)) {
                $constraints[$options] = array();
                unset($constraints[$constraint]);
            } elseif (!is_array($options)) {
                $constraints[$constraint] = array('message' => $options);
            }
        }
        return $constraints;
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return FormType::class;
    }
}
