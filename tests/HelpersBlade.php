<?php

namespace Tests;

use Clickfwd\Yoyo\Blade\Application;
use Clickfwd\Yoyo\Blade\YoyoServiceProvider;
use Clickfwd\Yoyo\ViewProviders\BladeViewProvider;
use Clickfwd\Yoyo\Yoyo;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\Fluent;
use Jenssegers\Blade\Blade;
use function Tests\yoyo_instance;

function yoyo_blade()
{
    $blade = blade();
    
    yoyo_instance()->registerViewProvider(function () use ($blade) {
        return new BladeViewProvider($blade);
    });
}

function blade()
{
    $app = Yoyo::getInstance()->container();
    
    $app->bind(ApplicationContract::class, Application::class);
    
    $app->alias('view', ViewFactory::class);
    
    $app->extend('config', function ($config) {
        return is_array($config) ? new Fluent($config) : $config;
    });
    
    $blade = new Blade(__DIR__.'/app/resources/views/yoyo', __DIR__.'/compiled', $app);
    
    $app->bind('view', function () use ($blade) {
        return $blade;
    });
    
    (new YoyoServiceProvider($app))->boot();
    
    return $blade;
}
