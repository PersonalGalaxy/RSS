<?php
declare(strict_types = 1);

namespace PersonalGalaxy\RSS\Parser;

use PersonalGalaxy\RSS\{
    Entity\Article,
    Entity\Article\Author,
    Entity\Article\Description,
    Entity\Article\Title,
    UrlFactory,
};
use Innmind\Url\Url;
use Innmind\Crawler\{
    Parser,
    HttpResource\Attribute\Attribute,
};
use Innmind\Http\Message\{
    Request,
    Response,
};
use Innmind\Xml\{
    ReaderInterface,
    NodeInterface,
    ElementInterface,
};
use Innmind\TimeContinuum\TimeContinuumInterface;
use Innmind\Immutable\{
    MapInterface,
    SetInterface,
    Set,
    StreamInterface,
    Stream,
};

final class Atom implements Parser
{
    private $reader;
    private $clock;
    private $make;

    public function __construct(
        ReaderInterface $reader,
        TimeContinuumInterface $clock,
        UrlFactory $make
    ) {
        $this->reader = $reader;
        $this->clock = $clock;
        $this->make = $make;
    }

    public function parse(
        Request $request,
        Response $response,
        MapInterface $attributes
    ): MapInterface {
        if (
            !$response->headers()->has('content-type') ||
            (string) $response->headers()->get('content-type')->values()->current() !== 'application/atom'
        ) {
            return $attributes;
        }

        $xml = $this->reader->read($response->body());
        $articles = $xml
            ->children()
            ->values()
            ->first() // feed node
            ->children()
            ->values()
            ->filter(static function(NodeInterface $node): bool {
                return $node instanceof ElementInterface && $node->name() === 'entry';
            })
            ->reduce(
                Set::of(Article::class),
                function(SetInterface $articles, ElementInterface $item): SetInterface {
                    $elements = $item
                        ->children()
                        ->values()
                        ->filter(static function(NodeInterface $node): bool {
                            return $node instanceof ElementInterface;
                        });
                    $link = $elements
                        ->filter(static function(ElementInterface $element): bool {
                            return $element->name() === 'link';
                        })
                        ->first()
                        ->attributes()
                        ->get('href')
                        ->value();
                    $author = $elements
                        ->filter(static function(ElementInterface $element): bool {
                            return $element->name() === 'author';
                        })
                        ->first()
                        ->children()
                        ->values()
                        ->filter(static function(NodeInterface $node): bool {
                            return $node instanceof ElementInterface && $node->name() === 'name';
                        })
                        ->first()
                        ->content();
                    $description = $elements
                        ->filter(static function(ElementInterface $element): bool {
                            return $element->name() === 'content';
                        })
                        ->first()
                        ->content();
                    $title = $elements
                        ->filter(static function(ElementInterface $element): bool {
                            return $element->name() === 'title';
                        })
                        ->first()
                        ->content();
                    $publicationDate = $elements
                        ->filter(static function(ElementInterface $element): bool {
                            return $element->name() === 'updated';
                        })
                        ->first()
                        ->content();

                    return $articles->add(Article::fetch(
                        ($this->make)(trim($link)),
                        new Author(trim($author)),
                        new Description(trim($description)),
                        new Title(trim($title)),
                        $this->clock->at(trim($publicationDate))
                    ));
                }
            );

        return $attributes->put(
            self::key(),
            new Attribute(
                self::key(),
                $articles
            )
        );
    }

    public static function key(): string
    {
        return 'articles';
    }
}
