<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Event;

use PersonalGalaxy\RSS\{
    Event\SubscriptionWasRemoved,
    Entity\Subscription\Identity,
};
use PHPUnit\Framework\TestCase;

class SubscriptionWasRemovedTest extends TestCase
{
    public function testInterface()
    {
        $event = new SubscriptionWasRemoved(
            $identity = $this->createMock(Identity::class)
        );

        $this->assertSame($identity, $event->identity());
    }
}
