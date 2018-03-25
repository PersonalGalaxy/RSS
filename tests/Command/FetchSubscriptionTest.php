<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Command;

use PersonalGalaxy\RSS\{
    Command\FetchSubscription,
    Entity\Subscription\Identity,
};
use PHPUnit\Framework\TestCase;

class FetchSubscriptionTest extends TestCase
{
    public function testInterface()
    {
        $command = new FetchSubscription(
            $identity = $this->createMock(Identity::class)
        );

        $this->assertSame($identity, $command->identity());
    }
}
