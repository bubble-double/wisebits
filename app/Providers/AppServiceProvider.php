<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Predis\Client;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            Serializer::class,
            static function() {
                return new Serializer([new ObjectNormalizer()]);
            }
        );

        $this->app->bind(
            ValidatorInterface::class,
            static function() {
                return (new ValidatorBuilder())
                    ->enableAnnotationMapping() // Connecting annotation mapping
                    ->getValidator();
            }
        );

        $this->app->bind(
            Client::class,
            static function () {
                $redisConnectionParams = [
                    'host' => config('database.redis.default.host'),
                    'port' =>  config('database.redis.default.port'),
                ];
                return new Client($redisConnectionParams);
            }
        );
    }
}
