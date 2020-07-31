<?php

namespace danog\Test\Fixtures;

use danog\BlockingConstruct;

use function Amp\delay;

class BlockingConstructTest
{
    use BlockingConstruct;
    public $inited = false;
    protected function __construct_async(int $delay): \Generator
    {
        yield delay($delay);
        $this->inited = true;
        return true;
    }
}
