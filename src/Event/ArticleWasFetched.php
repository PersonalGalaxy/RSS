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
use Innmind\TimeContinuum\PointInTimeInterface;

final class ArticleWasFetched
{
    private $identity;
    private $author;
    private $link;
    private $description;
    private $title;
    private $publicationDate;

    public function __construct(
        Identity $identity,
        Author $author,
        UrlInterface $link,
        Description $description,
        Title $title,
        PointInTimeInterface $publicationDate
    ) {
        $this->identity = $identity;
        $this->author = $author;
        $this->link = $link;
        $this->description = $description;
        $this->title = $title;
        $this->publicationDate = $publicationDate;
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

    public function publicationDate(): PointInTimeInterface
    {
        return $this->publicationDate;
    }
}
