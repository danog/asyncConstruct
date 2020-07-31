<?php

namespace danog\Test\Fixtures;

use danog\AsyncWakeup as AWakeup;

use function Amp\delay;

class AsyncWakeupTest
{
    use AWakeup;
    public $woke = false;
    private $delay;
    public function __construct(int $delay)
    {
        $this->delay = $delay;
    }
    protected function __wakeup_async(): \Generator
    {
        yield delay($this->delay);
        $this->woke = true;
        return true;
    }
}
