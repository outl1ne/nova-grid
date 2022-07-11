<?php

namespace Outl1ne\NovaGrid;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaGrid extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('nova-grid', __DIR__ . '/../dist/js/entry.js');
        Nova::style('nova-grid', __DIR__ . '/../dist/css/entry.css');
    }

    public function menu()
    {
        return [];
    }
}
