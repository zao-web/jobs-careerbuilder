# CareerBuilder Jobs Client

[![Latest Version](https://img.shields.io/github/release/zao-web/jobs-careerbuilder.svg?style=flat-square)](https://github.com/zao-web/jobs-careerbuilder/releases)
[![Software License](https://img.shields.io/badge/license-APACHE%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/zao-web/jobs-careerbuilder/master.svg?style=flat-square&1)](https://travis-ci.org/zao-web/jobs-careerbuilder)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/zao-web/jobs-careerbuilder.svg?style=flat-square)](https://scrutinizer-ci.com/g/zao-web/jobs-careerbuilder/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/zao-web/jobs-careerbuilder.svg?style=flat-square)](https://scrutinizer-ci.com/g/zao-web/jobs-careerbuilder)
[![Total Downloads](https://img.shields.io/packagist/dt/zao-web/jobs-careerbuilder.svg?style=flat-square)](https://packagist.org/packages/zao-web/jobs-careerbuilder)

This package provides [CareerBuilder](https://www.careerbuilder.com/) page scraping support for [Jobs Common](https://github.com/jobapis/jobs-common).

## Installation

To install, use composer:

```
composer require zao-web/jobs-careerbuilder
```

## Usage
Create a Query object and add all the parameters you'd like via the constructor.

```php
// Add parameters to the query via the constructor
$query = new JobApis\Jobs\Client\Queries\CareerBuilderQuery([
    'keyword' => 'keyword',
    'location' => 'location'
]);
```

Or via the "set" method. All of the parameters documented in Indeed's documentation can be added.

```php
// Add parameters via the set() method
$query->set('search', 'engineering');
```

You can even chain them if you'd like.

```php
// Add parameters via the set() method
$query->set('location', 'Chicago, IL')
    ->set('jobs_per_page', '100');
```

Then inject the query object into the provider.

```php
// Instantiating an IndeedProvider with a query object
$client = new JobApis\Jobs\Client\Provider\CareerBuilderProvider($query);
```

And call the "getJobs" method to retrieve results.

```php
// Get a Collection of Jobs
$jobs = $client->getJobs();
```

The `getJobs` method will return a [Collection](https://github.com/jobapis/jobs-common/blob/master/src/Collection.php) of [Job](https://github.com/jobapis/jobs-common/blob/master/src/Job.php) objects.

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/zao-web/jobs-careerbuilder/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Karl Hughes](https://github.com/karllhughes)
- [All Contributors](https://github.com/zao-web/jobs-careerbuilder/contributors)


## License

The Apache 2.0. Please see [License File](https://github.com/zao-web/jobs-careerbuilder/blob/master/LICENSE) for more information.
