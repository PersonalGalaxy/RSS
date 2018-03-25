<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Handler;

use PersonalGalaxy\RSS\{
    Command\RemoveSubscription,
    Repository\SubscriptionRepository,
};

final class RemoveSubscriptionHandler
{
    private $repository;

    public function __construct(SubscriptionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RemoveSubscription $wished): void
    {
        $subscription = $this->repository->get($wished->identity());
        $subscription->remove();
        $this->repository->remove($wished->identity());
    }
}
