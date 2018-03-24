<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Handler;

use PersonalGalaxy\RSS\{
    Handler\RemoveSubscriptionHandler,
    Command\RemoveSubscription,
    Entity\Subscription,
    Entity\Subscription\Identity,
    Entity\Subscription\User,
    Entity\Subscription\Name,
    Repository\SubscriptionRepository,
    Event\SubscriptionWasRemoved,
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class RemoveSubscriptionHandlerTest extends TestCase
{
    public function testInterface()
    {
        $command = new RemoveSubscription(
            $identity = $this->createMock(Identity::class)
        );
        $handle = new RemoveSubscriptionHandler(
            $repository = $this->createMock(SubscriptionRepository::class)
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn($subscription = Subscription::add(
                $identity,
                $this->createMock(User::class),
                new Name('foo'),
                $this->createMock(UrlInterface::class)
            ));
        $repository
            ->expects($this->once())
            ->method('remove')
            ->with($identity);

        $this->assertNull($handle($command));
        $this->assertInstanceOf(
            SubscriptionWasRemoved::class,
            $subscription->recordedEvents()->last()
        );
    }
}
