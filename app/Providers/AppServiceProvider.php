<?php

namespace App\Providers;

use App\Admin\Extensions\Form\Field\MultiDateTimeSelect;
use App\Admin\Extensions\Form\Field\WeekTimeSelector;
use Encore\Admin\Form;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        Form::extend("multiDatetime", MultiDateTimeSelect::class);
//        Form::extend("weekTimeSelect", WeekTimeSelector::class);

    }
}
