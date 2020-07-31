<?php

use Amp\Loop;
use danog\BlockingConstruct;

use function Amp\delay;

require 'vendor/autoload.php';

class BlockingTest
{
    use BlockingConstruct;
    /**
     * Async constructor function.
     *
     * Typically, one would establish an async AMP database connection here, or do some other async stuff.
     *
     * @param integer $delay Constructor delay
     *
     * @return \Generator
     */
    protected function __construct_async(int $delay): \Generator
    {
        yield delay($delay);
        echo "Inited after $delay milliseconds!".PHP_EOL;
    }
}

Loop::run(function () {
    echo "Before starting constructor".PHP_EOL;
    $test = new BlockingTest(1000);
    echo "Started constructor!".PHP_EOL;
});
