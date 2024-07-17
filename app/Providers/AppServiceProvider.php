<?php

namespace App\Providers;

use App\Models\MongoDB\SupportChat;
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
         $this->app->bind(
            'App\Twilloverify\TwilloService',
            'App\Services\TwilloVerification'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            \URL::forceScheme('https');
       }
        if (auth()->check()) {
            view()->composer('*', function ($view) {
                $supportChatId = SupportChat::where(['user_id' => auth()->user()->id, 'company_id' => auth()->user()->default_company])->first();
                $view->with('supportChatId', $supportChatId->_id ?? '');
            });
        }
    }
}
