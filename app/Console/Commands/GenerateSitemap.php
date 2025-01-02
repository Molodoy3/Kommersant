<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Property;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $baseUrl = 'https://an-kommersant-sol.ru';

        $sitemap = Sitemap::create();

        $sitemap->add(Url::create($baseUrl . "/")->setLastModificationDate(now()));
        $sitemap->add(Url::create($baseUrl . "/news")->setLastModificationDate(now()));
        $sitemap->add(Url::create($baseUrl . "/properties")->setLastModificationDate(now()));

        Article::query()->get()->each(function (Article $article) use ($baseUrl, $sitemap) {
            $sitemap->add(
                Url::create($baseUrl . "/news/{$article->id}")->setLastModificationDate(now())
            );
        });
        Property::query()->get()->each(function (Property $property) use ($baseUrl, $sitemap) {
           $sitemap->add(
               Url::create($baseUrl . "/property/{$property->id}")->setLastModificationDate(now())
           );
        });

        $sitemap->writeToFile('/var/www/html/sitemap.xml');
    }
}
