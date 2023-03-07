<?php

namespace App\Providers;

use DB;
use Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        if (env('APP_DEBUG', false)) {
            DB::listen(function($query){

                foreach ($query->bindings as $i => $bind) {
                    if($bind instanceof \DateTime){
                        $query->bindings[$i] = $bind->format('\'Y-m-d H:i:s\'');
                    } else {
                        if(is_string($bind)){
                            $query->bindings[$i] = "'$bind'";
                        }
                    }
                }

                $result = str_replace(['%', '?'], ['%%', '%s'], $query->sql);
                $result = vsprintf($result, $query->bindings);
                Log::debug("(".$query->time."s) ".$result);
            });
        }
    }
}
