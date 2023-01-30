<?php

declare(strict_types=1);

namespace App\Parser\Html;

use App\Exception\ParserException;
use DOMNode;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class responsible for extracting a list of packages (products) from HTML
 *
 * @author Karol Gancarczyk
 *
 */
class SubscriptionsParser
{
    public function __construct(
        protected readonly PackageParser $packageParser,
    ) {
    }

    public function extractPackagesData(string $html): array
    {
        $crawler = new Crawler($html);
        $packagesCrawler = $crawler->filter('div.package');

        $packagesData = [];

        /** @var DOMNode $node */
        foreach ($packagesCrawler->getIterator() as $node) {
            try {
                $nodeHtml = (new Crawler($node))->html();
                $packagesData[] = $this->packageParser->extractPackageData($nodeHtml);
            } catch (ParserException $e) {
                //skip invalid node
            }
        }

        return $packagesData;
    }
}
