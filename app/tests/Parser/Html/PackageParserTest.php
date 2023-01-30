<?php

declare(strict_types=1);

namespace App\Tests\Parser\Html;

use App\Dto\ProductOptionDto;
use App\Exception\ParserException;
use App\Parser\Html\CrawlerTextExtractor;
use App\Parser\Html\PackageParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Parser\Html\PackageParser
 *
 * @author Karol Gancarczyk
 *
 */
class PackageParserTest extends TestCase
{
    protected PackageParser $packageParser;

    protected function setUp(): void
    {
        $crawlerTextExtractor = new CrawlerTextExtractor();

        $this->packageParser = new PackageParser($crawlerTextExtractor);
    }

    public function testExtractPackageData(): void
    {
        $html = <<<'HTML'
        <div class="header dark-bg">
            <h3>Basic: 6GB Data - 1 Year</h3>
        </div>
        <div class="package-features">
            <ul>
                <li>
                    <div class="package-name">The basic starter subscription providing you with all you need to get you up and running with Data and SMS services to allow access to your device.</div>
                </li>
                <li>
                    <div class="package-description">Up to 6GB of data per year<br>including 240 SMS<br>(5p / MB data and 4p / SMS thereafter)</div>
                </li>
                <li>
                    <div class="package-price"><span class="price-big">£66.00</span><br>(inc. VAT)<br>Per Year
                        <p style="color: red">Save £5.86 on the monthly price</p>
                    </div>
                </li>
                <li>
                    <div class="package-data">Annual - Data &amp; SMS Service Only</div>
                </li>
            </ul>
            <div class="bottom-row">
                <a class="btn btn-primary main-action-button" href="https://wltest.dns-systems.net/#" role="button">Choose</a>
            </div>
        </div>
        HTML;

        $expectedProductOptionDto = new ProductOptionDto(
            'Basic: 6GB Data - 1 Year',
            'Up to 6GB of data per year including 240 SMS (5p / MB data and 4p / SMS thereafter)',
            '£66.00',
            '(inc. VAT) Per Year',
            'Save £5.86 on the monthly price',
        );

        $actualProductOptionDto = $this->packageParser->extractPackageData($html);

        $this->assertEquals($expectedProductOptionDto, $actualProductOptionDto);
    }


    public function testExtractPackageDataWithoutDiscountDescription(): void
    {
        $html = <<<'HTML'
        <div class="header dark-bg">
            <h3>Basic: 6GB Data - 1 Year</h3>
        </div>
        <div class="package-features">
            <ul>
                <li>
                    <div class="package-name">The basic starter subscription providing you with all you need to get you up and running with Data and SMS services to allow access to your device.</div>
                </li>
                <li>
                    <div class="package-description">Up to 6GB of data per year<br>including 240 SMS<br>(5p / MB data and 4p / SMS thereafter)</div>
                </li>
                <li>
                    <div class="package-price"><span class="price-big">£66.00</span><br>(inc. VAT)<br>Per Year
                    </div>
                </li>
                <li>
                    <div class="package-data">Annual - Data &amp; SMS Service Only</div>
                </li>
            </ul>
            <div class="bottom-row">
                <a class="btn btn-primary main-action-button" href="https://wltest.dns-systems.net/#" role="button">Choose</a>
            </div>
        </div>
        HTML;

        $expectedProductOptionDto = new ProductOptionDto(
            'Basic: 6GB Data - 1 Year',
            'Up to 6GB of data per year including 240 SMS (5p / MB data and 4p / SMS thereafter)',
            '£66.00',
            '(inc. VAT) Per Year',
            '',
        );

        $packageData = $this->packageParser->extractPackageData($html);

        $this->assertEquals($expectedProductOptionDto, $packageData);
    }

    public function testShouldThrowExceptionWhenPackageTitleIsMissing(): void
    {
        $html = <<<'HTML'
        <div class="header dark-bg">
        </div>
        <div class="package-features">
            <ul>
                <li>
                    <div class="package-name">The basic starter subscription providing you with all you need to get you up and running with Data and SMS services to allow access to your device.</div>
                </li>
                <li>
                    <div class="package-description">Up to 6GB of data per year<br>including 240 SMS<br>(5p / MB data and 4p / SMS thereafter)</div>
                </li>
                <li>
                    <div class="package-price"><span class="price-big">£66.00</span><br>(inc. VAT)<br>Per Year
                        <p style="color: red">Save £5.86 on the monthly price</p>
                    </div>
                </li>
                <li>
                    <div class="package-data">Annual - Data &amp; SMS Service Only</div>
                </li>
            </ul>
            <div class="bottom-row">
                <a class="btn btn-primary main-action-button" href="https://wltest.dns-systems.net/#" role="button">Choose</a>
            </div>
        </div>
        HTML;

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Missing package title');

        $this->packageParser->extractPackageData($html);
    }

    public function testShouldThrowExceptionWhenPackageDescriptionIsMissing(): void
    {
        $html = <<<'HTML'
        <div class="header dark-bg">
            <h3>Basic: 6GB Data - 1 Year</h3>
        </div>
        <div class="package-features">
            <ul>
                <li>
                    <div class="package-name">The basic starter subscription providing you with all you need to get you up and running with Data and SMS services to allow access to your device.</div>
                </li>
                <li>
                </li>
                <li>
                    <div class="package-price"><span class="price-big">£66.00</span><br>(inc. VAT)<br>Per Year
                        <p style="color: red">Save £5.86 on the monthly price</p>
                    </div>
                </li>
                <li>
                    <div class="package-data">Annual - Data &amp; SMS Service Only</div>
                </li>
            </ul>
            <div class="bottom-row">
                <a class="btn btn-primary main-action-button" href="https://wltest.dns-systems.net/#" role="button">Choose</a>
            </div>
        </div>
        HTML;

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Missing package description');

        $this->packageParser->extractPackageData($html);
    }

    public function testShouldThrowExceptionWhenPackagePriceIsMissing(): void
    {
        $html = <<<'HTML'
        <div class="header dark-bg">
            <h3>Basic: 6GB Data - 1 Year</h3>
        </div>
        <div class="package-features">
            <ul>
                <li>
                    <div class="package-name">The basic starter subscription providing you with all you need to get you up and running with Data and SMS services to allow access to your device.</div>
                </li>
                <li>
                    <div class="package-description">Up to 6GB of data per year<br>including 240 SMS<br>(5p / MB data and 4p / SMS thereafter)</div>
                </li>
                <li>
                    <div class="package-price"><br>(inc. VAT)<br>Per Year
                        <p style="color: red">Save £5.86 on the monthly price</p>
                    </div>
                </li>
                <li>
                    <div class="package-data">Annual - Data &amp; SMS Service Only</div>
                </li>
            </ul>
            <div class="bottom-row">
                <a class="btn btn-primary main-action-button" href="https://wltest.dns-systems.net/#" role="button">Choose</a>
            </div>
        </div>
        HTML;

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Missing package price');

        $this->packageParser->extractPackageData($html);
    }
}
