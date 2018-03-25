<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Handler;

use PersonalGalaxy\RSS\{
    Command\FetchSubscription,
    Repository\SubscriptionRepository,
    Repository\ArticleRepository,
    Entity\Article,
};
use Innmind\Crawler\Crawler;
use Innmind\Http\{
    Message\Request\Request,
    Message\Method\Method,
    ProtocolVersion\ProtocolVersion,
};

final class FetchSubscriptionHandler
{
    private $subscriptions;
    private $articles;
    private $crawler;

    public function __construct(
        SubscriptionRepository $subscriptions,
        ArticleRepository $articles,
        Crawler $crawler
    ) {
        $this->subscriptions = $subscriptions;
        $this->articles = $articles;
        $this->crawler = $crawler;
    }

    public function __invoke(FetchSubscription $wished): void
    {
        $subscription = $this->subscriptions->get($wished->identity());
        $resource = $this->crawler->execute(new Request(
            $subscription->location(),
            new Method(Method::GET),
            new ProtocolVersion(2, 0)
        ));
        $resource
            ->attributes()
            ->get('articles')
            ->content()
            ->filter(function(Article $article): bool {
                return !$this->articles->has($article->link());
            })
            ->foreach(function(Article $article): void {
                $this->articles->add($article);
            });
    }
}
