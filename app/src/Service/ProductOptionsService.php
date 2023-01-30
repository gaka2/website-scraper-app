<?php

declare(strict_types=1);

namespace App\Service;

use App\HttpClient\HttpClient;
use App\Dto\ProductOptionDto;
use App\Model\ItemsListModel;
use App\Model\ProductOptionModel;
use App\Parser\Html\WebsiteParser;
use App\Serializer\SerializerInterface;

/**
 *
 * @author Karol Gancarczyk
 *
 */
class ProductOptionsService
{
    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly WebsiteParser $websiteParser,
        protected readonly ProductMapperService $productMapperService,
        protected readonly ProductsSorterInterface $productsSorter,
        protected readonly SerializerInterface $serializer,
    ) {
    }

    public function getProductOptions(): ItemsListModel
    {
        $websiteContent = $this->httpClient->getWebsiteContent();
        $packagesData = $this->websiteParser->extractPackagesData($websiteContent);
        $productModels = array_map(fn (ProductOptionDto $productOptionDto): ProductOptionModel => $this->productMapperService->createModelFromDto($productOptionDto), $packagesData);
        $productModelsSorted = $this->productsSorter->sort($productModels);

        return new ItemsListModel($productModelsSorted);
    }

    public function getSerializedProductOptions(): string
    {
        return $this->serializer->serialize($this->getProductOptions());
    }
}
