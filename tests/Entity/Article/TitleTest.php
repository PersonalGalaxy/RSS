<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Entity\Article;

use PersonalGalaxy\RSS\Entity\Article\Title;
use PHPUnit\Framework\TestCase;
use Eris\{
    TestTrait,
    Generator,
};

class TitleTest extends TestCase
{
    use TestTrait;

    public function testInterface()
    {
        $this
            ->forAll(Generator\string())
            ->then(function(string $string): void {
                $this->assertSame($string, (string) new Title($string));
            });
    }
}
