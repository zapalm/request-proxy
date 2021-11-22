# About this project
This is a simple PHP-library to create proxy-service via HTTP-requests just having CURL. 
That is, there is no need to install any other software, such as a proxy server (you need just PHP).
Seriously though, this project was created just for fun :)
See `tests` directory for usage examples.

## Installation
Add the dependency directly to your `composer.json` file:
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

## How to help the project grow and get updates
Give the **star** to the project. That's all! :)

## Contributing to the code

### Requirements for code contributors

Contributors **must** follow the following rules:

* **Make your Pull Request on the *dev* branch**, NOT the *master* branch.
* Do not update a helper version number.
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
