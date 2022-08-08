<?php

namespace Outl1ne\NovaGrid;

use Laravel\Nova\Nova;
use Laravel\Nova\Fields\Field;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Http\Requests\NovaRequest;

class ToolServiceProvider extends ServiceProvider
{
    public function boot(NovaRequest $novaRequest)
    {
        $this->publishes([
            __DIR__ . '/../config/nova-grid.php' => config_path('nova-grid.php'),
        ], 'nova-grid-system');


        // Register macros
        Field::macro('size', fn ($size = 'w-full') => $this->withMeta(['size' => $size]));
        Field::macro('sizeHalf', fn () => $this->size('w-1/2'));
        Field::macro('sizeOneThird', fn () => $this->size('w-1/3'));
        Field::macro('sizeTwoThirds', fn () => $this->size('w-2/3'));
        Field::macro('removeBottomBorder', fn () =>  $this->withMeta(['removeBottomBorder' => true]));

        Nova::serving(function () {
            Nova::script('nova-grid', __DIR__ . '/../dist/js/entry.js');
            Nova::style('nova-grid', __DIR__ . '/../dist/css/entry.css');
        });
    }
}
