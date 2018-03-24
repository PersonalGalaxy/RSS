<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Entity;

use PersonalGalaxy\RSS\{
    Entity\Article,
    Entity\Article\Identity,
    Entity\Article\Author,
    Entity\Article\Description,
    Entity\Article\Title,
    Event\ArticleWasFetched,
    Event\ArticleWasMarkedAsRead,
};
use Innmind\Url\UrlInterface;
use Innmind\EventBus\ContainsRecordedEventsInterface;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    public function testFetch()
    {
        $article = Article::fetch(
            $identity = $this->createMock(Identity::class),
            $author = new Author('foo'),
            $link = $this->createMock(UrlInterface::class),
            $description = new Description('bar'),
            $title = new Title('baz')
        );

        $this->assertInstanceOf(Article::class, $article);
        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $article);
        $this->assertSame($identity, $article->identity());
        $this->assertSame($author, $article->author());
        $this->assertSame($link, $article->link());
        $this->assertSame($description, $article->description());
        $this->assertSame($title, $article->title());
        $this->assertCount(1, $article->recordedEvents());
        $event = $article->recordedEvents()->current();
        $this->assertInstanceOf(ArticleWasFetched::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame($author, $event->author());
        $this->assertSame($link, $event->link());
        $this->assertSame($description, $event->description());
        $this->assertSame($title, $event->title());
    }

    public function testMarkAsRead()
    {
        $article = Article::fetch(
            $identity = $this->createMock(Identity::class),
            new Author('foo'),
            $this->createMock(UrlInterface::class),
            new Description('bar'),
            new Title('baz')
        );

        $this->assertFalse($article->read());
        $this->assertNull($article->markAsRead());
        $this->assertTrue($article->read());
        $this->assertCount(2, $article->recordedEvents());
        $event = $article->recordedEvents()->last();
        $this->assertInstanceOf(ArticleWasMarkedAsRead::class, $event);
        $this->assertSame($identity, $event->identity());
    }
}
