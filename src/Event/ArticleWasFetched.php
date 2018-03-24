<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Event;

use PersonalGalaxy\RSS\Entity\Article\{
    Identity,
    Author,
    Description,
    Title,
};
use Innmind\Url\UrlInterface;

final class ArticleWasFetched
{
    private $identity;
    private $author;
    private $link;
    private $description;
    private $title;

    public function __construct(
        Identity $identity,
        Author $author,
        UrlInterface $link,
        Description $description,
        Title $title
    ) {
        $this->identity = $identity;
        $this->author = $author;
        $this->link = $link;
        $this->description = $description;
        $this->title = $title;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function link(): UrlInterface
    {
        return $this->link;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function title(): Title
    {
        return $this->title;
    }
}
