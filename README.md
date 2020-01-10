# Grafite Builder

> Grafite has archived this project and no longer supports or develops the code. We recommend using only as a source of ideas for your own code.

**Builder** - A handful of tools for Rapid Laravel Development

[![Build Status](https://travis-ci.org/GrafiteInc/Builder.svg?branch=master)](https://travis-ci.org/GrafiteInc/Builder)
[![Packagist](https://img.shields.io/packagist/dt/grafite/builder.svg)](https://packagist.org/packages/grafite/builder)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://packagist.org/packages/grafite/builder)

This is a set of tools to help speed up development of Laravel apps. You can start an app with various parts prewritten (Users, User Meta, Roles, Teams). And it comes with a powerful FormMaker which can generate form content from tables, and objects. It can generate epic CRUD prototypes rapidly with full testing scripts prepared for you, requiring very little editing. It also provides an elegant Cryptography tool which is URL friendly. Finally it brings along some friends with LaravelCollective as a vendor.

##### Author(s):
* [Matt Lantz](https://github.com/mlantz) ([@mattylantz](http://twitter.com/mattylantz), mattlantz at gmail dot com)

## General Requirements
1. PHP 7.1.3+
2. OpenSSL

## Compatibility and Support
| Laravel Version | Package Tag | Supported |
|-----------------|-------------|-----------|
| 5.7.x | 2.5.x | no |
| 5.6.x | 2.4.x | no |
| 5.5.x | 2.3.x | no |
| 5.4.x | 2.2.x | no |
| 5.3.x | 2.0.x - 2.1.x | no |
| 5.1.x - 5.2.x | 1.9.x | no |

## Installation

Start a new Laravel project:

```
laravel new {project_name}
```
or
```
composer create-project laravel/laravel {project_name}
```

Then run the following to add the Grafite Builder

```php
composer require "grafite/builder"
```

Time to publish those assets! Grafite Builder uses CrudMaker and FormMaker which have publishable assets.

```php
php artisan vendor:publish
```
or
```php
php artisan vendor:publish --provider="Yab\CrudMaker\CrudMakerProvider"
php artisan vendor:publish --provider="Yab\FormMaker\FormMakerProvider"
```

You now have Grafite Builder installed. Try out the *Starter Kit*.

### Application Starter Kit

!!! warning "Make sure you followed the getting started instructions!"

Grafite Builder provides an elegant solution for starting an application by building the most basic views, controllers, models and migrations for your application. No need to use the `php artisan make:auth` because now you can easily start your whole application with this single command:

```
php artisan grafite:starter
```
!!! tip "BUT, before we do that lets get a few things set up."

In order to make use of the <u>starter kit</u> you will need to modify some files. Check out the modifications below:

Add the following to your `app/Http/Kernel.php` in the `$routeMiddleware` array.

```php
'admin' => \App\Http\Middleware\Admin::class,
'permissions' => \App\Http\Middleware\Permissions::class,
'roles' => \App\Http\Middleware\Roles::class,
'active' => \App\Http\Middleware\Active::class,
```

If you don't want to worry about email activation then remove this from the route's middleware array:
```php
'active'
```

Update the `App\User::class` in: 'config/auth.php' and 'database/factories/UserFactory.php' to this:

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

### Regarding Email Activation

The Starter kit has an email activation component added to the app to ensure your users have validated their email address.
You can disable it by removing the `active` middleware from the `web` routes. You will also have to disable the Notification but it
won't cause any problems if you remove the email activation.

### For Laravel 5.2 and later
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

#### Things to note
You may try and start quickly by testing the registration but please make sure your app's <u>email</u> is configured or it will throw an exception.
You can do this in the `.env` file easily by setting it to 'log' temporarily

```php
MAIL_DRIVER=log
```

#### Last Steps

Once you've added in all these parts you will want to run the starter command!

```php
php artisan grafite:starter
```

Then you'll have to refresh the list of all classes that need to be included in the project.

```php
composer dump-autoload
```

Then you'll need to migrate to add in the users, user meta, roles and teams tables. The seeding is run to set the initial roles for your application.

```php
php artisan migrate --seed
```

Once you get the starter kit running you can register and login to your app. You can then you can visit the settings section of the app and set your role to admin to take full control of the app.

### What Starter Publishes

#### Controllers
Grafite Builder updated the basic controllers to handle things like creating a profile when a user is registered, as well as setting default return routes to `dashboard` etc. It also provides contollers for handling profile modifications and pages, team management etc. The admin controller handles the admin of users, modifying a user provided the user has the admin role.

* app/Http/Controllers/
    * Admin/
        * DashboardController.php
        * UserController.php
        * RoleController.php
    * Auth/
        * ActivateController.php
        * ForgotPasswordController.php
        * LoginController.php
        * RegisterController.php
        * ResetPasswordController.php
    * User/
        * PasswordController.php
        * SettingsController.php
    * PagesController.php
    * TeamController.php

#### Middleware
Grafite Builder overwrites the default middleware due to changes in the redirects. It also provides the `Admin` middleware for route level protection relative to roles.

* app/Http/Middleware/
    * Active.php
    * Admin.php
    * Permissions.php
    * RedirectIfAuthenticated.php
    * Roles.php

#### Requests
There are requests provided for handling the creation of Teams and updating of all components. Here we integrate the rules required that are able to run the validations and return errors. (If you're using Grafite Builder FormMaker Facade then it will even handling accepting the errors and highlighting the appropriate fields.)

* app/Http/Requests/
    * PasswordUpdateRequest.php
    * RoleCreateRequest.php
    * TeamCreateRequest.php
    * TeamUpdateRequest.php
    * UserInviteRequest.php
    * UserUpdateRequest.php

#### Routes
Given that there are numerous routes added to handle teams, profiles, password etc all routes are overwritten with the starter kit.

* routes/web.php

#### Config
The permissions config file is published, this is a way for you to set access levels and types of permissions `Roles` can have

* config/permissions.php

#### Events
The events for various actions.

* app/Events/
    * UserRegisteredEmail.php

#### Listeners
The event listeners for various actions.

* app/Listeners/
    * UserRegisteredEmailListener.php

#### Models
Models are obvious, but when we then integrate Services below which handle all the buisness logic etc which make the calls to the models we implement SOLID practices, the Controller, Console or other Service, which calls the service only accesses the model through it. Once these have been integrated please ensure you delete the `User.php` model file and ensure that you have followed the installation and config instructions.

* app/Models/
    * UserMeta.php
    * User.php
    * Team.php
    * Role.php

#### Notifications
These are all our emails that we need to send out to the users in the application. These are amazing since they use the power of Laravel's notifcation component.

* app/Notficiations/
    * ActivateUserEmail.php
    * NewAccountEmail.php
    * ResetPasswordEmail.php

#### Services
Service structure allows us to keep the buisness logic outside of the models, and controllers. This approach is best suited for apps that may wish to integrate an API down the road or other things. It also allows for a highly testable structure to the application.

* app/Services/
    * Traits/
        * HasRoles.php
        * HasTeams.php
    * ActivateService.php
    * RoleService.php
    * TeamService.php
    * UserService.php

#### Database
Please ensure that all migrations and seeds are run post installation. These seeds set the default roles available in your application.

* database/factories/
    * RoleFactory.php
    * TeamFactory.php
    * UserFactory.php
    * UserMetaFactory.php
* database/migrations/
    * 2015_11_30_191713_create_user_meta_table.php
    * 2015_11_30_215038_create_roles_table.php
    * 2015_11_30_215040_create_role_user_table.php
    * 2015_12_04_155900_create_teams_table.php
    * 2015_12_04_155900_create_teams_users_table.php
* database/seeds/
    * DatabaseSeeder.php
    * RolesTableSeeder.php
    * UserTableSeeder.php

#### Views
The views consist of as little HTML as possible to perform the logical actions. These are intended to be the most basic, and all of which are intended to be modified.

* resources/views/
    * admin/
        * roles/
            * edit.blade.php
            * index.blade.php
            * invite.blade.php
        * users/
            * edit.blade.php
            * index.blade.php
            * invite.blade.php
        * dashboard.blade.php
    * auth/
        * activate/
            * email.blade.php
            * token.blade.php
        * passwords/
            * email.blade.php
            * reset.blade.php
        * login.blade.php
        * register.blade.php
    * errors/
        * 401.blade.php
        * 404.blade.php
        * 503.blade.php
    * partials/
        * errors.blade.php
        * message.blade.php
        * status.blade.php
    * team/
        * create.blade.php
        * edit.blade.php
        * index.blade.php
        * show.blade.php
    * user/
        * meta.blade.php
        * password.blade.php
        * settings.blade.php
    * dashboard.blade.php

#### Tests
Grafite Builder starter kit provides the basic unit tests for each of its own parts. This provides some great examples of testing for building an application quickly.

* tests/
    * Feature/
        * TeamIntegrationTest.php
    * Unit/
        * UserServiceTest.php
        * TeamServiceTest.php
        * RoleServiceTest.php

## After Setup

### Dashboard access

The application dashboard is found by browsing to the /dashboard endpoint.
The default admin user login credentials are:

* email: admin@example.com
* password: admin

### User

The user model is expanded with Grafite Builder Starter Kit. It adds to the basic user model: roles, teams, and user meta. The relationships are as follows:

* Meta: hasOne
* Roles: belongsToMany
* Team: belongsToMany

It also provides the following methods:

```
meta() // The relationship method
roles() // The relationship method
hasRole(role_name) // checks if user has role
teams() // The relationship method
isTeamMember(team_id) // checks if user is member
isTeamAdmin(team_id) // checks if user is admin level member
```

### Middleware

#### Admin
The Admin middleware acts as a tool for setting admin level permissions on the routes or controller level.

```
['middleware' => 'admin']
```

This simple addition to a route will ensure the user has access to the admin level, if not it will return them from where they came.

#### Active
The Active middleware acts checks if the account as been activated by accessing the activate url with the emailed token.

```
['middleware' => 'active']
```

This simple addition to a route will ensure the user has an activated account, if not it will redirect them to the /active page so they can request another activation token if necessary.

#### Roles
The Roles middleware allows you to set custom roles for your routes.

```
['middleware' => 'roles:admin|member']
```

#### Permissions
The Permissions middleware allows you to set custom permissions (a subset of roles) for your routes

```
['middleware' => 'permissions:admin|somethingDescriptive']
```

You can set permissions in the `config/permissions.php`

### Bootstrap UI

!!! Tip "Bootstrap Version 4"

If you feel like opting in for the Application starter kit. You also have a great bootstrapping option for the views. You can blast through the initial building of an app and hit the ground running!

```
php artisan grafite:bootstrap
```

!!! Tip "This will also ensure that your webpack file is prepared to run"

#### What Boostrap Publishes

The command will overwrite any existing files with the bootstrap version of them:

* resources/views/
    * user/
        * meta.blade.php
        * password.blade.php
        * settings.blade.php
    * admin/
        * users/
            * edit.blade.php
            * index.blade.php
            * invite.blade.php
    * auth/
        * login.blade.php
        * password.blade.php
        * register.blade.php
        * reset.blade.php
    * dashboard/
        * main.blade.php
        * panel.blade.php
    * emails/
        * new-user.blade.php
        * password.blade.php
    * errors/
        * 404.blade.php
        * 503.blade.php
    * partials/
        * errors.blade.php
        * message.blade.php
    * team/
        * create.blade.php
        * edit.blade.php
        * index.blade.php
        * show.blade.php

### Application Activities

Sometimes its handy to know what your users are up to when they browse your site or application. The Activity kit gives you everything you need to track your users and their every action. The middleware does most of it for you, but your welcome to customize it to fit your needs.

#### Setup

```php
php artisan grafite:activity
```

Add to your `config/app.php` the following:

```php
App\Providers\ActivityServiceProvider::class,
```

##### Facades
Provides the following tool for in app features:

```php
Activity::log($description);
Activity::getByUser($userId);
```

##### Helper

```php
activity($description) // will log the activity
```

### Application Features

Sometimes what we need is a simple way to toggle parts of an app on and off, or hey, maybe the client wants it. Either way, using the feature management kit can
take care of all that. Now you or your clients can toggle signups on an off, or any other features in the app. Just utilize the blade or helper components to take full control of your app.

#### Setup

```php
php artisan grafite:features
```

You may want to add this line to your navigation:

```html
<li class="nav-item"><a class="nav-link" href="{!! url('admin/features') !!}"><span class="fa fa-cog"></span> Features</a></li>
```

Add to your `config/app.php` the following:

```php
App\Providers\FeatureServiceProvider::class,
```

Add this to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:

```php
require base_path('routes/features.php');
```

##### Facades
Provides the following tool for in app features:

```php
Features::isActive($key);
```

##### Blade

```php
@feature($key)
// code goes here
@endfeature
```

##### Helper

```php
feature($key) // will return true|false
```

##### What Features Publishes:

The command will overwrite any existing files with the features version of them:

* app/Facades/Features.php
* app/Http/Controllers/Admin/FeatureController.php
* app/Http/Requests/FeatureCreateRequest.php
* app/Http/Requests/FeatureUpdateRequest.php
* app/Models/Feature.php
* app/Providers/FeatureServiceProvider.php
* app/Services/FeatureService.php
* database/migrations/2016_04_14_210036_create_features_table.php
* resources/views/admin/features/create.blade.php
* resources/views/admin/features/edit.blade.php
* resources/views/admin/features/index.blade.php
* routes/features.php
* tests/Feature/FeatureIntegrationTest.php
* tests/Unit/FeatureServiceTest.php

### Application Logs

The logs tool simply add a view of the app logs to your admin panel. This can be of assistance durring development or in keeping an application in check.

#### Setup

```php
php artisan grafite:logs
```

You may want to add this line to your navigation:

```html
<li class="nav-item"><a href="{!! url('admin/logs') !!}"><span class="fa fa-line-chart"></span> Logs</a></li>
```

Add this to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:

```php
require base_path('routes/logs.php');
```

### Application Notifications

Grafite Builder's notifications will build a basic controller, service, and views for both users and admins so you can easily notifiy your users, in bulk or specifically.

##### Setup

```php
php artisan grafite:notifications
```

You may want to add this line to your navigation:

```html
<li><a href="{!! url('user/notifications') !!}"><span class="fa fa-envelope-o"></span> Notifications</a></li>
<li><a href="{!! url('admin/notifications') !!}"><span class="fa fa-envelope-o"></span> Notifications</a></li>
```

Add this to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:

```php
require base_path('routes/notification.php');
```

##### Facades
Provides the following tool for in app notifications:

```php
Notifications::notify($userId, $flag, $title, $details);
```

<small>Flags can be any bootstrap alert: default, info, success, warning, danger</small>

##### What Notifications Publishes:

The command will overwrite any existing files with the notification version of them:

* app/Facades/Notifications.php
* app/Http/Controllers/Admin/NotificationController.php
* app/Http/Controllers/User/NotificationController.php
* app/Http/Requests/NotificationRequest.php
* app/Models/Notification.php
* app/Services/NotificationService.php
* database/migrations/2016_04_14_180036_create_notifications_table.php
* resources/views/admin/notifications/create.blade.php
* resources/views/admin/notifications/edit.blade.php
* resources/views/admin/notifications/index.blade.php
* resources/views/notifications/index.blade.php
* resources/views/notifications/show.blade.php
* routes/notification.php
* tests/NotificationIntegrationTest.php
* tests/NotificationServiceTest.php

### Forge Integration

The FORGE component provides you with access to the FORGE API in your admin panel. Rather than having to log into FORGE for each adjustment, now
you can simply log into your own application and in the admin panel adjust the scheduler, or workers on your server configuration.

##### Requires
```php
composer require themsaid/forge-sdk
```

##### Setup

```php
php artisan grafite:forge
```

You may want to add this line to your navigation:

```html
<li class="nav-item"><a href="{{ url('admin/forge/settings') }}"><span class="fa fa-fw fa-server"></span> Forge Settings</a></li>
<li class="nav-item"><a href="{{ url('admin/forge/scheduler') }}"><span class="fa fa-fw fa-calendar"></span> Forge Calendar</a></li>
<li class="nav-item"><a href="{{ url('admin/forge/workers') }}"><span class="fa fa-fw fa-cogs"></span> Forge Workers</a></li>
```

Add this to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:

You will see a line like: `->group(base_path('routes/web.php'));`

You need to change it to resemble this:
```php
->group(function () {
    require base_path('routes/web.php');
    require base_path('routes/forge.php');
}
```

Add these to the .env:
```php
FORGE_TOKEN=
FORGE_SERVER_ID=
FORGE_SITE_ID=
```

### Application API

If you feel like opting in for the Laracogs starter kit. You can also easily build in an API layer. Running the <code>grafite:api</code> command will set up the bare bones components, but you can also use the API tools as a part of the CRUD now by using the <code>--api</code> option.

##### Requires
```php
composer require tymon/jwt-auth
```

##### Setup
```
php artisan grafite:api
```

Essentially you want to do all the basic setup for JWT such as everything in here:
Then follow the directions regarding installation on: [https://github.com/tymondesigns/jwt-auth/wiki/Installation](https://github.com/tymondesigns/jwt-auth/wiki/Installation)

Add this to the `app/Providers/RouteServiceProvider.php` file in the `mapWebRoutes(Router $router)` function:
```php
require base_path('routes/api.php');
```

Add to the app/Http/Kernal.php under routeMiddleware:
```php
'jwt.auth' => \Tymon\JWTAuth\Middleware\GetUserFromToken::class,
'jwt.refresh' => \Tymon\JWTAuth\Middleware\RefreshToken::class,
```

Add to except attribute the app/Http/Middleware/VerifyCsrfToken.php (You also have to do this for CRUDs you add):
```php
'api/v1/login',
'api/v1/user/profile',
```

If you use Apache add this to the .htaccess file:
```php
RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
```

Also update your jwt config file and set the user to:

```php
\App\Models\User::class
```

##### What API publishes
The command will overwrite any existing files with the api version of them:

* app/Http/Controllers/Api/AuthController.php
* app/Http/Controllers/Api/UserController.php
* routes/api.php

### Application Queue

Horizon is amazing if you've got a redis instance configured and are running your queue through that, but not all apps need that nor do that have to start there.
If you've got a database driven queue and are looking for an easy management component to handle job retrying and cancellations then this will be a perfect
addition to your app.

##### Setup

```php
php artisan grafite:queue
```

Add this to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:

```php
require base_path('routes/queue.php');
```

You may want to add this line to your navigation:

```html
<li><a href="{!! url('admin/queue') !!}"><span class="fa fa-list"></span> Queue</a></li>
```

### Social Media Logins

If you're looking to offer social media logins on your application and want a simple way to get started then look no further. Simply run the command and follow the steps below and you'll have GitHub login out of the box. Integrating Facebook etc afterward is easy when you look at the code base.

##### Requires
```php
composer require laravel/socialite
```

##### Setup
```
php artisan grafite:socialite
```

The next step is to prepare your app with socialite:

Add this to your app config under providers:
```php
Laravel\Socialite\SocialiteServiceProvider::class
```

Add this to your app config under aliases:
```php
'Socialite' => Laravel\Socialite\Facades\Socialite::class,
```

Add `require base_path('routes/socialite.php');` to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:
```php
Route::middleware('web')
    ->namespace($this->namespace)
    ->group(function () {
        require base_path('routes/socialite.php');
        require base_path('routes/web.php');
    });
```

Finally set the access details in the services config:
```php
'github' => [
    'client_id' => 'id code',
    'client_secret' => 'secret code',
    'redirect' => 'http://your-domain/auth/github/callback',
    'scopes' => ['user:email'],
],
```

##### What Socialite publishes
The command will overwrite any existing files with the socialite version of them:

* app/Http/Controllers/Auth/SocialiteAuthController.php
* routes/socialite.php

### Application Billing

If you feel like opting in for the Grafite Builder starter kit. You can also get your app's billing handled quickly with Grafite Builder billing command and `Laravel/Cashier`. Grafite Builder's billing will build a basic controller, views etc so you can stay focused on what makes your application amazing!

##### Requires
```php
composer require laravel/cashier
```

##### Setup
```
php artisan grafite:billing
```

You have to modify the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:

You will see a line like: `->group(base_path('routes/web.php'));`

You need to change it to resemble this:
```php
->group(function () {
    require base_path('routes/web.php');
    require base_path('routes/billing.php');
}
```

Add these to the .env:
```php
SUBSCRIPTION=app_basic
STRIPE_SECRET=secret-key
STRIPE_PUBLIC=public-key
```

Add this to the app/Providers/AuthServiceProvider.php:
```php
Gate::define('access-billing', function ($user) {
    return ($user->meta->subscribed('main') && is_null($user->meta->subscription('main')->endDate));
});
```

And for the `config/services.php` you will want yours to resemble:
```php
'stripe' => [
    'model'  => App\Models\UserMeta::class,
    'key'    => env('STRIPE_PUBLIC'),
    'secret' => env('STRIPE_SECRET'),
],
```

Finally run migrate to add the subscriptions and bind them to the user meta:
```php
php artisan migrate
```

You will also want to update your webpack mix file to resemble (webpack.mix.js):
```js
.js([
    'resources/assets/js/app.js',
    'resources/assets/js/card.js',
    'resources/assets/js/subscription.js'
], 'public/js');
```

##### After Setup
You may want to add this line to your navigation:

```php
<li><a href="{{ url('user/billing/details') }}"><span class="fa fa-dollar"></span> Billing</a></li>
```

##### Notes
You may want to switch the line in the view vendor.receipt to:

```php
<strong>To:</strong> {{ $user->user()->email }}
```

We do this because rather than bind the billing to the User, we bound it to the UserMeta.

##### What Billing Publishes

The command will overwrite any existing files with the billing version of them:

* app/Http/Controllers/User/BillingController.php
* app/Models/UserMeta.php
* config/invoice.php
* config/plans.php
* database/migrations/2016_02_26_000647_create_subscriptions_table.php
* database/migrations/2016_02_26_000658_add_billings_to_user_meta.php
* resources/assets/js/card.js
* resources/assets/js/subscription.js
* resources/views/billing/card-form.blade.php
* resources/views/billing/change-card.blade.php
* resources/views/billing/details.blade.php
* resources/views/billing/invoices.blade.php
* resources/views/billing/coupons.blade.php
* resources/views/billing/subscribe.blade.php
* resources/views/billing/tabs.blade.php
* routes/billing.php

##### Accounts

The Account service is a tool for handling your subscription details, meaning you can limit your applications based on various clauses using the plans config. You're free to set these plan details restricting things within a billing cycle or just in general.

These are relative to *billing* only. They provide extra tools for handling restrictions in your application based on the plan the user subscribed to. Unless you implment the cashier billing system with the UserMeta structure provided by Laracogs it will not benefit you.

##### Config
This is the basic config for `config/plans.php`. This is for a single plan, either be subscribed or not.

!!! tip "Remember you need to have corresponding plans on Stripe ex. app_basic by default"

```
'subscription' => env('SUBSCRIPTION'),
'subscription_name' => 'main',
'plans' => [
    'app_basic' => [
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

##### Multiple Plans

In this config you can define multiple plans which can have different rules per plan. By default the kit uses a single plan. You can define this in the env as mentioned above. But if you want to do multiple plans you can change the following code:

1. Line 45 of the `BillingController.php` change `config('plans.subscription')` to: `$payload['plan']`
2. Add `name`, and `stripe_id` to the config
```
'subscription_name' => 'main',
'plans' => [
    'basic' => [
        'name' => 'Basic Subscription',
        'stripe_id' => 'basic_subscription',
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
3. Then add the following code in `resources/views/billing/subscribe.blade.php` above the card form include:

```html
<div class="form-group">
    <label class="form-label" for="plan">Plan</label>
    <select class="form-control" name="plan" id="plan">
        @foreach (config('plans.plans') as $plan)
            <option value="{{ $plan['stripe_id'] }}">{{ $plan['name'] }}</option>
        @endforeach
    </select>
</div>
```

!!! tip "You may also want to add a component to your app to allow users to switch plans. You can use the swap method to achieve this: `auth()->user()->meta->subscription(config('plans.subscription_name'))->swap($request->plan)`"

##### Service Methods

<br>
#### currentBillingCycle()
By using this method at the beginning and chaining another method after it you enable the restriction of the query to be performed but restricting itself to the current billing cycle.

<br>
#### canAccess($area)
Returns a boolean if the user can enter

<br>
#### cannotAccess($area)
Returns a boolean if the user cannot enter

<br>
#### getClause($key)
Returns the specified clause of the plans

<br>
#### getLimit($model)
Returns the specified limit of a model or pivot table

<br>
#### withinLimit($model, $key = 'user_id', $value = {user's id})
Confirms that the specified model only has the specified amount collected by the $key and $value.
* If you add the `currentBillingCycle()` before this then it would add the further restrictions.

<br>
#### creditsUsed($model, $key = 'user_id', $value = {user's id})
Reports back the specified amount collected by the $key and $value.
* If you add the `currentBillingCycle()` before this then it would add the further restrictions.

<br>
#### creditsAvailable($model)
Returns the number of credits remaining based on the config and the previous method.
* If you add the `currentBillingCycle()` before this then it would add the further restrictions.

<br>
#### clause($key, $method, $model = null)
Performs a custom method with rules defined by the clause name in the config. The Closure method in this is provided with the following parameters: $user, $subscription, $clause, $query

## License
Grafite Builder is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Bug Reporting and Feature Requests
Please add as many details as possible regarding submission of issues and feature requests

### Disclaimer
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
