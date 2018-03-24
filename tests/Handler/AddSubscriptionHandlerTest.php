<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Handler;

use PersonalGalaxy\RSS\{
    Handler\AddSubscriptionHandler,
    Command\AddSubscription,
    Entity\Subscription\Identity,
    Entity\Subscription\User,
    Entity\Subscription\Name,
    Repository\SubscriptionRepository,
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class AddSubscriptionHandlerTest extends TestCase
{
    public function testInterface()
    {
        $command = new AddSubscription(
            $this->createMock(Identity::class),
            $this->createMock(User::class),
            new Name('foo'),
            $this->createMock(UrlInterface::class)
        );
        $handle = new AddSubscriptionHandler(
            $repository = $this->createMock(SubscriptionRepository::class)
        );
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(static function($subscription) use ($command): bool {
                return $subscription->identity() === $command->identity() &&
                    $subscription->user() === $command->user() &&
                    $subscription->name() === $command->name() &&
                    $subscription->location() === $command->location();
            }));

        $this->assertNull($handle($command));
    }
}
