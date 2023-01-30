<?php

declare(strict_types=1);

namespace App\Tests\Parser\Html;

use App\Parser\Html\CrawlerTextExtractor;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @covers \App\Parser\Html\CrawlerTextExtractor
 *
 * @author Karol Gancarczyk
 *
 */
class CrawlerTextExtractorTest extends TestCase
{
    protected CrawlerTextExtractor $crawlerTextExtractor;

    protected function setUp(): void
    {
        $this->crawlerTextExtractor = new CrawlerTextExtractor();
    }

    /**
     * @dataProvider provideHtmlAndExpectedResult
     */
    public function testExtractText(string $html, string $expectedText): void
    {
        //needed because the Crawler prepends provided HTML with <html><body>
        $crawler = (new Crawler($html))->filter('html body')->children();

        $actualText = $this->crawlerTextExtractor->extractText($crawler);

        $this->assertEquals($expectedText, $actualText);
    }

    public function testShouldThrowExceptionWhenEmptyCrawler(): void
    {
        $crawler = new Crawler();

        $this->expectException(InvalidArgumentException::class);

        $this->crawlerTextExtractor->extractText($crawler);
    }

    public function provideHtmlAndExpectedResult(): array
    {
        $expectedResultForDiv = 'Up to 6GB of data per year including 240 SMS';

        $htmlWithNewLine = <<<'HTML'
        <div>£108.00<br>(inc. VAT)<br>Per Year
        <p></p>
        </div>
        HTML;

        return [
            ['<div>Up to 6GB of data per year<br>including 240 SMS</div>', $expectedResultForDiv],
            ['<div>Up to 6GB of data per year<br> including 240 SMS</div>', $expectedResultForDiv],
            ['<div>Up to 6GB of data per year <br>including 240 SMS</div>', $expectedResultForDiv],
            ['<div>Up to 6GB of<span>kupa</span> data per year <br> including 240 SMS</div>', $expectedResultForDiv],
            ['<span class="price-big">£66.00</span>', '£66.00'],
            [$htmlWithNewLine, '£108.00 (inc. VAT) Per Year'],
        ];
    }
}
