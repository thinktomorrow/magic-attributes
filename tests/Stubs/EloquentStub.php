<?php

namespace Thinktomorrow\MagicAttributes\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\MagicAttributes\HasMagicAttributes;

class EloquentStub
{
    use HasMagicAttributes;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
