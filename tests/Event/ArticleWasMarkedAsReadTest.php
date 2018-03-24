<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Event;

use PersonalGalaxy\RSS\{
    Event\ArticleWasMarkedAsRead,
    Entity\Article\Identity,
    Entity\Article\Author,
    Entity\Article\Description,
    Entity\Article\Title,
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class ArticleWasMarkedAsReadTest extends TestCase
{
    public function testInterface()
    {
        $event = new ArticleWasMarkedAsRead(
            $identity = $this->createMock(Identity::class)
        );

        $this->assertSame($identity, $event->identity());
    }
}
