<?php

/*
 * This file is part of the BroadwayScenarioHelper package.
 *
 * (c) Kamil Kokot <kamil@kokot.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Pamil\BroadwayScenarioHelper\Scenario\Write;

use Pamil\BroadwayScenarioHelper\Scenario\Scenario;

interface WriteScenario extends Scenario
{
    /**
     * Can be run more than once.
     *
     * @param callable|mixed $event Event that should have happened after an action.
     *                              Or a callable receiving aggregate root id and returning that event.
     *
     * @return self
     */
    public function then($event): self;

    /**
     * Can be run more than once.
     *
     * @param callable|mixed $event Event that should have NOT happened after an action.
     *                              Or a callable receiving aggregate root id and returning that event.
     *
     * @return self
     */
    public function thenNot($event): self;
}
