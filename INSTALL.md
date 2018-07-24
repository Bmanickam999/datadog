Install
=======

This application requires Docker

Clone the repository
```sh
git clone git@github.com:TomHAnderson/datadog
```

Change into the datadog directory, build docker and run it
```
cd datadog
docker-compose build
docker-compose run --rm php bash
```

Fetch the vendors
```sh
composer install
```

The console route for running the http traffic monitor takes two
optional parameters:

* --log=/path/to/common_log_formatted_log - This is the log file to watch
* --threshold-alert=[integer] - This is the number of requests per second which should trigger a high traffic alert

Run the application
```sh
php public/index.php http:traffic:monitor
```

Example with optional parameters
```sh
php public/index.php http:traffic:monitor --log=access.log --threshold-alert=5
```

Test the Unit Tests including generating code coverage report
(code covergae report will show 100% for the HighTraffic rule)
```sh
vendor/bin/phpunit --coverage-html=coverage
```
