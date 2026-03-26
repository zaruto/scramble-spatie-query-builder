# Scramble extension for Spatie Query Builder
![Preview](./.github/preview.png)

## Introduction
This is the Scramble extension, which detects the usage of the Spatie query builder in your api routes and automatically adds applicable query parameters to the openapi definition.

## Installation

Install from Packagist:

```
composer require exonn-gmbh/scramble-spatie-query-builder
```

Install from your fork/repo:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/zaruto/scramble-spatie-query-builder.git"
        }
    ],
    "require": {
        "exonn-gmbh/scramble-spatie-query-builder": "dev-main"
    }
}
```

Then run:

```bash
composer update exonn-gmbh/scramble-spatie-query-builder
```

Supported baseline:

- PHP `^8.3`
- `dedoc/scramble` `^0.13.16`
- `spatie/laravel-query-builder` `^7.0.1`
- Laravel 12 test/dev stack

## Usage
1. Register the extension in your `config/scramble.php` file.
```php
'extensions' => [
    // ...
    \Exonn\ScrambleSpatieQueryBuilder\AllowedFieldsExtension::class,
    \Exonn\ScrambleSpatieQueryBuilder\AllowedSortsExtension::class,
    \Exonn\ScrambleSpatieQueryBuilder\AllowedFiltersExtension::class,
    \Exonn\ScrambleSpatieQueryBuilder\AllowedIncludesExtension::class,
//    \Exonn\ScrambleSpatieQueryBuilder\AllowedFilterModesExtension::class
],
```
2. Use Spatie Query Builder in your controller or route action as usual.

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $users = QueryBuilder::for(User::query())
            ->allowedFields(['id', 'name', 'email'])
            ->allowedFilters([
                'name',
                AllowedFilter::exact('email'),
            ])
            ->allowedIncludes(['posts', 'roles'])
            ->allowedSorts(['name', 'created_at'])
            ->paginate();

        return UserResource::collection($users);
    }
}
```

3. Open your Scramble docs. For routes that use Spatie Query Builder, the extension will add query parameters such as:

- `fields`
- `filter`
- `include`
- `sort`
- `filter_mode` when `AllowedFilterModesExtension` is enabled

4. Example query string:

```text
/api/users?fields[users]=id,name,email&filter[name]=john&include=posts&sort=-created_at
```

## Customization
By default this extension automatically updates openapi definition for you, but if you want to customize its default behaviour, you can do it in the following way

1. Open your ```AppServiceProvider.php``` and add the following code example in the ```boot``` method

```php
public function boot(): void
{
    // ...
    AllowedIncludesExtension::hook(function(Operation $operation, Parameter $parameter) {
        // Customize the example
        $parameter->example(['repositories.issues', 'repositories']);
        // Customize the description
        $parameter->description('Allows you to include additional model relations in the response');
    });
}
```
2. Customize for your needs

