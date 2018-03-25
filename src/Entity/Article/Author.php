<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Entity\Article;

final class Author
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
