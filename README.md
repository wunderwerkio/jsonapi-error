# JSON:API Error 
[![Test](https://github.com/wunderwerkio/jsonapi-error/actions/workflows/main.yml/badge.svg)](https://github.com/wunderwerkio/jsonapi-error/actions/workflows/main.yml)

This package provides `JsonApiError` and `JsonApiErrorResponse` classses to conveniently handle errors following the [JSON:API specification](https://jsonapi.org/format/#errors).

The `JsonApiErrorResponse` extends the `JsonResponse` from `symfony/http-foundation`, so this package is meant to be used in projects using that.

**Table of contents:**
- [Install](#install)
- [Usage](#usage)
    - [Return a simple error response](#return-a-simple-error-response)
    - [Return multiple errors](#return-multiple-errors)
    - [Build response from `JsonApiError` objects](#build-response-from-jsonapierror-objects)
- [Local Development](#local-development)
- [Credits](#credits)

## Install

Install this package via composer:

```bash
composer require wunderwerkio/jsonapi-error
```

## Usage

### Return a simple error response

```php
<?php

use Symfony\Component\HttpFoundation\Response;
use Wunderwerk\JsonApiError\JsonApiErrorResponse;

function someRequestHandler(): Response {
  return JsonApiErrorResponse::fromArray([
    'code' => 'application_error_code',
    'title' => 'An error occured!',
    'status' => 500,
  ]);
}
```

The above code would result in a JSON response with the following payload:

```json
{
  "errors": [{
    "status": 500,
    "code": "application_error_code",
    "title": "An error occured!"
  }]
}
```

### Return multiple errors

```php
<?php

use Symfony\Component\HttpFoundation\Response;
use Wunderwerk\JsonApiError\JsonApiErrorResponse;

function someRequestHandler(): Response {
  return JsonApiErrorResponse::fromArrayMultiple([
    [
      'status' => 422,
      'code' => 'validation_failed',
      'title' => 'Invalid request payload',
      'detail' => 'The "name" field is required.',
      'source' => [
        'pointer' => '/data/name'
      ]
    ],
    [
      'status' => 422,
      'code' => 'validation_failed',
      'title' => 'Invalid request payload',
      'detail' => 'The "description" field is required.',
      'source' => [
        'pointer' => '/data/description'
      ]
    ],
  ]);
}
```

The above code would result in a JSON response with the following payload:

```json
{
  "errors": [{
    "status": 422,
    "code": "validation_failed",
    "title": "Invalid request payload",
    "detail": "The \"name\" field is required.",
    "source": {
      "pointer": "/data/name"
    }
  }, {
    "status": 422,
    "code": "validation_failed",
    "title": "Invalid request payload",
    "detail": "The \"description\" field is required.",
    "source": {
      "pointer": "/data/description"
    }
  }]
}
```

### Build response from `JsonApiError` objects

To ease building a response with multiple errors, the response can also be created by constricting it by
passing an array of `JsonApiError` objects.

```php
<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Wunderwerk\JsonApiError\JsonApiError;
use Wunderwerk\JsonApiError\JsonApiErrorResponse;

function someRequestHandler(Request $request): Response {
  /** @var JsonApiError[] $errors */
  $errors = [];

  $payload = $request->getContent();
  $entity = json_decode($payload, TRUE);

  // Make sure 'name' field is set.
  if (!array_key_exists('name', $entity['data'])) {
    $errors[] = JsonApiError::fromArray([
      'status' => 422,
      'code' => 'validation_failed',
      'title' => 'Invalid request payload',
      'detail' => 'The "name" field is required.',
      'source' => [
        'pointer' => '/data/name',
      ],
    ]);
  }

  // Make sure 'description' field is set.
  if (!array_key_exists('description', $entity['data'])) {
    $errors[] = JsonApiError::fromArray([
      'status' => 422,
      'code' => 'validation_failed',
      'title' => 'Invalid request payload',
      'detail' => 'The "description" field is required.',
      'source' => [
        'pointer' => '/data/description',
      ],
    ]);
  }

  if (!empty($errors)) {
    return new JsonApiErrorResponse($errors);
  }

  return new JsonResponse([
    'status' => 'success',
  ]);
}
```

## Local Development

A local dev environment without the need to have PHP locally installed can be done via [DDEV](https://ddev.readthedocs.io).

```bash
ddev start
```

**Run tests**

```bash
ddev composer test
```

**Lint with PHPStan**

```bash
ddev composer analyze
```

## Credits

This project took inspiration from the following awesome projects:

- [`SineMah/json-api-error`](https://github.com/SineMah/json-api-error)
