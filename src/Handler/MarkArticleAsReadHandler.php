<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Handler;

use PersonalGalaxy\RSS\{
    Command\MarkArticleAsRead,
    Repository\ArticleRepository,
};

final class MarkArticleAsReadHandler
{
    private $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(MarkArticleAsRead $wished): void
    {
        if (!$this->repository->has($wished->link())) {
            return;
        }

        $this
            ->repository
            ->get($wished->link())
            ->markAsRead();
    }
}
