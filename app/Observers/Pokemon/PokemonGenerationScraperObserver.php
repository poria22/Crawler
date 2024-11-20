<?php

namespace App\Observers\Pokemon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use \Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\DomCrawler\Crawler;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use App\Models\Crawler as CrawlerModel;
class PokemonGenerationScraperObserver extends CrawlObserver
{
    private $content;

    public function __construct()
    {
        $this->content = null;
    }

    /*
     * Called when the crawler will crawl the url.
     */
    public function willCrawl(UriInterface $url, ?string $linkText): void
    {
        Log::info('willCrawl', ['url' => $url]);
    }

    /*
     * Called when the crawler has crawled the given url successfully.
     */
    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null,
        ?string $linkText = null,
    ): void {
        $html = (string) $response->getBody();

        $crawler = new Crawler($html);

        $crawler->filter('div[data-key] a')->each(function (Crawler $node) {
            $link = $node->link();
            $linkUrl = $link->getUri();

            Log::info("Found link: " . $link->getUri());

            $this->performActionOnPage($linkUrl);
        });

        Log::info("Crawled: {$url}");
    }

    private function performActionOnPage(string $url): void {
        $client = new Client();
        $response = $client->request('GET', $url);

        $html = (string) $response->getBody();
        $crawler = new Crawler($html);

        $title = $crawler->filter('h1')->text();
        $description = $crawler->filter('div.summary')->text();

        $dateInfo = $crawler->filter('span.date-info')->text();
        $date = explode('|', $dateInfo)[0];
        $jalaliDate = Verta::parse($date)->toCarbon();

        $crawler->filter('div.content')->each(function (Crawler $node) use ($title, $description, $url, $jalaliDate) {
            $content = $node->text();

            CrawlerModel::updateOrCreate(
                ['url' => $url],
                [
                    'title' => $title,
                    'description' => $description,
                    'content' => $content,
                    'create_date' => $jalaliDate,
                ]
            );
        });
    }

    /*
     * Called when the crawler had a problem crawling the given url.
     */
    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null,
        ?string $linkText = null,
    ): void {
        Log::error("Failed: {$url}");
    }

    /*
     * Called when the crawl has ended.
     */
    public function finishedCrawling(): void
    {
        Log::info("Finished crawling");
    }
}
