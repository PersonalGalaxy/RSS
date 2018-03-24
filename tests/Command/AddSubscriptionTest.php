<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Command;

use PersonalGalaxy\RSS\{
    Command\AddSubscription,
    Entity\Subscription\Identity,
    Entity\Subscription\User,
    Entity\Subscription\Name,
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class AddSubscriptionTest extends TestCase
{
    public function testInterface()
    {
        $command = new AddSubscription(
            $identity = $this->createMock(Identity::class),
            $user = $this->createMock(User::class),
            $name = new Name('foo'),
            $location = $this->createMock(UrlInterface::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($user, $command->user());
        $this->assertSame($name, $command->name());
        $this->assertSame($location, $command->location());
    }
}
