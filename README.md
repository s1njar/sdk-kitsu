Kitsu PHP Api Wrapper
=====================

## Introduction
This PHP extension works as a wrapper for interface queries at the Kitsu API.
It contains a query builder where you can add search criteria.

The docs for the api are available at (https://kitsu.docs.apiary.io/#introduction)

Project: (https://gitlab.com/s1njar/kitsu-php/)

## Installation

Run composer to require this package:
```bash
composer require s1njar/kitsu-php
```
## Usage

**Default Workflow:**

```php
//Create new SearchBuilder object.
$searchBuilder = new SearchBuilder();

//Add the endpoint to be requested.
//Calling after setting filters, filters will be resetted.
$searchBuilder = $searchBuilder->setEndpoint('anime');

//Add the fields you want to return.
$searchBuilder = $searchBuilder->addFields(['slug'], 'anime');

//Add filter to refine the search.
$searchBuilder = $searchBuilder->addFilter('slug', 'naruto');

//Add filter to refine the search, with greater than or lower than.
$searchBuilder = $searchBuilder->addFilter('slug', 'naruto', SearchBuilder::FILTER_RANGE_GREATER_THAN);

//Add a limit.
$searchBuilder = $searchBuilder->addLimit(10);

//Add an offset.
$searchBuilder = $searchBuilder->addOffset(0);

//Add an order. Default ascending.
$searchBuilder = $searchBuilder->addOrder('slug');

//Add an order, with order direction descending
$searchBuilder = $searchBuilder->addOrder('slug', SearchBuilder::ORDER_DIRECTION_DESC);

//Trigger the search. It returns an Response object. Ignores filter.
//Can take list of fields to return.
$searchBuilder = $searchBuilder->searchById(1, ['slug']);

//Trigger the search. It returns an Response object.
$searchBuilder = $searchBuilder->search();

//Decode the response from the server and return an array of objects.
$response = $searchBuilder->get();
```

**Request by id.**

```php
//Create new SearchBuilder object.
$searchBuilder = new SearchBuilder();

//Add endpoint and search by id.
$response = $searchBuilder
    ->setEndpoint('anime')
    ->searchById(1, ['slug'])
    ->get();
```

**Request by search**

```php
//Create new SearchBuilder object.
$searchBuilder = new SearchBuilder();

//Add endpoint and search needle.
$searchBuilder
    ->setEndpoint('anime')
    ->addFilter('slug', 'naruto', SearchBuilder::FILTER_RANGE_GREATER_THAN)
    ->addOrder('slug', 'desc', SearchBuilder::ORDER_DIRECTION_DESC)
    ->addLimit(20)
    ->addOffset(0)
    ->search()
    ->get()
```

## Format of response

The data is returned in Json format and converted to an array of PHP 
objects or associative arrays.

If a single resource will be requested, it also will be returned in a collection. 

**Sample Response:**

```php
[
    0 => {
        "id": "1",
        "type": "anime",
        "links": {
            "self": "https://kitsu.io/api/edge/anime/1"
        },
        "attributes": {
            "createdAt": "2013-02-20T16:00:13.609Z",
            "updatedAt": "2020-07-26T13:18:42.333Z",
            "slug": "cowboy-bebop"
    },
    1 => {
        "id": "11",
        "type": "anime",
        "links": {
            "self": "https://kitsu.io/api/edge/anime/11"
        },
        "attributes": {
            "createdAt": "2013-02-20T16:00:24.797Z",
            "updatedAt": "2020-07-26T14:53:19.709Z",
            "slug": "naruto"
    }
]
```

## Kitsu API

- [Check docs](https://kitsu.docs.apiary.io/#introduction)

## Credits

- [s1njar](https://twitter.com/s1njar)

## License

The MIT License (MIT).
