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

