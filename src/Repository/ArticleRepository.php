<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Repository;

use PersonalGalaxy\RSS\Entity\Article;
use Innmind\Url\UrlInterface;
use Innmind\Immutable\SetInterface;
use Innmind\Specification\SpecificationInterface;

interface ArticleRepository
{
    /**
     * @throws ArticleNotFound
     */
    public function get(UrlInterface $link): Article;
    public function add(Article $article): self;
    public function remove(UrlInterface $link): self;
    public function has(UrlInterface $link): bool;
    public function count(): int;

    /**
     * @return SetInterface<Article>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Article>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
