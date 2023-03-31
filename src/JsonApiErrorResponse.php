<?php

declare(strict_types=1);

namespace Wunderwerk\JsonApiError;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * A JSON API error response.
 */
class JsonApiErrorResponse extends JsonResponse {

  /**
   * JsonApiErrorResponse constructor.
   *
   * @param \Wunderwerk\JsonApiError\JsonApiError[] $errors
   *   The errors to be returned.
   * @param array<string, mixed> $headers
   *   An array of HTTP headers.
   * @param bool $json
   *   Whether the response body should be JSON encoded.
   */
  public function __construct(
    protected readonly array $errors,
    array $headers = [],
    bool $json = FALSE,
  ) {
    parent::__construct(
      $this->createResponseArray(), 
      $this->inferStatus(),
      $headers,
      $json,
    );
  }

  /**
   * Get the error response data.
   *
   * @return array{'errors': array<string, mixed>}
   *   The response data.
   */
  protected function createResponseArray(): array {
    return [
      'errors' => array_map(fn(JsonApiError $error) => $error->toArray(), $this->errors),
    ];
  }

  /**
   * Infers status code from errors.
   *
   * If all errors have the same status code, that status code is returned.
   * Otherwise if all errors have a 4xx status code, 400 is returned.
   * Otherwise if all errors have a 5xx status code, 500 is returned.
   */
  protected function inferStatus(): int {
    /** @var int[] $statusCodes */
    $statusCodes = array_unique(array_map(fn(JsonApiError $error) => $error->getStatus(), $this->errors));

    // Same code for all errors.
    if (count($statusCodes) === 1) {
      return reset($statusCodes);
    }

    // All 4xx errors.
    if (array_reduce($statusCodes, fn(bool $carry, int $statusCode) => $carry && $statusCode >= 400 && $statusCode < 500, TRUE)) {
      return 400;
    }

    // All 5xx errors.
    if (array_reduce($statusCodes, fn(bool $carry, int $statusCode) => $carry && $statusCode >= 500 && $statusCode < 600, TRUE)) {
      return 500;
    }

    // No common status code.
    return 500;
  }

  /**
   * Create a JSON API error response from a single error.
   *
   * @param array<string, mixed> $error
   *   The error data.
   * @param array<string, mixed> $headers
   *   An array of HTTP headers.
   * @param bool $json
   *   Whether the response body should be JSON encoded.
   *
   * @return \Wunderwerk\JsonApiError\JsonApiErrorResponse
   *   The error response.
   */
  public static function fromArray(
    array $error, 
    array $headers = [],
    bool $json = FALSE,
  ): JsonApiErrorResponse {
    return new JsonApiErrorResponse([JsonApiError::fromArray($error)], $headers, $json);
  }

  /**
   * Create a JSON API error response from multiple errors.
   *
   * @param array<string, mixed>[] $errors
   *   The error data.
   * @param array<string, mixed> $headers
   *   An array of HTTP headers.
   * @param bool $json
   *   Whether the response body should be JSON encoded.
   *
   * @return \Wunderwerk\JsonApiError\JsonApiErrorResponse
   *   The error response.
   */
  public static function fromArrayMultiple(
    array $errors,
    array $headers = [],
    bool $json = FALSE,
  ): JsonApiErrorResponse {
    return new JsonApiErrorResponse(
      array_map(fn(array $error) => JsonApiError::fromArray($error), $errors),
      $headers,
      $json,
    );
  }

}
