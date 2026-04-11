# About this project
**This is a simple library/project to create proxy-service just having PHP and a web-server.** 
That is, there is no need to install any other software.

**This project includes code to implement the client and server.** Everything is done in PHP language.
But you can implement your own client in any programming language and use the server that is included with this project.

**When might you need this project?**
For research and practical purposes, bypassing blocking and restrictions on websites or services from which you want to 
obtain data (to parse pages or access to restricted APIs). For example, in such cases of use:
1. To send requests to services and websites that are blocked in your country, for example, `api.telegram.org`.
   When the blocking is so severe that requests do not pass through regular proxies such as _SOCKS5_.
2. When you have limited resources for your pet project and want to make do with free or super cheap shared hosting that 
   doesn't have the ability to install regular _HTTP/SOCKS proxy-server_.
3. Is this approach suitable for production use? Yes, but not for high loads, which also require maximum proxy-service speed.
   Meeting these high load and speed requirements requires customization and configuration of additional software. However, 
   another solution is better suited for this purpose.

## System requirements
1. PHP >= 5.6
2. Apache 2. You can use another web-server, but security rules are currently only was made for Apache (via `.htaccess`). 
   **You should not ignore these security rules.**

## Installation

### Configure `request-proxy` server
1. Go to your web directory on your web-server, for example, `cd /var/www/`
2. `git clone https://github.com/zapalm/request-proxy.git`
3. `cd request-proxy`
4. `composer install`
5. `cp examples/1/test-api.php api.php` (`api.php` file is the entry point to your proxy service)
6. `nano api.php` and then edit the path to the `vendor` directory and specify your access `token`

### Configure `request-proxy` client in your project
1. Add the dependency directly to your `composer.json` file of your project:
```
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/zapalm/request-proxy"
  }
],
"require": {
  "php": ">=5.6",
  "zapalm/request-proxy": "dev-master"
},
```
2. `composer update`
3. Finally, write the client code to work with your `request-proxy` service using the example in `tests/TestRequestProxy.php`.

## Update
Update your server code periodically: 
```
cd request-proxy
git pull
composer update
```

## How to help the project grow and get updates
Give the **star** to the project. That's all! :)

## Contributing to the code

### Requirements for code contributors

Contributors **must** follow the following rules:

* **Make your Pull Request on the *dev* branch**, NOT the *master* branch.
* Follow [PSR coding standards][1].

### Process in details for code contributors

Contributors wishing to edit the project's files should follow the following process:

1. Create your GitHub account, if you do not have one already.
2. Fork the project to your GitHub account.
3. Clone your fork to your local machine.
4. Create a branch in your local clone of the project for your changes.
5. Change the files in your branch. Be sure to follow [the coding standards][1].
6. Push your changed branch to your fork in your GitHub account.
7. Create a pull request for your changes **on the *dev* branch** of the project.
   If you need help to make a pull request, read the [GitHub help page about creating pull requests][2].
8. Wait for the maintainer to apply your changes.

**Do not hesitate to create a pull request if even it's hard for you to apply the coding standards.**

[1]: https://www.php-fig.org/psr/
[2]: https://help.github.com/articles/about-pull-requests/
