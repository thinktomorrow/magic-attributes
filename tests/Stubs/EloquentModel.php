<?php

namespace Thinktomorrow\MagicAttributes\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;

class EloquentModel extends Model
{
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
