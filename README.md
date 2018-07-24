Datadog Coding Challenge
========================

Installation
------------

See the [INSTALL.md](INSTALL.md) file for complete installation instructions.


Architecture
------------

This project is built on a Zend Skeleton Application with few changes to the files included with that repository.
There is a new module called Console which contains all the code for the http traffic monitor.

The console route is handled by a controller which runs the program's main loop.  This project could easliy be turned into a library which would be included in require-dev for projects but for the purpose of this challenge
that was not a goal.

The main loop opens the log file and moves the file pointer to the end of the file.  The loop then waits 10
seconds before it reads any new lines added to the log.  After all new logs have been read it moves the pointer
again to the end of the file in preparation for the next loop.  This method keeps the log file out of memory and
the application acts as a tail file pattern.

After the new lines have been parsed the Rules are invoked.  There are two rules in individual classes with a
common interface.  The rules are passed the Console object along with other data so they write directly to the
console like the controller.


Explain how you’d improve on this application design
----------------------------------------------------

* A standard for the output would be helpful such as this could create a parsable log.
* Parsing multiple log formats would be useful such as combined and vhost combined.
* As a coding challenge it's worthwhile.  PHP is a funny choice for a console application but it is common
  to have many console routes as helpers and sometimes as daemons as part of a larger application.


Evaluation Notes - Places of interest
-------------------------------------

* [Application Loop Controller](https://github.com/TomHAnderson/datadog/blob/master/module/Console/src/Controller/HttpTrafficController.php)
* [ShortStats Rule](https://github.com/TomHAnderson/datadog/blob/master/module/Console/src/Rule/ShortStats.php)
* [HighTraffic Rule](https://github.com/TomHAnderson/datadog/blob/master/module/Console/src/Rule/HighTraffic.php)
* [HighTrafic Unit Tests](https://github.com/TomHAnderson/datadog/blob/master/test/Console/HighTrafficTest.php)


Comments about the Requirements
-------------------------------

The common log format does not include the DNS of the HTTP server.  So in the "section" description which
includes http://my.site.com/... this information is not available in the log and including it as a paramter
to the http traffic monitor is unnecessary.


Requirements
------------

At Datadog, we value working on real solutions to real problems, and as such we think the best way to understand your capabilities is to give you the opportunity to solve a problem similar to the ones we solve on a daily basis. As the next step in our process, we ask that you write a simple console program that monitors HTTP traffic on your machine. Treat this as an opportunity to show us how you would write something you would be proud to put your name on. Feel free to impress us.

* Consume an actively written-to w3c-formatted HTTP access log (https://en.wikipedia.org/wiki/Common_Log_Format). It should default to reading /var/log/access.log and be overridable.

Example log lines:

```
127.0.0.1 - james [09/May/2018:16:00:39 +0000] "GET /report HTTP/1.0" 200 1234

127.0.0.1 - jill [09/May/2018:16:00:41 +0000] "GET /api/user HTTP/1.0" 200 1234

127.0.0.1 - frank [09/May/2018:16:00:42 +0000] "GET /api/user HTTP/1.0" 200 1234

127.0.0.1 - mary [09/May/2018:16:00:42 +0000] "GET /api/user HTTP/1.0" 200 1234
```

* Display stats every 10s about the traffic during those 10s: the sections of the web site with the most hits, as well as interesting summary statistics on the traffic as a whole. A section is defined as being what's before the second '/' in the path. For example, the section for "http://my.site.com/pages/create” is "http://my.site.com/pages".
* Make sure a user can keep the app running and monitor the log file continuously
* Whenever total traffic for the past 2 minutes exceeds a certain number on average, add a message saying that “High traffic generated an alert - hits = {value}, triggered at {time}”. The default threshold should be 10 requests per second and should be overridable.
* Whenever the total traffic drops again below that value on average for the past 2 minutes, print or displays another message detailing when the alert recovered.
* Write a test for the alerting logic.
* Explain how you’d improve on this application design.
* If you have access to a linux docker environment, we'd love to be able to docker build and run your project! If you don't though, don't sweat it. As an example:

```py
FROM python:3
RUN touch /var/log/access.log # since the program will read this by default
WORKDIR /usr/src
ADD . /usr/src
ENTRYPOINT ["python", "main.py"]
```

and we'll have something else write to that log file.
