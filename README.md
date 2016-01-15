# Laracogs

**Laracogs** - A handful of tools for Laravel

This is a set of tools to help speed up development of Laravel apps. You can start an app with various parts prewritten (Users, Accounts, Roles, Teams). And it comes with a powerful FormMaker which can generate form content from tables, and objects. It can generate epic CRUD prototypes rapidly with full testing scripts prepared for you, requiring very little editing. It also provides an elegant Cryptography tool which is URL friendly. Finally it brings along some friends with the LaravelCollective as a vendor.

## Documentation
[http://laracogs.com](http://laracogs.com)

**Author(s):**
* [Matt Lantz](https://github.com/mlantz) ([@mattylantz](http://twitter.com/mattylantz), matt at yabhq dot com)
* [Chris Blackwell](https://github.com/chrisblackwell) ([@mattylantz](https://twitter.com/chrisblackwell), chris at yabhq dot com)

## Requirements

1. PHP 5.5.9+
2. OpenSSL
3. Laravel 5.1+

### Composer install

```php
composer require "yab/laracogs"
```

### Providers

```php
Yab\Laracogs\LaracogsProvider::class
```

### Kernel Route Middleware

```php
'admin' => \App\Http\Middleware\Admin::class,
```

### Model
Update the model in: 'config/auth.php' and 'database/factory/ModelFactory.php'

```php
App\Repositories\User\User::class
```

### AuthServiceProvider
Add the following to 'app/Providers/AuthServiceProvider.php' in the boot method

```php
$gate->define('admin', function ($user) {
    return ($user->roles->first()->name === 'admin');
});

$gate->define('team-member', function ($user, $team) {
    return ($user->teams->find($team->id));
});
```

### Migrate
You will need to migrate to add in the users, accounts, roles and teams tables. The seeding is run to set the initial roles for your application.

```php
php artisan migrate --seed
```

### Testing
You will want to create an sqlite memory test DB

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
```

### Things to note
- You may try and start quickly by testing the registration - make sure your app's
**email** is configured or it will throw an exception.

## Starter App Structure etc
Please consult the documentation here: [http://laracogs.com](http://laracogs.com)

## Commands
The commands provided by Laracogs are as follows:

#### Starter
#### Crud
#### Docs

## Facades/ Utilities
Laracogs provides a handful of easy to use tools outside of the app starter kit, and CRUD builder:

#### Crypto
#### FormMaker
#### InputMaker

## License
Laracogs is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Bug Reporting and Feature Requests
Please add as many details as possible regarding submission of issues and feature requests

### Disclaimer
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
