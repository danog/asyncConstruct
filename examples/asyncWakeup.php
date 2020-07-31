<?php

use Amp\Loop;
use danog\AsyncWakeup;

use function Amp\delay;

require 'vendor/autoload.php';

class AsyncWakeupTest
{
    use AsyncWakeup;
    /**
     * Time to wait for wakeup.
     *
     * @var int
     */
    private $delay;
    /**
     * Constructor function.
     *
     * @param integer $delay
     */
    public function __construct(int $delay)
    {
        $this->delay = $delay;
    }
    /**
     * Async wakeup function.
     *
     * Typically, one would re-establish an async AMP database connection here, or do some other async stuff.
     *
     * @return \Generator
     */
    protected function __wakeup_async(): \Generator
    {
        yield delay($this->delay);
        echo "Woke up after {$this->delay} milliseconds!".PHP_EOL;
    }
}

Loop::run(function () {
    $test = new AsyncWakeupTest(1000);
    $test = \serialize($test);
    $test = \unserialize($test);
    echo "Deserialized, waiting asynchronously...".PHP_EOL;
    yield $test->wakeupAsynchronously();
});
