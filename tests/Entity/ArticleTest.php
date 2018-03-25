<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Entity;

use PersonalGalaxy\RSS\{
    Entity\Article,
    Entity\Article\Author,
    Entity\Article\Description,
    Entity\Article\Title,
    Event\ArticleWasFetched,
    Event\ArticleWasMarkedAsRead,
};
use Innmind\Url\UrlInterface;
use Innmind\EventBus\ContainsRecordedEventsInterface;
use Innmind\TimeContinuum\PointInTimeInterface;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    public function testFetch()
    {
        $article = Article::fetch(
            $link = $this->createMock(UrlInterface::class),
            $author = new Author('foo'),
            $description = new Description('bar'),
            $title = new Title('baz'),
            $publicationDate = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertInstanceOf(Article::class, $article);
        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $article);
        $this->assertSame($author, $article->author());
        $this->assertSame($link, $article->link());
        $this->assertSame($description, $article->description());
        $this->assertSame($title, $article->title());
        $this->assertSame($publicationDate, $article->publicationDate());
        $this->assertCount(1, $article->recordedEvents());
        $event = $article->recordedEvents()->current();
        $this->assertInstanceOf(ArticleWasFetched::class, $event);
        $this->assertSame($author, $event->author());
        $this->assertSame($link, $event->link());
        $this->assertSame($description, $event->description());
        $this->assertSame($title, $event->title());
        $this->assertSame($publicationDate, $event->publicationDate());
    }

    public function testMarkAsRead()
    {
        $article = Article::fetch(
            $identity = $this->createMock(UrlInterface::class),
            new Author('foo'),
            new Description('bar'),
            new Title('baz'),
            $this->createMock(PointInTimeInterface::class)
        );

        $this->assertFalse($article->read());
        $this->assertNull($article->markAsRead());
        $this->assertNull($article->markAsRead()); // verify that calling many times generate only one event
        $this->assertTrue($article->read());
        $this->assertCount(2, $article->recordedEvents());
        $event = $article->recordedEvents()->last();
        $this->assertInstanceOf(ArticleWasMarkedAsRead::class, $event);
        $this->assertSame($identity, $event->link());
    }
}
