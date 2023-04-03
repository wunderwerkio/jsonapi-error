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
   * Create a new JSON API error response with a single error.
   *
   * The use of named parameters is highly recommended!
   * Alternatively ::fromArray can be used instead.
   *
   * @param int|null $status
   *   The HTTP status code applicable to this problem.
   * @param string|null $id
   *   A unique identifier for this particular occurrence of the problem.
   * @param string[]|null $links
   *   A links object containing the following members:
   *   - about: a link that leads to further details about this particular
   *     occurrence of the problem.
   * @param string|null $code
   *   An application-specific error code, expressed as a string value.
   * @param array{'pointer'?: string, 'parameter'?: string}|null $source
   *   An object containing references to the source of the error, optionally
   *   including any of the following members:
   *   - pointer: a JSON Pointer [RFC6901] to the associated entity in the
   *     request document [e.g. "/data" for a primary data object, or
   *     "/data/attributes/title" for a specific attribute].
   *   - parameter: a string indicating which query parameter caused the error.
   * @param string|null $title
   *   A short, human-readable summary of the problem that SHOULD NOT change
   *   from occurrence to occurrence of the problem, except for purposes of
   *   localization.
   * @param string|null $detail
   *   A human-readable explanation specific to this occurrence of the problem.
   *   Like title, this field’s value can be localized.
   * @param mixed[]|null $meta
   *   A meta object containing non-standard meta-information about the error.
   * @param array<string, mixed> $headers
   *   An array of HTTP headers.
   * @param bool $json
   *   Whether the response body should be JSON encoded.
   */
  public static function fromError(
    ?int $status = NULL,
    ?string $id = NULL,
    ?array $links = NULL,
    ?string $code = NULL,
    ?array $source = NULL,
    ?string $title = NULL,
    ?string $detail = NULL,
    ?array $meta = NULL,
    array $headers = [],
    bool $json = FALSE,
  ): JsonApiErrorResponse {
    $error = new JsonApiError(
      $status,
      $id,
      $links,
      $code,
      $source,
      $title,
      $detail,
      $meta,
    );

    return new self([$error], $headers, $json);
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
