<?php
declare(strict_types = 1);

namespace Tests\PersonalGalaxy\RSS\Parser;

use PersonalGalaxy\RSS\{
    Parser\Atom,
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

class AtomTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Parser::class,
            new Atom(
                $this->createMock(ReaderInterface::class),
                $this->createMock(TimeContinuumInterface::class),
                $this->createMock(UrlFactory::class)
            )
        );
        $this->assertSame('articles', Atom::key());
    }

    public function testDoesntParseWhenNoContentType()
    {
        $parser = new Atom(
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

    public function testDoesntParseWhenNotAtomContent()
    {
        $parser = new Atom(
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
                    new ContentTypeValue('application', 'rss')
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
        $parser = new Atom(
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
                    new ContentTypeValue('application', 'atom')
                )
            ));
        $response
            ->expects($this->once())
            ->method('body')
            ->willReturn(new Stream(fopen('fixtures/atom.xml', 'r')));

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
            'https://www.reddit.com/r/programming/comments/86s6am/an_intuitive_visualization_of_hash_tables/',
            (string) $article->link()
        );
        $this->assertSame(
            'An intuitive visualization of hash tables',
            (string) $article->title()
        );
        $this->assertSame(
            '/u/jeffacce',
            (string) $article->author()
        );
        $description = <<<DESC
&#32; submitted by &#32; <a href="https://www.reddit.com/user/jeffacce"> /u/jeffacce </a> <br/> <span><a href="https://www.youtube.com/watch?v=LPzN8jgbnvA">[link]</a></span> &#32; <span><a href="https://www.reddit.com/r/programming/comments/86s6am/an_intuitive_visualization_of_hash_tables/">[comments]</a></span>
DESC;
        $this->assertSame($description, (string) $article->description());
        $this->assertSame(
            '2018-03-24T10:10:49+00:00',
            $article->publicationDate()->format(new ISO8601)
        );
    }
}
