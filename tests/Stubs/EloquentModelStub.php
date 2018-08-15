<?php

namespace Thinktomorrow\MagicAttributes\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\MagicAttributes\HasMagicAttributes;

class EloquentModelStub extends Model
{
    use HasMagicAttributes;

    public $guarded = [];

    public static function fake()
    {
        return new static([
            'foo' => 'bar',
            'zoo' => ['horror' => 'show'],
            'hell' => new static([
                'raiser' => new static(['box' => 'never touch it']),
            ])
        ]);
    }
}
