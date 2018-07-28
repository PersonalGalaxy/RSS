<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS;

use Innmind\Url\UrlInterface;

interface UrlFactory
{
    public function __invoke(string $url): UrlInterface;
}
