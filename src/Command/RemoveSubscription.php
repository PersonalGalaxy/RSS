<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Command;

use PersonalGalaxy\RSS\Entity\Subscription\Identity;

final class RemoveSubscription
{
    private $identity;

    public function __construct(Identity $identity)
    {
        $this->identity = $identity;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }
}
