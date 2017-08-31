# Laracogs

**Laracogs** - A handful of tools for Rapid Laravel Development

[![Build Status](https://travis-ci.org/YABhq/Laracogs.svg?branch=master)](https://travis-ci.org/YABhq/Laracogs)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/YABhq/Laracogs/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/YABhq/Laracogs/?branch=develop)
[![Packagist](https://img.shields.io/packagist/dt/yab/laracogs.svg?maxAge=2592000)](https://packagist.org/packages/yab/laracogs)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?maxAge=2592000)](https://packagist.org/packages/yab/laracogs)

This is a set of tools to help speed up development of Laravel apps. You can start an app with various parts prewritten (Users, User Meta, Roles, Teams). And it comes with a powerful FormMaker which can generate form content from tables, and objects. It can generate epic CRUD prototypes rapidly with full testing scripts prepared for you, requiring very little editing. It also provides an elegant Cryptography tool which is URL friendly. Finally it brings along some friends with LaravelCollective as a vendor.

##### Author(s):
* [Matt Lantz](https://github.com/mlantz) ([@mattylantz](http://twitter.com/mattylantz), matt at yabhq dot com)
* [Chris Blackwell](https://github.com/chrisblackwell) ([@chrisblackwell](https://twitter.com/chrisblackwell), chris at yabhq dot com)

## Website
[http://laracogs.com](http://laracogs.com)

## Yab Newsletter
[Subscribe](http://eepurl.com/ck7dSv)

## Detailed Documentation
Please consult the documentation here: [http://laracogs.com/docs](http://laracogs.com/docs)

## General Requirements

1. PHP 5.6+
2. OpenSSL

## Compatibility and Support

| Laravel Version | Package Tag | Supported |
|-----------------|-------------|-----------|
| 5.5.x | 2.3.x | yes |
| 5.4.x | 2.2.x | yes |
| 5.3.x | 2.0.x - 2.1.x | no |
| 5.1.x - 5.2.x | 1.9.x | no |

----

### Installation

Start a new Laravel project:
```php
composer create-project laravel/laravel your-project-name
```

Then run the following to add Laracogs
```php
composer require "yab/laracogs"
```

Add this to the `config/app.php` in the providers array:
```php
Yab\Laracogs\LaracogsProvider::class,
```

##### After these few steps you have the following tools at your fingertips:

## CrudMaker
The CrudMaker commands build a CRUD for a table with unit tests! Use the table-crud for tables that already exist.
```php
php artisan crudmaker:new {name or snake_names} {--api} {--ui=bootstrap|semantic} {--serviceOnly} {--withFacade} {--migration} {--schema=} {--relationships=}
php artisan crudmaker:table {name or snake_names} {--api} {--ui=bootstrap|semantic} {--serviceOnly} {--withFacade}
```

## Docs
The docs can prepare documentation for business rules or prepare your app for API doc generation with Sami.
```php
php artisan laracogs:docs {action} {name=null} {version=null}
```

## Facades/ Services
Laracogs provides a handful of easy to use tools outside of the app starter kit, and CrudMaker including:

#### Crypto
Some simple cryptography tools including a random UUID generator.

```php
Crypto::uuid();
Crypto::encrypt('string');
Crypto::decrypt('enc-string');
Crypto::sharable()->encrypt('string');
Crypto::sharable()->decrypt('enc-string');
```

#### FormMaker
Build forms from as little as one line of code.

```php
FormMaker::fromTable($table, $columns = null, $class = 'form-control', $view = null, $reformatted = true, $populated = false, $idAndTimestamps = false)
FormMaker::fromObject($object, $columns = null, $view = null, $class = 'form-control', $populated = true, $reformatted = false, $idAndTimestamps = false)
FormMaker::fromArray($array, $columns = null, $view = null, $class = 'form-control', $populated = true, $reformatted = false, $idAndTimestamps = false)
```

#### InputMaker
Looking to make some inputs? Look no further.

```php
InputMaker::label($name, $attributes = [])
InputMaker::create($name, $field, $object = null, $class = 'form-control', $reformatted = false, $populated = false)
```

#### Cerebrum
A set of traits which can be added to service or models to give your app extra power!

```php
Memory // Magical caching
Linguistics // Basic NLP
```

#### Laratest
Looking to write tests for your code? Generate test structures with this handy command.

```php
php artisan laratest:unit {path to file}
php artisan laratest:route {route}
```

# Kits

You may also want to utilize our boilerplate generators, these tools will prepare your apps with starter kits, admins, user settings, notifications, billing integration, API access, Social Media logins, Bootstrap styles and more!
<br>
Kits are only compatible with the latest version of Laravel!
----

## Starter
In order to make use of the <u>starter kit</u> you will need to modify some files. Check out the modifications below:

Add the following to your `app/Http/Kernel.php` $routeMiddleware array.
```php
'admin' => \App\Http\Middleware\Admin::class,
'permissions' => \App\Http\Middleware\Permissions::class,
'roles' => \App\Http\Middleware\Roles::class,
'active' => \App\Http\Middleware\Active::class,
```

If you want to opt out of having your users confirm their email address, simply remove the `active` from the middleware in the routes files. You may also wish to remove the email notification from `UserService`.

With the roles middleware you can specify which roles are applicable separating them with pipes: `['middleware' => ['roles:admin|moderator|member']]`
The permissions middleware allows you to specify which permissions (which are bound to roles) are applicable to a route separating them with pipes: `['middleware' => ['permissions:admin|regular']]`

Update the `App\User::class` in: 'config/auth.php' and 'database/factories/ModelFactory.php' to this:
```php
App\Models\User::class
```

Add the following to 'app/Providers/AuthServiceProvider.php' in the boot method
```php
Gate::define('admin', function ($user) {
    return ($user->roles->first()->name === 'admin');
});

Gate::define('team-member', function ($user, $team) {
    return ($user->teams->find($team->id));
});

Gate::define('permission', function ($user, $permission) {
    return $user->hasPermission($permission);
});

Gate::define('role', function ($user, $role) {
    return ($user->hasRole($role));
});
```

Add the following to 'app/Providers/EventServiceProvider.php' in the $listen property

```php
'App\Events\UserRegisteredEmail' => [
    'App\Listeners\UserRegisteredEmailListener',
],
```

You will want to create an sqlite memory test database in the `config/database.php`
```php
'testing' => [
    'driver'   => 'sqlite',
    'database' => ':memory:',
    'prefix'   => '',
],
```

Add the following line to the 'phpunit.xml' file
```xml
<env name="DB_CONNECTION" value="testing"/>
<env name="MAIL_DRIVER" value="log"/>
```

##### Regarding Email Activation

The Starter kit has an email activation component added to the app to ensure your users have validated their email address.
You can disable it by removing the `active` middleware from the `web` routes. You will also have to disable the Notification but it
won't cause any problems if you remove the email activation.

##### For Laravel 5.2
You will also need to set the location of the email for password reminders. (config/auth.php - at the bottom)

```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'email' => 'emails.password',
        'table' => 'password_resets',
        'expire' => 60,
    ],
],
```

##### Things to note
You may try and start quickly by testing the registration but please make sure your app's <u>email</u> is configured or it will throw an exception.
You can do this in the `.env` file easily by setting it to 'log' temporarily

```php
MAIL_DRIVER=log
```

##### Last Steps
Once you've added in all these parts you will want to run the starter command!
```php
php artisan laracogs:starter
```

Then you'll need to migrate to add in the users, user meta, roles and teams tables. The seeding is run to set the initial roles for your application.
```php
composer dump
php artisan migrate --seed
```

To login simply enter:

```
admin@example.org
admin
```

Once you get the starter kit running you can register and login to your app. You can then visit the settings section of the app and set your role to admin to take full control of the app.
Now its time for more boilerplate generators!

### Bootstrap
----
Bootstrap prepares your application with bootstrap as a view/ css framework.
```php
php artisan laracogs:bootstrap
```

### Semantic
----
Semantic prepares your application with semantic-ui as a view/ css framework.
```php
php artisan laracogs:semantic
```

### Features
----
A powerful and remarkably handy feature management system.
```php
php artisan laracogs:feature
```

### Activity
----
The ability to track user activities and provide a layer of accountability in your app.
```php
php artisan laracogs:activity
```

### Socialite
----
Socialite prepares your application with a socialite system, with GitHub as the example:
```php
php artisan laracogs:socialite
```

### API
----
Api prepares your application with an API system using JWT (logins, and user profile):

```php
php artisan laracogs:api
```

<small>Please note that Billing and Notifications are only set for use with bootstrap</small>

### Notifications
----
Notifications prepares your application with a notification system.
```php
php artisan laracogs:notifications
```


### Billing
----
The billing command sets up your app with Laravel's cashier - it prepares the whole app to handle subscriptions with a policy structure.
```php
php artisan laracogs:billing
```

##### Requires
```php
composer require laravel/cashier
```

You may want to add this line to your navigation:
```php
<li><a href="{!! url('user/billing/details') !!}"><span class="fa fa-dollar"></span> Billing</a></li>
```

Add this to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes` method.
It will look like: `->group(base_path('routes/web.php'));` So you need to change it to resemble this:

```php
->group(function () {
    require base_path('routes/web.php');
    require base_path('routes/billing.php');
});
```

This to the .env:
```php
SUBSCRIPTION=basic
STRIPE_SECRET=public-key
STRIPE_PUBLIC=secret-key
```

This to the `app/Providers/AuthServiceProvider.php` in the `boot` method:
```php
Gate::define('access-billing', function ($user) {
    return ($user->meta->subscribed('main') && is_null($user->meta->subscription('main')->endDate));
});
```

For the `config/services.php` you will want yours to resemble:
```php
'stripe' => [
    'model'  => App\Models\UserMeta::class,
    'key'    => env('STRIPE_PUBLIC'),
    'secret' => env('STRIPE_SECRET'),
],
```

Finally run migrate to add the subscrptions and bind them to the user meta:
```php
php artisan migrate
```

You will also want to update your gulpfile.js to include the card.js, and subscription.js
```js
elixir(function(mix) {
    mix.scripts([
        'app.js',
        'card.js',
        'subscription.js'
    ]);
});
```

#### Accounts (Billing Only)

##### Plans
This is the basic config for `config/plans.php`. It sets the default subscription name, as well as the plans and the rules pertaining to them.

```
'subscription_name' => 'main',
'plans' => [
    'basic' => [
        'access' => [
            'some name'
        ],
        'limits' => [
            'Model\Namespace' => 5,
            'pivot_table' => 1
        ],
        'credits' => [
            'column' => 'credits_spent',
            'limit' => 10
        ],
        'custom' => [
            'anything' => 'anything'
        ],
    ],
]
```

##### Service
The service provides extra tools for handling restrictions in your application based on the plan the user subscribed to.

```php
getClause('box_limit');
canAccess('area_51');
cannotAccess('restricted_area');
getLimit('team_user');
withinLimit('App\Models\Team');
creditsAvailable('App\Models\Team');
creditsUsed('App\Models\Team');
currentBillingCycle()->withinLimit('App\Models\Team');
clause('custom', function($user, $subscription, $clause, $query) {
    // do your own logic!
    // model is optional if you dont provide it the query is null - otherwise it's a query builder
}, 'App\Models\Team');
```

## License
Laracogs is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Bug Reporting and Feature Requests
Please add as many details as possible regarding submission of issues and feature requests

### Disclaimer
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
