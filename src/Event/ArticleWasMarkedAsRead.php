<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Event;

use PersonalGalaxy\RSS\Entity\Article\Identity;

final class ArticleWasMarkedAsRead
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
