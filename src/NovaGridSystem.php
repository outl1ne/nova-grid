<?php

namespace Outl1ne\NovaGrid;


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
    }

    public function menu()
    {
        return [];
    }
}
