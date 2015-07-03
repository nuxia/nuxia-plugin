<?php

namespace Nuxia\Component\Doctrine\Manager;

interface ControllerManagerInterface
{
    /**
     * @param array  $criteria
     * @param string $type
     * @param array  $parameters
     *
     * @return mixed
     */
    public function getPaginatorTarget(array $criteria, $type = 'list', array $parameters = array());

    /**
     * @param array  $criteria
     * @param string $type
     * @param array  $parameters
     *
     * @return mixed
     */
    public function getControllerObject(array $criteria, $type = 'default', array $parameters = array());
}
