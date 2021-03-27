<?php

namespace App\Providers;

use App\CheckResult;
use App\Mark;
use App\Markcheck;
use App\Observers\MarksObserver;
use App\Observers\ResultsObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Student;
Use App\Observers\StudentObserver;
use App\Result;

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

//  public function register()
//     {
//     $this->app->bind('path.public', function () {
//     return base_path() . '/';
// });
//     }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        Student::observe(StudentObserver::class);
        CheckResult::observe(ResultsObserver::class);
        Markcheck::observe(MarksObserver::class);

    }
}
