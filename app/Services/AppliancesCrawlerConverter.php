<?php

namespace App\Services;

use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class AppliancesCrawlerConverter
{
    private $crawler;
    
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function getAll()
    {
        return $this->getProductNodes()->each(function ($node) {
            return $this->getAppliance($node);
        });
    }

    public function getAppliance(Crawler $node)
    {
        return [
                'url' => $this->getUrl($node),
                'image' => $this->getImage($node),
                'title' => $this->getTitle($node),
                'model' => $this->getModel($node),
                'price' => $this->getPrice($node),
                'description' => $this->getDescription($node),
            ];
    }

    public function getProductNodes()
    {
        return $this->crawler->filter('.search-results-product');
    }

    public function getUrl(Crawler $node)
    {
        return $node->filter('h4 a')->attr('href');
    }

    public function getImage(Crawler $node)
    {
        return $node->filter('.product-image')->filter('img')->attr('data-src');
    }

    public function getTitle(Crawler $node)
    {
        return $node->filter('h4 a')->first()->text();
    }

    public function getModel(Crawler $node)
    {
        $tmp = explode(' ', $this->getTitle($node));
        return array_pop($tmp);
    }

    public function getPrice(Crawler $node)
    {
        return preg_replace('/[^0-9]/', '', $node->filter('.section-title')->first()->text());
    }

    public function getDescription(Crawler $node)
    {
        return $node->filter('.result-list-item-desc-list li')
            ->each(function ($value) {
                return $value->text();
            });
    }

    public function getPageQuantity()
    {
        return (int)Str::after($this->crawler->filter('.result-list-pagination')->children()->last()->attr('href'), 'page=');
    }

    public function getPageBaseUrl()
    {
        return Str::before($this->crawler->filter('.result-list-pagination')->children()->last()->attr('href'), '&page=');
    }
}
