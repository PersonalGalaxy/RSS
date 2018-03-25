<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Command;

use PersonalGalaxy\RSS\Command\MarkArticleAsRead;
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class MarkArticleAsReadTest extends TestCase
{
    public function testInterface()
    {
        $command = new MarkArticleAsRead(
            $link = $this->createMock(UrlInterface::class)
        );

        $this->assertSame($link, $command->link());
    }
}
