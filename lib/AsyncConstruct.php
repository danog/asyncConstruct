<?php

/**
 * Async constructor trait.
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
 * Async constructor trait.
 *
 * Manages asynchronous construction of async classes
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
trait AsyncConstruct
{
    /**
     * Async init promise.
     *
     * @var ?Promise
     */
    private $asyncInitPromise;
    /**
     * Constructor function.
     *
     * @param mixed ...$params Parameters
     */
    public function __construct(...$params)
    {
        if (\method_exists($this, '__construct_async')) {
            $this->setInitPromise($this->__construct_async(...$params));
        }
    }
    /**
     * Set init promise.
     *
     * @param Promise|\Generator $promise Promise
     *
     * @return void
     */
    protected function setInitPromise($promise): void
    {
        $this->asyncInitPromise = $promise instanceof \Generator ? new Coroutine($promise) : $promise;
        $this->asyncInitPromise->onResolve(
            function (?\Throwable $error, $result): void {
                $this->asyncInitPromise = null;
            }
        );
    }
    /**
     * Blockingly init.
     *
     * @return mixed Resolves with the result of the constructor or null
     */
    public function init()
    {
        if ($this->asyncInitPromise) {
            return wait($this->asyncInitPromise);
        }
        return null;
    }
    /**
     * Asynchronously init.
     *
     * @return Promise<mixed|null> Resolves with the result of the constructor or null
     */
    public function initAsynchronously(): Promise
    {
        if ($this->asyncInitPromise) {
            return $this->asyncInitPromise;
        }
        return new Success();
    }
    /**
     * Check if we've already inited.
     *
     * @return boolean
     */
    public function inited(): bool
    {
        return !$this->asyncInitPromise;
    }
    /**
     * Mark instance as (de)inited forcefully.
     *
     * @param boolean $inited Whether to mark the instance as inited or deinited
     *
     * @return void
     */
    public function forceInit(bool $inited): void
    {
        $this->asyncInitPromise = $inited ? null : new Success(null);
    }
}
