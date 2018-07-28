<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Parser;

use PersonalGalaxy\RSS\{
    Parser\RSS,
    Entity\Article,
    UrlFactory,
};
use Innmind\Crawler\{
    Parser,
    HttpResource\Attribute,
};
use Innmind\Http\{
    Message\Request,
    Message\Response,
    Headers\Headers,
    Header\ContentType,
    Header\ContentTypeValue,
};
use Innmind\Xml\{
    ReaderInterface,
    Reader\Reader,
    Translator\NodeTranslator,
    Translator\NodeTranslators,
};
use Innmind\Stream\Readable\Stream;
use Innmind\TimeContinuum\{
    TimeContinuumInterface,
    TimeContinuum\Earth,
    Timezone\Earth\UTC,
    Format\ISO8601,
};
use Innmind\Immutable\{
    Map,
    MapInterface,
    SetInterface,
};
use PHPUnit\Framework\TestCase;

class RSSTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Parser::class,
            new RSS(
                $this->createMock(ReaderInterface::class),
                $this->createMock(TimeContinuumInterface::class),
                $this->createMock(UrlFactory::class)
            )
        );
        $this->assertSame('articles', RSS::key());
    }

    public function testDoesntParseWhenNoContentType()
    {
        $parser = new RSS(
            $this->createMock(ReaderInterface::class),
            $this->createMock(TimeContinuumInterface::class),
            new UrlFactory\UrlFactory
        );

        $attributes = $parser->parse(
            $this->createMock(Request::class),
            $this->createMock(Response::class),
            $expected = new Map('string', Attribute::class)
        );

        $this->assertSame($expected, $attributes);
    }

    public function testDoesntParseWhenNotRSSContent()
    {
        $parser = new RSS(
            $this->createMock(ReaderInterface::class),
            $this->createMock(TimeContinuumInterface::class),
            new UrlFactory\UrlFactory
        );

        $response = $this->createMock(Response::class);
        $response
            ->expects($this->exactly(2))
            ->method('headers')
            ->willReturn(Headers::of(
                new ContentType(
                    new ContentTypeValue('application', 'atom')
                )
            ));

        $attributes = $parser->parse(
            $this->createMock(Request::class),
            $response,
            $expected = new Map('string', Attribute::class)
        );

        $this->assertSame($expected, $attributes);
    }

    public function testParseArticles()
    {
        $parser = new RSS(
            new Reader(
                new NodeTranslator(
                    NodeTranslators::defaults()
                )
            ),
            new Earth(new UTC),
            new UrlFactory\UrlFactory
        );

        $response = $this->createMock(Response::class);
        $response
            ->expects($this->exactly(2))
            ->method('headers')
            ->willReturn(Headers::of(
                new ContentType(
                    new ContentTypeValue('application', 'rss')
                )
            ));
        $response
            ->expects($this->once())
            ->method('body')
            ->willReturn(new Stream(fopen('fixtures/rss.xml', 'r')));

        $attributes = $parser->parse(
            $this->createMock(Request::class),
            $response,
            new Map('string', Attribute::class)
        );

        $this->assertInstanceOf(MapInterface::class, $attributes);
        $this->assertSame('string', (string) $attributes->keyType());
        $this->assertSame(Attribute::class, (string) $attributes->valueType());
        $this->assertCount(1, $attributes);
        $articles = $attributes->get('articles')->content();
        $this->assertInstanceOf(SetInterface::class, $articles);
        $this->assertSame(Article::class, (string) $articles->type());
        $this->assertCount(25, $articles);
        $article = $articles->current();
        $this->assertSame(
            'https://www.macg.co/publicite/2018/03/dites-adieu-itunes-avec-anytrans-101786',
            (string) $article->link()
        );
        $this->assertSame(
            'Dites adieu à iTunes avec AnyTrans',
            (string) $article->title()
        );
        $this->assertSame(
            'Article sponsorisé',
            (string) $article->author()
        );
        $description = <<<DESC
Pour gérer ses terminaux iOS, iTunes fait de moins en moins bien le travail : complexe, lourd, peu performant…&nbsp;La dernière version abandonne d’ailleurs ce qui constituait l’un de ses points forts : la prise en charge de l’App Store. À l’inverse, AnyTrans ne cesse de se bonifier avec le temps. En termes de fonctionnalités, c’est une sorte d’iTunes pour les pros.

Cette application disponible sur Mac et PC vous permet tout d’abord de switcher facilement. Elle inclut en effet un assistant qui permet de transférer en toute simplicité les données stockées sur son smartphone Android sur son nouvel iPhone.

De manière générale, la manipulation et le transfert de données sont l’un des points forts d’AnyTrans. L’appareil dispose de toute une série d’outils offrant la possibilité de transférer du contenu d’un appareil à l’autre. Vous pouvez même cloner un appareil pour profiter très rapidement de votre nouveau smartphone, ou bien même fusionner le contenu de deux appareils.... <a href="https://www.macg.co/publicite/2018/03/dites-adieu-itunes-avec-anytrans-101786" class="views-more-link">Lire la suite sur MacGeneration</a>
DESC;
        $this->assertSame($description, (string) $article->description());
        $this->assertSame(
            '2018-03-25T09:30:00+00:00',
            $article->publicationDate()->format(new ISO8601)
        );
    }
}
