<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Handler;

use PersonalGalaxy\RSS\{
    Command\AddSubscription,
    Entity\Subscription,
    Repository\SubscriptionRepository,
};

final class AddSubscriptionHandler
{
    private $repository;

    public function __construct(SubscriptionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(AddSubscription $wished): void
    {
        $this->repository->add(Subscription::add(
            $wished->identity(),
            $wished->user(),
            $wished->name(),
            $wished->location()
        ));
    }
}
