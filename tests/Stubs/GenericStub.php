<?php

namespace Thinktomorrow\MagicAttributes\Tests\Stubs;

use Thinktomorrow\MagicAttributes\HasMagicAttributes;

class GenericStub
{
    use HasMagicAttributes;

    public function __construct()
    {
        $this->foo = 'bar';
        $this->zoo = ['horror' => 'show'];
        $this->hell = (object) ['raiser' => (object) ['box' => 'never touch it']];
        $this->crazyShow = 'mustsee';

        $this->models = [
            ['box' => 'one'],
            ['box' => 'two'],
            ['box' => 'three'],
        ];

        $this->recursiveModels = [
            ['box' => ['inner' => 'one']],
            ['box' => ['inner' => 'two']],
            ['box' => 'three'],
        ];
    }
}
