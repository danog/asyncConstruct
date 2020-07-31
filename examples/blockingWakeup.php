<?php

use Amp\Loop;
use danog\BlockingWakeup;

use function Amp\delay;

require 'vendor/autoload.php';

class BlockingWakeupTest
{
    use BlockingWakeup;
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
    $test = new BlockingWakeupTest(1000);
    $test = \serialize($test);
    echo "Deserializing...".PHP_EOL;
    $test = \unserialize($test);
});
