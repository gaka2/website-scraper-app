<?php

declare(strict_types=1);

namespace App\Parser\Html;

use DOMNode;
use DOMText;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class responsible for extracting text representation from Crawler
 * Useful when Crawler contains a few text nodes that are not separated by space
 *
 * @author Karol Gancarczyk
 *
 */
class CrawlerTextExtractor
{
    public function extractText(Crawler $crawler): string
    {
        $domNode = $crawler->getNode(0);
        if (null === $domNode) {
            throw new InvalidArgumentException('The current node list is empty.');
        }

        $text = '';

        /** @var DOMNode $childNode */
        foreach ($domNode->childNodes as $childNode) {
            if (!$childNode instanceof DOMText) {
                continue;
            }

            $childNodeText = (new Crawler($childNode))->text();

            if ($childNodeText === '') {
                //do not append empty text
                continue;
            }

            if ($text !== '') {
                //prepend next part with space
                $text .= ' ';
            }

            $text .= $childNodeText;
        }

        return $text;
    }
}
