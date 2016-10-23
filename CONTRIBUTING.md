# Contributions are always welcome

## Quick guide

 * Fork the repo.
 * Checkout the branch you want to make changes on:
  * Develop branch in 95% of the cases.
 * Install the dependencies: `composer install`.
 * Create branch such as: `feature-foo` or `fix-bar`.
 * Write some awesome code!
 * Add some tests, and ensure your code is PSR-2 compliant.
 * Submit your Pull Request

## When opening a pull request
You can do some things to increase the chance that your pull request is accepted the first time:

 * Submit one pull request per fix or feature.
 * If your changes are not up to date - rebase your branch on the parent branch.
 * Follow the conventions used in the project.
 * Remember about tests and documentation.

## Naming Conventions

 * Use camelCase, not underscores, for variable, function and method names, arguments.
 * Use namespaces for all classes.
 * Prefix abstract classes with Abstract.
 * Suffix interfaces with Interface.
 * Suffix traits with Trait.
 * Suffix exceptions with Exception.
 * Suffix services with Service.
 * Use alphanumeric characters and underscores for file names.

## PHPDoc

We generally follow the doc standards of Laravel.

```
/**
 * Register a binding with the container.
 *
 * @param  string|array  $abstract
 * @param  \Closure|string|null  $concrete
 * @param  bool  $shared
 * @return void
 */
public function bind($abstract, $concrete = null, $shared = false)
{
    //
}
```

## Other general standards we follow
 * [PSR-1: Basic Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
 * [PSR-2: Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
 * [PSR-4: Autoloading Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)
 * [Symfony Coding Standards](http://symfony.com/doc/current/contributing/code/standards.html)
