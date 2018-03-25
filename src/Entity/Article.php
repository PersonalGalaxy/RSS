<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Entity;

use PersonalGalaxy\RSS\{
    Event\ArticleWasFetched,
    Event\ArticleWasMarkedAsRead,
    Entity\Article\Identity,
    Entity\Article\Author,
    Entity\Article\Description,
    Entity\Article\Title,
};
use Innmind\Url\UrlInterface;
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder,
};
use Innmind\TimeContinuum\PointInTimeInterface;

final class Article implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $link;
    private $author;
    private $description;
    private $title;
    private $publicationDate;
    private $read = false;

    private function __construct(
        UrlInterface $link,
        Author $author,
        Description $description,
        Title $title,
        PointInTimeInterface $publicationDate
    ) {
        $this->author = $author;
        $this->link = $link;
        $this->description = $description;
        $this->title = $title;
        $this->publicationDate = $publicationDate;
    }

    public static function fetch(
        UrlInterface $link,
        Author $author,
        Description $description,
        Title $title,
        PointInTimeInterface $publicationDate
    ): self {
        $self = new self($link, $author, $description, $title, $publicationDate);
        $self->record(new ArticleWasFetched($link, $author, $description, $title, $publicationDate));

        return $self;
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

    public function markAsRead(): void
    {
        $this->read = true;
        $this->record(new ArticleWasMarkedAsRead($this->link));
    }

    public function read(): bool
    {
        return $this->read;
    }
}
