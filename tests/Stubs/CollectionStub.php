<?php

namespace Thinktomorrow\MagicAttributes\Tests\Stubs;

use Illuminate\Support\Collection;
use Thinktomorrow\MagicAttributes\HasMagicAttributes;

class CollectionStub
{
    use HasMagicAttributes;

    public $collection;

    public function __construct()
    {
        $this->collection = new Collection([
            'foo' => 'bar',
            'zoo' => ['horror' => 'show'],
            'hell' => new Collection([
                'raiser' => new Collection(['box' => 'never touch it']),
            ]),
            'models' => [
                new Collection(['box' => 'one']),
                new Collection(['box' => 'two']),
                new Collection([]),
            ]
        ]);
    }
}
