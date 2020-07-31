<?php

namespace danog\Test;

use Amp\PHPUnit\AsyncTestCase;
use danog\AsyncConstruct;
use danog\BlockingConstruct;
use danog\Test\Fixtures\AsyncConstructTest;
use danog\Test\Fixtures\BlockingConstructTest;

use function Amp\delay;

class ConstructTest extends AsyncTestCase
{
    public function testAsyncInitBackground(): \Generator
    {
        $test = new AsyncConstructTest(100);
        $this->assertFalse($test->inited());
        $this->assertFalse($test->inited);
        yield delay(50);
        $this->assertFalse($test->inited());
        $this->assertFalse($test->inited);
        yield delay(51);
        $this->assertTrue($test->inited());
        $this->assertTrue($test->inited);
    }

    public function testAsyncInit(): \Generator
    {
        $test = new AsyncConstructTest(100);
        $this->assertTrue(yield $test->initAsynchronously());
        $this->assertTrue($test->inited());
        $this->assertTrue($test->inited);
        $this->assertNull(yield $test->initAsynchronously());
        $this->assertTrue($test->inited());
        $this->assertTrue($test->inited);
    }

    public function testAsyncInitBlocking()
    {
        $test = new AsyncConstructTest(100);
        $this->assertTrue($test->init());
        $this->assertTrue($test->inited());
        $this->assertTrue($test->inited);
        $this->assertNull($test->init());
        $this->assertTrue($test->inited());
        $this->assertTrue($test->inited);
    }


    public function testBlockingInit()
    {
        $test = new BlockingConstructTest(100);
        $this->assertTrue($test->inited());
        $this->assertTrue($test->inited);
        $this->assertNull($test->init());
    }

    public function testNoInit()
    {
        $test = new class(100) {
            use AsyncConstruct;
        };
        $this->assertTrue($test->inited());
        $test = new class(100) {
            use BlockingConstruct;
        };
        $this->assertTrue($test->inited());
    }

    public function testForceInit()
    {
        $test = new AsyncConstructTest(100);
        $test->forceInit(true);
        $this->assertTrue($test->inited());
        $this->assertNull($test->init());
        $test->forceInit(false);
        $this->assertFalse($test->inited());
        $this->assertNull($test->init());

        $test = new BlockingConstructTest(100);
        $test->forceInit(true);
        $this->assertTrue($test->inited());
        $this->assertNull($test->init());
        $test->forceInit(false);
        $this->assertFalse($test->inited());
        $this->assertNull($test->init());
    }
}
