<?php

declare(strict_types=1);

namespace App\Tests\Parser\Html;

use App\Dto\ProductOptionDto;
use App\Parser\Html\CrawlerTextExtractor;
use App\Parser\Html\PackageParser;
use App\Parser\Html\SubscriptionsParser;
use App\Parser\Html\WebsiteParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Parser\Html\WebsiteParser
 *
 * @author Karol Gancarczyk
 *
 */
class WebsiteParserTest extends TestCase
{
    protected WebsiteParser $websiteParser;

    protected function setUp(): void
    {
        $crawlerTextExtractor = new CrawlerTextExtractor();
        $packageParser = new PackageParser($crawlerTextExtractor);
        $subscriptionsParser = new SubscriptionsParser($packageParser);

        $this->websiteParser = new WebsiteParser($subscriptionsParser);
    }

    public function testExtractPackagesData(): void
    {
        $html = file_get_contents(__DIR__ . '/../../data/website.html');

        $actualPackagesData = $this->websiteParser->extractPackagesData($html);

        $expectedPackagesData = [
            new ProductOptionDto(
                'Basic: 500MB Data - 12 Months',
                'Up to 500MB of data per month including 20 SMS (5p / MB data and 4p / SMS thereafter)',
                '£5.99',
                '(inc. VAT) Per Month',
                '',
            ),
            new ProductOptionDto(
                'Standard: 1GB Data - 12 Months',
                'Up to 1 GB data per month including 35 SMS (5p / MB data and 4p / SMS thereafter)',
                '£9.99',
                '(inc. VAT) Per Month',
                '',
            ),
            new ProductOptionDto(
                'Optimum: 2 GB Data - 12 Months',
                '2GB data per month including 40 SMS (5p / minute and 4p / SMS thereafter)',
                '£15.99',
                '(inc. VAT) Per Month',
                '',
            ),
            new ProductOptionDto(
                'Basic: 6GB Data - 1 Year',
                'Up to 6GB of data per year including 240 SMS (5p / MB data and 4p / SMS thereafter)',
                '£66.00',
                '(inc. VAT) Per Year',
                'Save £5.86 on the monthly price',
            ),
            new ProductOptionDto(
                'Standard: 12GB Data - 1 Year',
                'Up to 12GB of data per year including 420 SMS (5p / MB data and 4p / SMS thereafter)',
                '£108.00',
                '(inc. VAT) Per Year',
                'Save £11.90 on the monthly price',
            ),
            new ProductOptionDto(
                'Optimum: 24GB Data - 1 Year',
                'Up to 12GB of data per year including 480 SMS (5p / MB data and 4p / SMS thereafter)',
                '£174.00',
                '(inc. VAT) Per Year',
                'Save £17.90 on the monthly price',
            ),
        ];

        $this->assertEquals($expectedPackagesData, $actualPackagesData);
    }
}
