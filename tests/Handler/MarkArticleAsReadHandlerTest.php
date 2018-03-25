<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Handler;

use PersonalGalaxy\RSS\{
    Handler\MarkArticleAsReadHandler,
    Command\MarkArticleAsRead,
    Repository\ArticleRepository,
    Entity\Article,
    Entity\Article\Author,
    Entity\Article\Description,
    Entity\Article\Title,
};
use Innmind\Url\UrlInterface;
use Innmind\TimeContinuum\PointInTimeInterface;
use PHPUnit\Framework\TestCase;

class MarkArticleAsReadHandlerTest extends TestCase
{
    public function testDoesNothingWhenArticleNotFound()
    {
        $handle = new MarkArticleAsReadHandler(
            $repository = $this->createMock(ArticleRepository::class)
        );
        $link = $this->createMock(UrlInterface::class);
        $repository
            ->expects($this->once())
            ->method('has')
            ->with($link)
            ->willReturn(false);
        $repository
            ->expects($this->never())
            ->method('get');

        $this->assertNull($handle(new MarkArticleAsRead($link)));
    }
    public function testMarkAsRead()
    {
        $handle = new MarkArticleAsReadHandler(
            $repository = $this->createMock(ArticleRepository::class)
        );
        $link = $this->createMock(UrlInterface::class);
        $repository
            ->expects($this->once())
            ->method('has')
            ->with($link)
            ->willReturn(true);
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($link)
            ->willReturn($article = Article::fetch(
                $link,
                new Author(''),
                new Description(''),
                new Title(''),
                $this->createMock(PointInTimeInterface::class)
            ));

        $this->assertNull($handle(new MarkArticleAsRead($link)));
        $this->assertTrue($article->read());
    }
}
