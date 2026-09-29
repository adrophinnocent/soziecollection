<?php

namespace App\Console\Commands;

use App\Http\Controllers\SitemapController;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate valid XML sitemap and robots.txt in public directory';

    public function handle(): int
    {
        $controller = new SitemapController;

        $controller->index();
        $controller->robots();

        $this->info('Successfully generated sitemap.xml and robots.txt in public directory.');

        return self::SUCCESS;
    }
}
