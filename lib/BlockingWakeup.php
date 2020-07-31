<?php

/**
 * Blocking wakeup trait.
 *
 * @author    Daniil Gentili <daniil@daniil.it>
 * @copyright 2016-2020 Daniil Gentili <daniil@daniil.it>
 * @license   https://opensource.org/licenses/MIT MIT
 */

namespace danog;

/**
 * Blocking wakeup trait.
 *
 * Manages blocking wakeup of async classes
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
trait BlockingWakeup
{
    use AsyncWakeup;

    /**
     * Wakeup function.
     */
    public function __wakeup(): void
    {
        if (\method_exists($this, '__wakeup_async')) {
            $this->setWakeupPromise($this->__wakeup_async());
        }
        $this->wakeup();
    }
}
