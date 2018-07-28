<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\UrlFactory;

use PersonalGalaxy\RSS\UrlFactory as UrlFactoryInterface;
use Innmind\Url\{
    UrlInterface,
    Url,
};

final class UrlFactory implements UrlFactoryInterface
{
    public function __invoke(string $url): UrlInterface
    {
        return Url::fromString($url);
    }
}
