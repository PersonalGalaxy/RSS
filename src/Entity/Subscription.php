<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Entity;

use PersonalGalaxy\RSS\{
    Entity\Subscription\Identity,
    Entity\Subscription\User,
    Entity\Subscription\Name,
    Event\SubscriptionWasAdded,
    Event\SubscriptionWasRemoved,
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder,
};
use Innmind\Url\UrlInterface;

final class Subscription implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $user;
    private $name;
    private $location;

    private function __construct(
        Identity $identity,
        User $user,
        Name $name,
        UrlInterface $location
    ) {
        $this->identity = $identity;
        $this->user = $user;
        $this->name = $name;
        $this->location = $location;
    }

    public static function add(
        Identity $identity,
        User $user,
        Name $name,
        UrlInterface $location
    ): self {
        $self = new self($identity, $user, $name, $location);
        $self->record(new SubscriptionWasAdded($identity, $user, $name, $location));

        return $self;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function location(): UrlInterface
    {
        return $this->location;
    }

    public function remove(): void
    {
        $this->record(new SubscriptionWasRemoved($this->identity));
    }
}

