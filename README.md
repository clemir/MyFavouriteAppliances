# MyFavouriteAppliances.wow

This is a new website built with Laravel 6 where users can see a variety of home appliances and creating a wishlist of their favourite ones which can be shared with friends.

## Features
- Users can see home appliances.
- Users can create an account.
- Users can save their favourite appliances in a wishlist.
- Users can share by email their wishlist with friends.
- Users can remove appliances from their wishlist.

## Criteria
- Source data come from [https://www.appliancesdelivered.ie/](https://www.appliancesdelivered.ie/) using a crawler.
- In a future will use an API.

## Server Requirements

You will need to make sure your server meets the following requirements:

- PHP >= 7.2.0
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Installation

- Clone or download this repository to your PHP server that met with the requirements.
- Rename .env.example to .env and fill the MYSQL database credentials.
Run `composer install`, then `php artisan key:generate` and finally `php artisan migrate`.
- In order to sync all appliances the first time, run `php artisan sync`.
- Later, there is a configured job to sync all appliances every hour. You only need to add the following Cron entry to your server:  `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`

## Testing

The website use the Laravel Testing features and PHPUnit. If you want to run the tests you need to create a database called `appliances_tests` and run `vendor\bin\phpunit`
