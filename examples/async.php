<?php

use Amp\Loop;
use danog\AsyncConstruct;

use function Amp\delay;

require 'vendor/autoload.php';

class AsyncTest
{
    use AsyncConstruct;
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
    $test = new AsyncTest(1000);
    echo "Started constructor, waiting...".PHP_EOL;
    yield $test->initAsynchronously();
});
