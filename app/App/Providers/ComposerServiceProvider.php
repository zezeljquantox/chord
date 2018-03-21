<?php

namespace Chord\App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Class ComposerServiceProvider
 * @package Chord\App\Providers
 */
class ComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer(
            'welcome', 'Chord\Http\Postcode\ViewComposers\PostcodeComposer'
        );
    }
}