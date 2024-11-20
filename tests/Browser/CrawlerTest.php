<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CrawlerTest extends DuskTestCase
{
    /**
     * A basic browser test.
     *
     * @return void
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://insf.org/fa/news')
                ->waitFor('.news-item')
                ->each('.news-item', function ($item) {
                    $title = $item->text('.title');
                    $url = $item->attribute('a', 'href');
                    $date = $item->text('.item-meta-data');
                    $description = $item->text('p');

                    // نمایش یا ذخیره داده‌ها
                    \Log::info("Title: $title");
                    \Log::info("URL: $url");
                    \Log::info("Date: $date");
                    \Log::info("Description: $description");
                });
        });
    }
}
