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

final class Article implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $author;
    private $link;
    private $description;
    private $title;
    private $read = false;

    private function __construct(
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

    public static function fetch(
        Identity $identity,
        Author $author,
        UrlInterface $link,
        Description $description,
        Title $title
    ): self {
        $self = new self($identity, $author, $link, $description, $title);
        $self->record(new ArticleWasFetched($identity, $author, $link, $description, $title));

        return $self;
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

    public function markAsRead(): void
    {
        $this->read = true;
        $this->record(new ArticleWasMarkedAsRead($this->identity));
    }

    public function read(): bool
    {
        return $this->read;
    }
}
