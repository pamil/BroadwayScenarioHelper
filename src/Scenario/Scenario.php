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

namespace Pamil\BroadwayScenarioHelper\Scenario;

interface Scenario
{
    /**
     * Used to tell to which aggregate is the source of events specified in `given` method.
     * Passed as the first argument to callables received by `then*` methods.
     *
     * Can be run ONLY once.
     *
     * @param string $aggregateId
     *
     * @return self
     */
    public function withAggregateId(string $aggregateId): self;

    /**
     * Can be run more than once.
     *
     * @param callable|mixed $event Event that has happened in the past.
     *                              Or a callable receiving aggregate root id and returning that event.
     *
     * @return self
     */
    public function given($event): self;

    /**
     * Can be run ONLY once.
     *
     * @param callable $action
     *
     * @return self
     */
    public function when(callable $action): self;
}
