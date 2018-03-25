<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Event;

use PersonalGalaxy\RSS\{
    Event\ArticleWasFetched,
    Entity\Article\Author,
    Entity\Article\Description,
    Entity\Article\Title,
};
use Innmind\Url\UrlInterface;
use Innmind\TimeContinuum\PointInTimeInterface;
use PHPUnit\Framework\TestCase;

class ArticleWasFetchedTest extends TestCase
{
    public function testInterface()
    {
        $event = new ArticleWasFetched(
            $link = $this->createMock(UrlInterface::class),
            $author = new Author('foo'),
            $description = new Description('bar'),
            $title = new Title('baz'),
            $publicationDate = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertSame($author, $event->author());
        $this->assertSame($link, $event->link());
        $this->assertSame($description, $event->description());
        $this->assertSame($title, $event->title());
        $this->assertSame($publicationDate, $event->publicationDate());
    }
}
