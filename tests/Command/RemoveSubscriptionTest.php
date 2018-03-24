<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Command;

use PersonalGalaxy\RSS\{
    Command\RemoveSubscription,
    Entity\Subscription\Identity,
};
use PHPUnit\Framework\TestCase;

class RemoveSubscriptionTest extends TestCase
{
    public function testInterface()
    {
        $command = new RemoveSubscription(
            $identity = $this->createMock(Identity::class)
        );

        $this->assertSame($identity, $command->identity());
    }
}
