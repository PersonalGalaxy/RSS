<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Event;

use PersonalGalaxy\RSS\Entity\Subscription\{
    Identity,
    User,
    Name,
};
use Innmind\Url\UrlInterface;

final class SubscriptionWasAdded
{
    private $identity;
    private $user;
    private $name;
    private $location;

    public function __construct(
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
}
