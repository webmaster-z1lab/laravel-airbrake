# Laravel Airbrake

This is a Laravel service provider for the latest Airbrake PHP package https://github.com/airbrake/phpbrake

The service provider will configure an instance of Airbrake\Notifier with an ID, key and environment name.

The service provider will also filter out sensitive variables set in your project's .env file out of notifications sent to Airbrake. Any variable set in the .env file will appear with a value of "FILTERED" in the env tab of an Airbrake report.
```
"APP_KEY": "FILTERED",
```

## Install
Require via composer.
```
composer require kouz/laravel-airbrake
```
Add package to list of service providers in config/app.php
```
<?php
  //config/app.php
  
  'providers' => [
    Kouz\Providers\AirbrakeServiceProvider::class,
  ],
```
Publish and fill out the config/airbrake.php file with your ID and key.
```
php artisan vendor:publish --provider="Kouz\Providers\AirbrakeServiceProvider"
```

## Basic Usage
### Exception Handler
The easiest way to notify airbrake is through the laravel exception handler as shown in the following code snippet. Inject or make a new instance
of a Airbrake\Notifier object then pass a exception to the notify function.

```
//app/Exceptions/Handler.php

use App;

class Handler extends ExceptionHandler
{ 
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldReport($e)) {
            $airbrake = App::make('Airbrake\Notifier');
            $airbrake->notify($e);
        }

        parent::report($e);
    }
}
```

### Custom Monolog Configuration 
To configure it as a Monolog handler you will have to create a custom configuration in bootstrap/app.php. This callback function is called 
before the service providers are loaded. So it is necessary to directly use our AirbrakeHandler class instead of the provider.

```
//bootstrap/app.php

$app->configureMonologUsing(function($monolog) use ($app) {
    $airbrakeNotifier = (new Kouz\Providers\AirbrakeHandler($app))->handle();
    $monolog->pushHandler(new Airbrake\MonologHandler($airbrakeNotifier, Monolog\Logger::ERROR));
});

