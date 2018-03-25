<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Event;

use PersonalGalaxy\RSS\{
    Event\ArticleWasMarkedAsRead,
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class ArticleWasMarkedAsReadTest extends TestCase
{
    public function testInterface()
    {
        $event = new ArticleWasMarkedAsRead(
            $identity = $this->createMock(UrlInterface::class)
        );

        $this->assertSame($identity, $event->link());
    }
}
