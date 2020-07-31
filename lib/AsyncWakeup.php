<?php

/**
 * Async wakeup trait.
 *
 * @author    Daniil Gentili <daniil@daniil.it>
 * @copyright 2016-2020 Daniil Gentili <daniil@daniil.it>
 * @license   https://opensource.org/licenses/MIT MIT
 */

namespace danog;

use Amp\Coroutine;
use Amp\Promise;
use Amp\Success;

use function Amp\Promise\wait;

/**
 * Async wakeup trait.
 *
 * Manages asynchronous wakeup of async classes
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
trait AsyncWakeup
{
    /**
     * Async wakeup promise.
     *
     * @var ?Promise
     */
    private $asyncWakeupPromise;
    /**
     * Wakeup function.
     */
    public function __wakeup(): void
    {
        if (\method_exists($this, '__wakeup_async')) {
            $this->setWakeupPromise($this->__wakeup_async());
        }
    }
    /**
     * Set wakeup promise.
     *
     * @param Promise|\Generator $promise Promise
     *
     * @return void
     */
    protected function setWakeupPromise($promise): void
    {
        $this->asyncWakeupPromise = $promise instanceof \Generator ? new Coroutine($promise) : $promise;
        $this->asyncWakeupPromise->onResolve(
            function (?\Throwable $error, $result): void {
                $this->asyncWakeupPromise = null;
            }
        );
    }
    /**
     * Blockingly wakeup.
     *
     * @return mixed|null Resolves with the result of the wakeup function or null
     */
    public function wakeup()
    {
        if ($this->asyncWakeupPromise) {
            return wait($this->asyncWakeupPromise);
        }
        return null;
    }
    /**
     * Asynchronously wakeup.
     *
     * @return Promise<mixed|null> Resolves with the result of the wakeup function or null
     */
    public function wakeupAsynchronously(): Promise
    {
        if ($this->asyncWakeupPromise) {
            return $this->asyncWakeupPromise;
        }
        return new Success();
    }
    /**
     * Check if we've already woken up.
     *
     * @return boolean
     */
    public function wokenUp(): bool
    {
        return !$this->asyncWakeupPromise;
    }
    /**
     * Mark instance as (de)woken up forcefully.
     *
     * @param boolean $woke Whether to mark the instance as woken up or not
     *
     * @return void
     */
    public function forceWakeup(bool $woke): void
    {
        $this->asyncWakeupPromise = $woke ? null : new Success(null);
    }
}
