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

use Broadway\Domain\DomainEventStream;
use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Broadway\EventSourcing\AggregateFactory\AggregateFactory;
use Broadway\EventSourcing\AggregateFactory\ReflectionAggregateFactory;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use PHPUnit\Framework\Assert;
use Pamil\BroadwayScenarioHelper\Scenario\Scenario;

final class DomainWriteScenario extends AbstractWriteScenario
{
    /** @var string */
    private $aggregateRootClass;

    /** @var AggregateFactory */
    private $aggregateFactory;

    /** @var EventSourcedAggregateRoot|null */
    private $aggregateRoot;

    /** @var array */
    private $producedEvents = [];

    public function __construct(string $aggregateRootClass, AggregateFactory $aggregateFactory = null)
    {
        $this->aggregateRootClass = $aggregateRootClass;
        $this->aggregateFactory = $aggregateFactory ?: new ReflectionAggregateFactory();
    }

    /** {@inheritdoc} */
    public function given($event): Scenario
    {
        if (is_callable($event)) {
            $event = $event($this->aggregateId);
        }

        if (null === $this->aggregateRoot) {
            $this->aggregateRoot = $this->aggregateFactory->create($this->aggregateRootClass, new DomainEventStream([]));

            Assert::assertInstanceOf(EventSourcedAggregateRoot::class, $this->aggregateRoot);
        }

        $this->aggregateRoot->initializeState(new DomainEventStream([
            DomainMessage::recordNow($this->aggregateId, $this->aggregateRoot->getPlayhead() + 1, new Metadata(), $event)
        ]));

        return $this;
    }

    public function when(callable $callable): Scenario
    {
        if (null === $this->aggregateRoot) {
            $this->aggregateRoot = $callable($this->aggregateId);

            Assert::assertInstanceOf(EventSourcedAggregateRoot::class, $this->aggregateRoot);
        } else {
            $callable($this->aggregateRoot);
        }

        $this->producedEvents = array_map(
            function (DomainMessage $message) {
                return $message->getPayload();
            },
            iterator_to_array($this->aggregateRoot->getUncommittedEvents())
        );

        return $this;
    }

    /** {@inheritdoc} */
    protected function getProducedEvents(): iterable
    {
        return $this->producedEvents;
    }
}
