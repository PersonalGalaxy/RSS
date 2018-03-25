<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Command;

use Innmind\Url\UrlInterface;

final class MarkArticleAsRead
{
    private $link;

    public function __construct(UrlInterface $link)
    {
        $this->link = $link;
    }

    public function link(): UrlInterface
    {
        return $this->link;
    }
}
