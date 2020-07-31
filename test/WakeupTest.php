<?php

namespace danog\Test;

use Amp\PHPUnit\AsyncTestCase;
use danog\AsyncWakeup;
use danog\BlockingWakeup;
use danog\Test\Fixtures\AsyncWakeupTest as AsyncWakeupTest;
use danog\Test\Fixtures\BlockingWakeupTest;

use function Amp\delay;

class WakeupTest extends AsyncTestCase
{
    public function testAsyncWakeupBackground(): \Generator
    {
        $test = new AsyncWakeupTest(100);
        $this->assertFalse($test->woke);
        $test = \unserialize(\serialize($test));

        $this->assertFalse($test->wokenUp());
        $this->assertFalse($test->woke);
        yield delay(50);
        $this->assertFalse($test->wokenUp());
        $this->assertFalse($test->woke);
        yield delay(51);
        $this->assertTrue($test->wokenUp());
        $this->assertTrue($test->woke);
    }

    public function testAsyncWakeup(): \Generator
    {
        $test = new AsyncWakeupTest(100);
        $this->assertFalse($test->woke);
        $test = \unserialize(\serialize($test));

        $this->assertTrue(yield $test->WakeupAsynchronously());
        $this->assertTrue($test->wokenUp());
        $this->assertTrue($test->woke);
        $this->assertNull(yield $test->WakeupAsynchronously());
        $this->assertTrue($test->wokenUp());
        $this->assertTrue($test->woke);
    }

    public function testAsyncWakeupBlocking()
    {
        $test = new AsyncWakeupTest(100);
        $this->assertFalse($test->woke);
        $test = \unserialize(\serialize($test));

        $this->assertTrue($test->Wakeup());
        $this->assertTrue($test->wokenUp());
        $this->assertTrue($test->woke);
        $this->assertNull($test->Wakeup());
        $this->assertTrue($test->wokenUp());
        $this->assertTrue($test->woke);
    }


    public function testBlockingWakeup()
    {
        $test = new BlockingWakeupTest(100);
        $this->assertFalse($test->woke);
        $test = \unserialize(\serialize($test));

        $this->assertTrue($test->wokenUp());
        $this->assertTrue($test->woke);
        $this->assertNull($test->Wakeup());
    }

    public function testNoWakeup()
    {
        $test = new class(100) {
            use AsyncWakeup;
        };
        $this->assertTrue($test->wokenUp());
        $test = new class(100) {
            use BlockingWakeup;
        };
        $this->assertTrue($test->wokenUp());
    }

    public function testForceWakeup()
    {
        $test = new AsyncWakeupTest(100);
        $test->forceWakeup(true);
        $this->assertTrue($test->wokenUp());
        $this->assertNull($test->Wakeup());
        $test->forceWakeup(false);
        $this->assertFalse($test->wokenUp());
        $this->assertNull($test->Wakeup());

        $test = new BlockingWakeupTest(100);
        $test->forceWakeup(true);
        $this->assertTrue($test->wokenUp());
        $this->assertNull($test->Wakeup());
        $test->forceWakeup(false);
        $this->assertFalse($test->wokenUp());
        $this->assertNull($test->Wakeup());
    }
}
