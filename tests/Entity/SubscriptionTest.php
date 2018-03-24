<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Entity;

use PersonalGalaxy\RSS\{
    Entity\Subscription,
    Entity\Subscription\Identity,
    Entity\Subscription\User,
    Entity\Subscription\Name,
    Event\SubscriptionWasAdded,
    Event\SubscriptionWasRemoved,
};
use Innmind\Url\UrlInterface;
use Innmind\EventBus\ContainsRecordedEventsInterface;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    public function testAdd()
    {
        $subscription = Subscription::add(
            $identity = $this->createMock(Identity::class),
            $user = $this->createMock(user::class),
            $name = new Name('foo'),
            $location = $this->createMock(UrlInterface::class)
        );

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $subscription);
        $this->assertSame($identity, $subscription->identity());
        $this->assertSame($user, $subscription->user());
        $this->assertSame($name, $subscription->name());
        $this->assertSame($location, $subscription->location());
        $this->assertCount(1, $subscription->recordedEvents());
        $event = $subscription->recordedEvents()->current();
        $this->assertInstanceOf(SubscriptionWasAdded::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame($user, $event->user());
        $this->assertSame($name, $event->name());
        $this->assertSame($location, $event->location());
    }

    public function testRemove()
    {
        $subscription = Subscription::add(
            $identity = $this->createMock(Identity::class),
            $this->createMock(User::class),
            new Name('foo'),
            $this->createMock(UrlInterface::class)
        );

        $this->assertNull($subscription->remove());
        $this->assertCount(2, $subscription->recordedEvents());
        $event = $subscription->recordedEvents()->last();
        $this->assertInstanceOf(SubscriptionWasRemoved::class, $event);
        $this->assertSame($identity, $event->identity());
    }
}
