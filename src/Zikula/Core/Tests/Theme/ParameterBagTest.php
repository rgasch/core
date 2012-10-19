<?php

namespace Zikula\Core\Tests\Theme;

use Zikula\Core\Theme\ParameterBag;

/**
 * Tests ParameterBag
 */
class ParameterBagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $array;

    /**
     * @var ParameterBag
     */
    private $bag;

    protected function setUp()
    {
        $this->array = array(
            'hello' => 'world',
            'always' => 'be happy',
            'user_login' => 'drak',
            'csrf_token' => array(
                'a' => '1234',
                'b' => '4321',
            ),
            'category' => array(
                'fishing' => array(
                    'first' => 'cod',
                    'second' => 'sole')
                ),
        );
        $this->bag = new ParameterBag($this->array);
    }

    protected function tearDown()
    {
        $this->bag = null;
        $this->array = array();
    }

    /**
     * @dataProvider parametersProvider
     */
    public function testHas($key, $value, $exists)
    {
        $this->assertEquals($exists, $this->bag->has($key));
    }

    /**
     * @dataProvider parametersProvider
     */
    public function testGet($key, $value, $expected)
    {
        $this->assertEquals($value, $this->bag->get($key));
    }

    public function testGetDefaults()
    {
        $this->assertEmpty($this->bag->get('user2_login'));
        $this->assertEquals('default', $this->bag->get('user2_login', 'default'));
    }

    /**
     * @dataProvider parametersProvider
     */
    public function testSet($key, $value, $expected)
    {
        $this->bag->set($key, $value);
        $this->assertEquals($value, $this->bag->get($key));
    }

    public function testAll()
    {
        $this->assertEquals($this->array, $this->bag->all());

        $this->bag->set('hello', 'fabien');
        $array = $this->array;
        $array['hello'] = 'fabien';
        $this->assertEquals($array, $this->bag->all());
    }

    public function testReplace()
    {
        $array = array();
        $array['name'] = 'jack';
        $array['foo_bar'] = 'beep';
        $this->bag->replace($array);
        $this->assertEquals($array, $this->bag->all());
        $this->assertEmpty($this->bag->get('hello'));
        $this->assertEmpty($this->bag->get('always'));
        $this->assertEmpty($this->bag->get('user_login'));
    }

    public function testRemove()
    {
        $this->assertEquals('world', $this->bag->get('hello'));
        $this->bag->remove('hello');
        $this->assertEmpty($this->bag->get('hello'));

        $this->assertEquals('be happy', $this->bag->get('always'));
        $this->bag->remove('always');
        $this->assertEmpty($this->bag->get('always'));

        $this->assertEquals('drak', $this->bag->get('user_login'));
        $this->bag->remove('user_login');
        $this->assertEmpty($this->bag->get('user_login'));
    }

    public function testClear()
    {
        $this->bag->clear();
        $this->assertEquals(array(), $this->bag->all());
    }

    public function parametersProvider()
    {
        return array(
            array('hello', 'world', true),
            array('always', 'be happy', true),
            array('user_login', 'drak', true),
            array('csrf_token', array('a' => '1234', 'b' => '4321'), true),
            array('csrf_token.a', '1234', true),
            array('csrf_token.b', '4321', true),
            array('category', array('fishing' => array('first' => 'cod', 'second' => 'sole')), true),
            array('category.fishing', array('first' => 'cod', 'second' => 'sole'), true),
            array('category.fishing.first', 'cod', true),
            array('category.fishing.second', 'sole', true),
            array('user2.login', null, false),
            array('never', null, false),
            array('bye', null, false),
            array('bye.for.now', null, false),
        );
    }
}
