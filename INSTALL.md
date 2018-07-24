Install
=======

This application requires PHP 7.1 or 7.2

Clone the repository
```sh
git clone git@github.com:TomHAnderson/datadog
```

Change into the datadog directory and fetch the vendors
```sh
cd datadog
./composer.phar install
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
php public/index.php http:traffic:monitor --log=/var/log/http --threshold-alert=5
```

