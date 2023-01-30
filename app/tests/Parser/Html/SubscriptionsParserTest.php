<?php

declare(strict_types=1);

namespace App\Tests\Parser\Html;

use App\Dto\ProductOptionDto;
use App\Parser\Html\CrawlerTextExtractor;
use App\Parser\Html\PackageParser;
use App\Parser\Html\SubscriptionsParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Parser\Html\SubscriptionsParser
 *
 * @author Karol Gancarczyk
 *
 */
class SubscriptionsParserTest extends TestCase
{
    protected SubscriptionsParser $subscriptionsParser;

    protected function setUp(): void
    {
        $crawlerTextExtractor = new CrawlerTextExtractor();

        $packageParser = new PackageParser($crawlerTextExtractor);

        $this->subscriptionsParser = new SubscriptionsParser($packageParser);
    }

    public function testExtractPackagesData(): void
    {
        $html = <<<'HTML'
        <section id="subscriptions" class="content_section grid">
            <div class="row" style="margin-left:0px; margin-right:0px">
                <div class="top-line-decoration"></div>        
                <h2>Annual Subscription Packages</h2>
                <div class="colored-line"></div>
                <div class="sub-heading">
                    Choose from the packages below and get your product connected, each with per second billing.
                </div>
                <div class="pricing-table">
                    <div class="row-subscriptions" style="margin-bottom:40px;">
                        <!-- PACKAGE TWO -->
                        <div class="col-xs-4">
                            <div class="package featured center" style="margin-left:0px;">
                                <div class="header dark-bg">
                                    <h3>Standard: 12GB Data - 1 Year</h3>
                                </div>
                                <div class="package-features">
                                    <ul>
                                        <li>
                                            <div class="package-name">The standard subscription providing you with enough service time to support the average user with Data and SMS services to allow access to your device.</div>
                                        </li>
                                        <li>
                                            <div class="package-description">Up to 12GB of data per year<br> including 420 SMS<br>(5p / MB data and 4p / SMS thereafter)</div>
                                        </li>
                                        <li>
                                            <div class="package-price"><span class="price-big">£108.00</span><br>(inc. VAT)<br>Per Year
                                            <p style="color: red">Save £11.90 on the monthly price</p>
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
                            </div>
                        </div> <!-- /END PACKAGE -->

                        <!-- PACKAGE THREE -->
                        <div class="col-cs-4">
                            <div class="package featured-right" style="margin-top:0px; margin-left:0px; margin-bottom:0px">
                                <div class="header dark-bg">
                                    <h3>Optimum: 24GB Data - 1 Year</h3>
                                </div>
                                <div class="package-features">
                                    <ul>
                                        <li>
                                            <div class="package-name">The optimum subscription providing you with enough service time to support the above-average with data and SMS services to allow access to your device.</div>
                                        </li>
                                        <li>
                                            <div class="package-description">Up to 12GB of data per year<br> including 480 SMS<br>(5p / MB data and 4p / SMS thereafter)</div>
                                        </li>
                                        <li>
                                            <div class="package-price"><span class="price-big">£174.00</span><br>(inc. VAT)<br>Per Year
                                            <p style="color: red">Save £17.90 on the monthly price</p>
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
                            </div>
                        </div> <!-- /END PACKAGE -->
                    </div> <!-- /END ROW -->
                </div> <!-- /END ALL PACKAGE -->
            </div> <!-- /END CONTAINER -->
        </section>
        HTML;

        $actualPackagesData = $this->subscriptionsParser->extractPackagesData($html);

        $expectedPackagesData = [
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

    public function testExtractPackagesDataWhenNoPackagesInHtml(): void
    {
        $html = <<<'HTML'
        <section id="subscriptions" class="content_section grid">
            <div class="row" style="margin-left:0px; margin-right:0px">
                <div class="top-line-decoration"></div>        
                <h2>Annual Subscription Packages</h2>
                <div class="colored-line"></div>
                <div class="sub-heading">
                    Choose from the packages below and get your product connected, each with per second billing.
                </div>
                <div class="pricing-table">
                    <div class="row-subscriptions" style="margin-bottom:40px;">
                        <!-- PACKAGE TWO -->
                        <div class="col-xs-4">
                        </div> <!-- /END PACKAGE -->
                    </div> <!-- /END ROW -->
                </div> <!-- /END ALL PACKAGE -->
            </div> <!-- /END CONTAINER -->
        </section>
        HTML;

        $actualPackagesData = $this->subscriptionsParser->extractPackagesData($html);

        $this->assertEmpty($actualPackagesData);
    }

    public function testExtractPackagesDataShouldIgnorePackageWithoutTitle(): void
    {
        $html = <<<'HTML'
        <section id="subscriptions" class="content_section grid">
            <div class="row" style="margin-left:0px; margin-right:0px">
                <div class="top-line-decoration"></div>        
                <h2>Annual Subscription Packages</h2>
                <div class="colored-line"></div>
                <div class="sub-heading">
                    Choose from the packages below and get your product connected, each with per second billing.
                </div>
                <div class="pricing-table">
                    <div class="row-subscriptions" style="margin-bottom:40px;">
                        <!-- PACKAGE TWO -->
                        <div class="col-xs-4">
                            <div class="package featured center" style="margin-left:0px;">
                                <div class="header dark-bg">
                                </div>
                                <div class="package-features">
                                    <ul>
                                        <li>
                                            <div class="package-name">The standard subscription providing you with enough service time to support the average user with Data and SMS services to allow access to your device.</div>
                                        </li>
                                        <li>
                                            <div class="package-description">Up to 12GB of data per year<br> including 420 SMS<br>(5p / MB data and 4p / SMS thereafter)</div>
                                        </li>
                                        <li>
                                            <div class="package-price"><span class="price-big">£108.00</span><br>(inc. VAT)<br>Per Year
                                            <p style="color: red">Save £11.90 on the monthly price</p>
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
                            </div>
                        </div> <!-- /END PACKAGE -->
                    </div> <!-- /END ROW -->
                </div> <!-- /END ALL PACKAGE -->
            </div> <!-- /END CONTAINER -->
        </section>
        HTML;

        $actualPackagesData = $this->subscriptionsParser->extractPackagesData($html);

        $this->assertEmpty($actualPackagesData);
    }
}
