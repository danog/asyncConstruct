<?php

/**
 * Blocking constructor trait.
 *
 * @author    Daniil Gentili <daniil@daniil.it>
 * @copyright 2016-2020 Daniil Gentili <daniil@daniil.it>
 * @license   https://opensource.org/licenses/MIT MIT
 */

namespace danog;

/**
 * Blocking constructor trait.
 *
 * Manages blocking construction of async classes
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
trait BlockingConstruct
{
    use AsyncConstruct;

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
        $this->init();
    }
}
