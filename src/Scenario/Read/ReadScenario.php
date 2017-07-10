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

use Pamil\BroadwayScenarioHelper\Scenario\Scenario;

interface ReadScenario extends Scenario
{
    /**
     * Can be run more than once.
     *
     * @param callable $assertion
     *
     * @return self
     */
    public function then(callable $assertion): self;
}
