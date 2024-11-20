<?php

namespace App\Http\Controllers;

use App\Observers\Pokemon\PokemonGenerationScraperObserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

class CrawlerController extends Controller
{
    public function index(Request $request)
    {
        $url = "https://insf.org/fa/news/category/28/%D8%AD%D9%85%D8%A7%DB%8C%D8%AA-%D9%87%D8%A7%DB%8C-%D9%85%D8%B4%D8%AA%D8%B1%DA%A9-%D8%A8%DB%8C%D9%86-%D8%A7%D9%84%D9%85%D9%84%D9%84";

        Crawler::create()
            ->setCrawlObserver(new PokemonGenerationScraperObserver())
            ->setMaximumDepth(0)
            ->setTotalCrawlLimit(1)
            ->startCrawling($url);
    }
}
