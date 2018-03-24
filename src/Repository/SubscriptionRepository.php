<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Repository;

use PersonalGalaxy\RSS\Entity\{
    Subscription,
    Subscription\Identity,
};
use Innmind\Immutable\SetInterface;
use Innmind\Specification\SpecificationInterface;

interface SubscriptionRepository
{
    /**
     * @throws SubscriptionNotFound
     */
    public function get(Identity $identity): Subscription;
    public function add(Subscription $subscription): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Subscription>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Subscription>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
