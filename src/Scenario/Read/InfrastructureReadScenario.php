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

namespace Pamil\BroadwayScenarioHelper\Scenario\Read;

use Broadway\Domain\DomainEventStream;
use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Broadway\EventHandling\EventBus;
use Pamil\BroadwayScenarioHelper\Scenario\Scenario;

final class InfrastructureReadScenario implements ReadScenario
{
    /** @var EventBus */
    private $eventBus;

    /** @var string|null */
    private $aggregateId;

    /** @var int */
    private $currentPlayhead = -1;

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /** {@inheritdoc} */
    public function withAggregateId(string $aggregateId): Scenario
    {
        if (null !== $this->aggregateId) {
            throw new \DomainException('Aggregate ID is already specified!');
        }

        $this->aggregateId = $aggregateId;

        return $this;
    }

    /** {@inheritdoc} */
    public function given($event): Scenario
    {
        if (is_callable($event)) {
            $event = $event($this->aggregateId);
        }

        $this->eventBus->publish(new DomainEventStream([
            DomainMessage::recordNow($this->aggregateId, ++$this->currentPlayhead, new Metadata([]), $event)
        ]));

        return $this;
    }

    /** {@inheritdoc} */
    public function when(callable $action): Scenario
    {
        $action($this->aggregateId);

        return $this;
    }

    /** {@inheritdoc} */
    public function then(callable $assertion): ReadScenario
    {
        $assertion($this->aggregateId);

        return $this;
    }
}
