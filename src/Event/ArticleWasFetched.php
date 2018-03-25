<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Event;

use PersonalGalaxy\RSS\Entity\Article\{
    Author,
    Description,
    Title,
};
use Innmind\Url\UrlInterface;
use Innmind\TimeContinuum\PointInTimeInterface;

final class ArticleWasFetched
{
    private $link;
    private $author;
    private $description;
    private $title;
    private $publicationDate;

    public function __construct(
        UrlInterface $link,
        Author $author,
        Description $description,
        Title $title,
        PointInTimeInterface $publicationDate
    ) {
        $this->link = $link;
        $this->author = $author;
        $this->description = $description;
        $this->title = $title;
        $this->publicationDate = $publicationDate;
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
