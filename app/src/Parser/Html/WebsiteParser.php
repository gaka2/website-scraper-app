<?php

declare(strict_types=1);

namespace App\Parser\Html;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class responsible for extracting all packages (products) from HTML
 *
 * @author Karol Gancarczyk
 *
 */
class WebsiteParser
{
    public function __construct(
        protected readonly SubscriptionsParser $subscriptionsParser,
    ) {
    }

    public function extractPackagesData(string $html): array
    {
        $crawler = new Crawler($html);
        $subscriptionsCrawler = $crawler->filter('section#subscriptions');

        $packagesData = [];

        /** @var DOMNode $node */
        foreach ($subscriptionsCrawler->getIterator() as $node) {
            $nodeHtml = (new Crawler($node))->html();

            $packagesData = array_merge($packagesData, $this->subscriptionsParser->extractPackagesData($nodeHtml));
        }

        return $packagesData;
    }
}
