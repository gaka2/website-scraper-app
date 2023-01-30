<?php

declare(strict_types=1);

namespace App\Tests\Parser\Html;

use App\Factory\MoneyParserFactory;
use App\HttpClient\HttpClient;
use App\Model\ItemsListModel;
use App\Model\ProductOptionModel;
use App\Parser\Html\CrawlerTextExtractor;
use App\Parser\Html\PackageParser;
use App\Parser\Html\SubscriptionsParser;
use App\Parser\Html\WebsiteParser;
use App\Parser\MoneyParser;
use App\Serializer\SerializerInterface;
use App\Service\DescendingAnnualPriceSorter;
use App\Service\ProductMapperService;
use App\Service\ProductOptionsService;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\ProductOptionsService
 *
 * @author Karol Gancarczyk
 *
 */
class ProductOptionsServiceTest extends TestCase
{
    protected ProductOptionsService $productOptionsService;

    protected function setUp(): void
    {
        $websiteContent = file_get_contents(__DIR__ . '/../data/website.html');

        $httpClient = $this->createStub(HttpClient::class);
        $httpClient->method('getWebsiteContent')
             ->willReturn($websiteContent);

        $crawlerTextExtractor = new CrawlerTextExtractor();
        $packageParser = new PackageParser($crawlerTextExtractor);
        $subscriptionsParser = new SubscriptionsParser($packageParser);
        $websiteParser = new WebsiteParser($subscriptionsParser);

        $innerMoneyParser = (new MoneyParserFactory())->getParser();
        $moneyParser = new MoneyParser($innerMoneyParser);
        $productMapperService = new ProductMapperService($moneyParser);

        $productsSorter = new DescendingAnnualPriceSorter();

        $serializer = $this->createStub(SerializerInterface::class);

        $this->productOptionsService = new ProductOptionsService(
            $httpClient,
            $websiteParser,
            $productMapperService,
            $productsSorter,
            $serializer,
        );
    }

    public function testGetProductOptions(): void
    {
        $actualItems = $this->productOptionsService->getProductOptions();

        $itemsArray = [
            new ProductOptionModel(
                'Optimum: 2 GB Data - 12 Months',
                '2GB data per month including 40 SMS (5p / minute and 4p / SMS thereafter)',
                Money::GBP(19188),
                ''
            ),
            new ProductOptionModel(
                'Optimum: 24GB Data - 1 Year',
                'Up to 12GB of data per year including 480 SMS (5p / MB data and 4p / SMS thereafter)',
                Money::GBP(17400),
                'Save £17.90 on the monthly price'
            ),
            new ProductOptionModel(
                'Standard: 1GB Data - 12 Months',
                'Up to 1 GB data per month including 35 SMS (5p / MB data and 4p / SMS thereafter)',
                Money::GBP(11988),
                ''
            ),
            new ProductOptionModel(
                'Standard: 12GB Data - 1 Year',
                'Up to 12GB of data per year including 420 SMS (5p / MB data and 4p / SMS thereafter)',
                Money::GBP(10800),
                'Save £11.90 on the monthly price'
            ),
            new ProductOptionModel(
                'Basic: 500MB Data - 12 Months',
                'Up to 500MB of data per month including 20 SMS (5p / MB data and 4p / SMS thereafter)',
                Money::GBP(7188),
                ''
            ),
            new ProductOptionModel(
                'Basic: 6GB Data - 1 Year',
                'Up to 6GB of data per year including 240 SMS (5p / MB data and 4p / SMS thereafter)',
                Money::GBP(6600),
                'Save £5.86 on the monthly price'
            ),
        ];

        $expectedItems = new ItemsListModel($itemsArray);

        $this->assertEquals($expectedItems, $actualItems);
    }
}
