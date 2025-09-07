<?php

namespace App\Console\Commands;

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
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.xml file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Sitemap::create()
            // Home page
            ->add(Url::create(route('home'))
            ->setLastModificationDate(now())
            ->setChangeFrequency('daily')
            ->setPriority(1.0))
            
            // Courses pages
            ->add(Url::create(route('courses.index'))
            ->setLastModificationDate(now())
            ->setChangeFrequency('daily')
            ->setPriority(0.9))
            
            // Rooms pages
            ->add(Url::create(route('rooms.index'))
            ->setLastModificationDate(now())
            ->setChangeFrequency('weekly')
            ->setPriority(0.8))
            
            // News pages
            ->add(Url::create(route('news.index'))
            ->setLastModificationDate(now())
            ->setChangeFrequency('daily')
            ->setPriority(0.8))
            
            // Contact page
            ->add(Url::create(route('contacts'))
            ->setLastModificationDate(now())
            ->setChangeFrequency('monthly')
            ->setPriority(0.7))
            
            // Search page
            ->add(Url::create(route('search'))
            ->setLastModificationDate(now())
            ->setChangeFrequency('monthly')
            ->setPriority(0.6))
            
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
        return 0;
    }
}
