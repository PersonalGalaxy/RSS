<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Handler;

use PersonalGalaxy\RSS\{
    Handler\FetchSubscriptionHandler,
    Command\FetchSubscription,
    Repository\SubscriptionRepository,
    Repository\ArticleRepository,
    Entity\Subscription,
    Entity\Subscription\Identity,
    Entity\Subscription\Name,
    Entity\Subscription\User,
    Entity\Article,
    Entity\Article\Author,
    Entity\Article\Description,
    Entity\Article\Title,
};
use Innmind\Crawler\{
    Crawler,
    HttpResource,
    HttpResource\Attribute,
};
use Innmind\Filesystem\MediaType\MediaType;
use Innmind\Url\UrlInterface;
use Innmind\Stream\Readable;
use Innmind\TimeContinuum\PointInTimeInterface;
use Innmind\Immutable\{
    Map,
    Set,
};
use PHPUNit\Framework\TestCase;

class FetchSubscriptionHandlerTest extends TestCase
{
    public function testInterface()
    {
        $handle = new FetchSubscriptionHandler(
            $subscriptions = $this->createMock(SubscriptionRepository::class),
            $articles = $this->createMock(ArticleRepository::class),
            $crawler = $this->createMock(Crawler::class)
        );
        $command = new FetchSubscription(
            $identity = $this->createMock(Identity::class)
        );
        $subscriptions
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(Subscription::add(
                $identity,
                $this->createMock(User::class),
                new Name('foo'),
                $location = $this->createMock(UrlInterface::class)
            ));
        $crawler
            ->expects($this->once())
            ->method('execute')
            ->with($this->callback(static function($request) use ($location): bool {
                return $request->url() === $location &&
                    (string) $request->method() === 'GET' &&
                    (string) $request->protocolVersion() === '2.0';
            }))
            ->willReturn(new HttpResource(
                $location,
                new MediaType('application', 'rss'),
                (new Map('string', Attribute::class))
                    ->put('articles', $fetched = $this->createMock(Attribute::class)),
                $this->createMock(Readable::class)
            ));
        $fetched
            ->expects($this->once())
            ->method('content')
            ->willReturn(Set::of(
                Article::class,
                $first = Article::fetch(
                    $this->createMock(UrlInterface::class),
                    new Author(''),
                    new Description(''),
                    new Title(''),
                    $this->createMock(PointInTimeInterface::class)
                ),
                $second = Article::fetch(
                    $this->createMock(UrlInterface::class),
                    new Author(''),
                    new Description(''),
                    new Title(''),
                    $this->createMock(PointInTimeInterface::class)
                ),
                $third = Article::fetch(
                    $this->createMock(UrlInterface::class),
                    new Author(''),
                    new Description(''),
                    new Title(''),
                    $this->createMock(PointInTimeInterface::class)
                )
            ));
        $articles
            ->expects($this->at(0))
            ->method('has')
            ->with($first->link())
            ->willReturn(false);
        $articles
            ->expects($this->at(1))
            ->method('has')
            ->with($second->link())
            ->willReturn(true);
        $articles
            ->expects($this->at(2))
            ->method('has')
            ->with($first->link())
            ->willReturn(false);
        $articles
            ->expects($this->at(3))
            ->method('add')
            ->with($first);
        $articles
            ->expects($this->at(4))
            ->method('add')
            ->with($third);

        $this->assertNull($handle($command));
        $this->assertSame($identity, $first->subscription());
        $this->assertSame($identity, $third->subscription());

        $this->expectException(\typeError::class);

        // not bound to subscription as we don't want to persist this article
        $second->subscription();
    }
}
