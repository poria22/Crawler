<?php

namespace App\Livewire;

use App\Models\Crawler;
use Livewire\Component;

class CrawlerList extends Component
{
    public function render()
    {
        //$crawlers = Crawler::all();

        return view('livewire.crawler-list');
    }
}
