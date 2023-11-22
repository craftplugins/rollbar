> [!IMPORTANT]
>
> **This plugin is no longer maintained.**

# Rollbar for Craft CMS

Craft integration with error monitoring service Rollbar.

## Installation

To install Rollbar, follow these steps:

1. Download & unzip the file and place the `rollbar` directory into your `craft/plugins` directory
2. Install plugin in the Craft Control Panel under Settings > Plugins

Rollbar works on Craft 2.4.x and Craft 2.5.x.

## Configuring Rollbar

You’ll need to create a `rollbar.php` file in your `craft/config` directory and set your server access token via the `accessToken` configuration item.

```php
// craft/config/rollbar.php

return [

    'accessToken' => 'sAWNXugaQ2pusR9FqjGvTwyNaTV',

];
```

## Using Rollbar

You can use this plugin in your own plugins simply by using Craft’s internal logging feature, however should you want to report exceptions or something directly you may do so via the service.

```php
// Reporting exceptions

try {
    throw new \Exception('Something went wrong!');
} catch (\Exception $e) {
    craft()->rollbar->reportException($e);
}
```

```php
// Logging

craft()->rollbar->reportMessage('Something happened.', \Craft\LogLevel::Info, [
    'foo' => 'bar',
]);
```
