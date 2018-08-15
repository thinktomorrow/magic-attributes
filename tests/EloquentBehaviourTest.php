<?php

namespace Thinktomorrow\Trader\Tests\Common;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\MagicAttributes\Tests\Stubs\EloquentModel;
use Thinktomorrow\MagicAttributes\Tests\Stubs\EloquentStub;
use Thinktomorrow\Trader\Common\Presenters\GetDynamicValue;

class EloquentBehaviourTest extends TestCase
{
    private $stub;

    public function setUp()
    {
        parent::setUp();

        $this->stub = new EloquentStub(EloquentModel::fake());
    }

    /** @test */
    public function it_can_retrieve_value_as_property()
    {
        $this->assertEquals('bar', $this->stub->attr('model.foo'));
    }

    /** @test */
    public function unknown_value_gives_null()
    {
        $this->assertNull($this->stub->attr('model.unknown'));
    }

    /** @test */
    public function unknown_value_can_have_specific_default()
    {
        $this->assertEquals('hello', $this->stub->attr('model.crazy', 'hello'));
    }

    /** @test */
    public function nested_value_can_be_retrieved_via_dot_syntax()
    {
        $this->assertEquals('show', $this->stub->attr('model.zoo.horror'));
    }

    /** @test */
    public function not_found_nested_value_returns_default()
    {
        $this->assertEquals('clown', $this->stub->attr('model.zoo.horrific', 'clown'));
    }

    /** @test */
    public function nested_value_in_object_can_be_retrieved_via_dot_syntax()
    {
        $this->assertEquals('never touch it', $this->stub->attr('model.hell.raiser.box'));
    }

    /** @test */
    public function camelcased_also_works_for_nested_value_retrieval()
    {
        $this->assertEquals('show', $this->stub->attr('modelZooHorror'));

        // Combination of dot syntax and camelcased
        $this->assertEquals('never touch it', $this->stub->attr('model.hellRaiserBox'));
    }

    /** @test */
    public function value_can_be_manipulated_at_runtime()
    {
        $this->assertSame('BAR', $this->stub->attr('model.foo', null, function ($value) {
            return strtoupper($value);
        }));
    }
}
