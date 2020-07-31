<?php

namespace danog\Test\Fixtures;

use danog\AsyncConstruct;

use function Amp\delay;

class AsyncConstructTest
{
    use AsyncConstruct;
    public $inited = false;
    protected function __construct_async(int $delay): \Generator
    {
        yield delay($delay);
        $this->inited = true;
        return true;
    }
}
