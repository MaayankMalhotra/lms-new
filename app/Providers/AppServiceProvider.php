<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        view()->composer('admin.partials.sidebar', function ($view) {
            $totalAssignedInternships = 0;

            if (Auth::check() && (int) Auth::user()->role === 2) {
                $totalAssignedInternships = DB::table('internships')
                    ->join('internship_batches', 'internships.id', '=', 'internship_batches.internship_id')
                    ->where('internship_batches.teacher_id', Auth::id())
                    ->distinct()
                    ->count('internships.id');
            }

            $view->with('totalAssignedInternships', $totalAssignedInternships);
        });
    }
}
