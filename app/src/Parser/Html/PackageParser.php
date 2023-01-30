<?php

declare(strict_types=1);

namespace App\Parser\Html;

use App\Dto\ProductOptionDto;
use App\Exception\ParserException;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class responsible for extracting single package data from HTML
 *
 * @author Karol Gancarczyk
 *
 */
class PackageParser
{
    public function __construct(
        protected readonly CrawlerTextExtractor $crawlerTextExtractor,
    ) {
    }

    public function extractPackageData(string $html): ProductOptionDto
    {
        $crawler = new Crawler($html);

        $title = $this->extractTitle($crawler);
        $description = $this->extractDescription($crawler);
        $price = $this->extractPrice($crawler);
        $priceDescription = $this->extractPriceDescription($crawler);
        $discountDescription = $this->extractDiscountDescription($crawler);

        return new ProductOptionDto($title, $description, $price, $priceDescription, $discountDescription);
    }

    private function extractTitle(Crawler $crawler): string
    {
        try {
            return $crawler
                ->filter('div.header > h3')
                ->first()
                ->text()
            ;
        } catch (InvalidArgumentException $e) {
            throw new ParserException('Missing package title');
        }
    }

    private function extractDescription(Crawler $crawler): string
    {
        try {
            $descriptionCrawler = $crawler->filter('div.package-features div.package-description')->first();

            return $this->crawlerTextExtractor->extractText($descriptionCrawler);
        } catch (InvalidArgumentException $e) {
            throw new ParserException('Missing package description');
        }
    }

    private function extractPrice(Crawler $crawler): string
    {
        try {
            return $crawler
                ->filter('div.package-features div.package-price span.price-big')
                ->text()
            ;
        } catch (InvalidArgumentException $e) {
            throw new ParserException('Missing package price');
        }
    }

    private function extractPriceDescription(Crawler $crawler): string
    {
        $priceDescriptionCrawler = $crawler
            ->filter('div.package-features div.package-price')
            ->first()
        ;

        return $this->crawlerTextExtractor->extractText($priceDescriptionCrawler);
    }

    private function extractDiscountDescription(Crawler $crawler): string
    {
        try {
            return $crawler
                ->filter('div.package-features div.package-price p')
                ->first()
                ->text()
            ;
        } catch (InvalidArgumentException $e) {
            //no discount description is present

            return '';
        }
    }
}
