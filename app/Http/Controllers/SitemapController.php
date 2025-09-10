<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create()
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
                ->setPriority(0.6));

        return response($sitemap->render(), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
