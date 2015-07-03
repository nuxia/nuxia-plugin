<?php

namespace Nuxia\Component\Config\Tests;

use Nuxia\Component\Config\ParameterBag;

/**
 * Unit tests for {@see \Nuxia\Component\Config\ParameterBag}
 * 
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class ParameterBagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Nuxia\Component\Config\ParameterBag::setIfNotSet
     */
    public function testSetIfNotSet()
    {
        $parameterBag = new ParameterBag(array('foo' => 'foo', 'bar' => 'bar'));

        $parameterBag->setIfNotSet('foo', 'newValue');
        $this->assertEquals('foo', $parameterBag->get('foo'));
        $parameterBag->setIfNotSet('newKey', 'value');
        $this->assertEquals('value', $parameterBag->get('newKey'));
    }

    /**
     * @covers \Nuxia\Component\Config\ParameterBag::filterByKeys
     */
    public function testFilterByKeys()
    {
        $parameters = array('foo' => 'foo', 'foo2' => 'foo2', 'foo3' => 'foo3');
        $parameterBag = new ParameterBag($parameters);

        $this->assertEquals(array('foo' => 'foo', 'foo2' => 'foo2'), $parameterBag->filterByKeys(array('foo', 'foo2')));
        $this->assertEquals($parameters, $parameterBag->all(), 'filterByKeys must not change the initials parameters bag');
        $this->assertEquals(array('foo' => 'foo', 'foo2' => 'foo2'), $parameterBag->filterByKeys(array('foo', 'foo2', 'bar')));
    }
}

