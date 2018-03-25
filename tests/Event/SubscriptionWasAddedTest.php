<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Event;

use PersonalGalaxy\RSS\{
    Event\SubscriptionWasAdded,
    Entity\Subscription\Identity,
    Entity\Subscription\User,
    Entity\Subscription\Name,
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class SubscriptionWasAddedTest extends TestCase
{
    public function testInterface()
    {
        $event = new SubscriptionWasAdded(
            $identity = $this->createMock(Identity::class),
            $user = $this->createMock(User::class),
            $name = new Name('foo'),
            $location = $this->createMock(UrlInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($user, $event->user());
        $this->assertSame($name, $event->name());
        $this->assertSame($location, $event->location());
    }
}
