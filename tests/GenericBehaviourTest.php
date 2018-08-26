<?php

namespace Thinktomorrow\Trader\Tests\Common;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\MagicAttributes\Tests\Stubs\GenericStub;
use Thinktomorrow\Trader\Common\Presenters\GetDynamicValue;

class GenericBehaviourTest extends TestCase
{
    private $stub;

    public function setUp()
    {
        parent::setUp();

        $this->stub = new GenericStub();
    }

    /** @test */
    public function it_can_retrieve_value_as_property()
    {
        $this->assertEquals('bar', $this->stub->attr('foo'));
    }

    /** @test */
    public function unknown_value_gives_null()
    {
        $this->assertNull($this->stub->attr('unknown'));
    }

    /** @test */
    public function unknown_value_can_have_specific_default()
    {
        $this->assertEquals('hello', $this->stub->attr('crazy', 'hello'));
    }

    /** @test */
    public function nested_value_can_be_retrieved_via_dot_syntax()
    {
        $this->assertEquals('show', $this->stub->attr('zoo.horror'));
    }

    /** @test */
    public function not_found_nested_value_returns_default()
    {
        $this->assertEquals('clown', $this->stub->attr('zoo.horrific', 'clown'));
    }

    /** @test */
    public function nested_value_in_object_can_be_retrieved_via_dot_syntax()
    {
        $this->assertEquals('never touch it', $this->stub->attr('hell.raiser.box'));
    }

    /** @test */
    public function camelcased_also_works_for_nested_value_retrieval()
    {
        $this->assertEquals('show', $this->stub->attr('zooHorror'));
        $this->assertEquals('never touch it', $this->stub->attr('hellRaiserBox'));
    }

    /** @test */
    public function value_can_be_manipulated_at_runtime()
    {
        $this->assertSame('BAR', $this->stub->attr('foo', null, function ($value) {
            return strtoupper($value);
        }));
    }

    /** @test */
    public function retrieving_value_from_array_plucks_all_values()
    {
        $this->assertInternalType('array', $this->stub->attr('models.box'));
        $this->assertEquals(['one', 'two', 'three'], $this->stub->attr('models.box'));

        // unknown key will return default
        $this->assertEquals('foobar', $this->stub->attr('models.unknown', 'foobar'));
    }
}
