<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Event;

use PersonalGalaxy\RSS\{
    Event\ArticleWasFetched,
    Entity\Article\Identity,
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
            $identity = $this->createMock(Identity::class),
            $author = new Author('foo'),
            $link = $this->createMock(UrlInterface::class),
            $description = new Description('bar'),
            $title = new Title('baz'),
            $publicationDate = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($author, $event->author());
        $this->assertSame($link, $event->link());
        $this->assertSame($description, $event->description());
        $this->assertSame($title, $event->title());
        $this->assertSame($publicationDate, $event->publicationDate());
    }
}
