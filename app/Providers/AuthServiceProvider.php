<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin',function($user){
            return ($user->type === 'admin'||$user->type === 'superadmin');
        });

        Gate::define('isTutor',function($user){
            return $user->type === 'tutor';
        });

        Gate::define('isUser',function($user){
            return $user->type === 'user';
        });
        Gate::define('isStudent',function($user){
            return $user->type === 'student';
        });
        Gate::define('isSuperadmin',function($user){
            return $user->type === 'superadmin';
        });
        Gate::define('isSubjectTeacher',function($user){
            return $user->type === 'subjectTeacher';
        });
        Gate::define('isAdminOrTutorOrStudent',function($user){
            return ($user->type === 'admin'||$user->type === 'tutor'||$user->type === 'student'||$user->type === 'superadmin');
        });



        Passport::routes();
        //
    }
}
