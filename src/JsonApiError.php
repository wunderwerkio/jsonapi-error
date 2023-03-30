<?php

declare(strict_types=1);

namespace Wunderwerk\JsonApiError;

/**
 * Represents a JSON API error.
 *
 * @see https://jsonapi.org/format/#error-objects
 */
class JsonApiError {

  /**
   * List of valid error fields.
   *
   * @var array
   *
   * @see https://jsonapi.org/format/#error-objects
   */
  const ERROR_FIELDS = ['id', 'links', 'status', 'code', 'source', 'title', 'detail', 'meta'];

  /**
   * JsonApiError constructor.
   *
   * @internal
   * @param int|null $status
   *   The HTTP status code applicable to this problem.
   * @param string|null $id
   *   A unique identifier for this particular occurrence of the problem.
   * @param array|null $links
   *   A links object containing the following members:
   *   - about: a link that leads to further details about this particular
   *     occurrence of the problem.
   * @param string|null $code
   *   An application-specific error code, expressed as a string value.
   * @param array|null $source
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
   * @param array|null $meta
   *   A meta object containing non-standard meta-information about the error.
   */
  protected function __construct(
    protected readonly ?int $status,
    protected readonly ?string $id,
    protected readonly ?array $links,
    protected readonly ?string $code,
    protected readonly ?array $source,
    protected readonly ?string $title,
    protected readonly ?string $detail,
    protected readonly ?array $meta,
  ) { }

  /**
   * Get the HTTP status code applicable to this problem.
   *
   * @return int|null
   *   The HTTP status code.
   */
  public function getStatus(): ?int {
    return $this->status;
  }

  /**
   * Get a unique identifier for this particular occurrence of the problem.
   *
   * @return string|null
   *   The error id.
   */
  public function getId(): ?string {
    return $this->id;
  }

  /**
   * Get a links object containing the following members:
   * - about: a link that leads to further details about this particular
   *   occurrence of the problem.
   *
   * @return array|null
   *   The links object.
   */
  public function getLinks(): ?array {
    return $this->links;
  }

  /**
   * Get an application-specific error code, expressed as a string value.
   *
   * @return string|null
   *   The error code.
   */
  public function getCode(): ?string {
    return $this->code;
  }

  /**
   * Get an object containing references to the source of the error.
   *
   * @return array|null
   *   The source object.
   */
  public function getSource(): ?array {
    return $this->source;
  }

  /**
   * Get a short, human-readable summary of the problem.
   *
   * @return string|null
   *   The error title.
   */
  public function getTitle(): ?string {
    return $this->title;
  }

  /**
   * Get a human-readable explanation specific to this occurrence of the problem.
   *
   * @return string|null
   *   The error detail.
   */
  public function getDetail(): ?string {
    return $this->detail;
  }

  /**
   * Get a meta object containing non-standard meta-information about the error.
   *
   * @return array|null
   *   The meta object.
   */
  public function getMeta(): ?array {
    return $this->meta;
  }

  /**
   * Convert the JsonApiError object to an array.
   *
   * @return array
   *   The error array.
   */
  public function toArray(): array {
    return array_filter(
      [
        'id' => $this->id,
        'links' => $this->links,
        'status' => $this->status,
        'code' => $this->code,
        'source' => $this->source,
        'title' => $this->title,
        'detail' => $this->detail,
        'meta' => $this->meta,
      ],
      fn($value) => !is_null($value),
    );
  }

  /**
   * Create a JsonApiError object from an array.
   *
   * @param array $error
   *   The error array.
   *
   * @return JsonApiError
   *   The JsonApiError object.
   *
   * @throws \InvalidArgumentException
   *   Thrown if the error array does not contain at least one valid field.
   */
  public static function fromArray(array $error): JsonApiError {
    $error = array_intersect_key($error, array_flip(self::ERROR_FIELDS));

    if (empty($error)) {
      throw new \InvalidArgumentException('Error must have at least one of the following fields: ' . implode(', ', self::ERROR_FIELDS));

    }

    return new JsonApiError(
      $error['status'] ?? null,
      $error['id'] ?? null,
      $error['links'] ?? null,
      $error['code'] ?? null,
      $error['source'] ?? null,
      $error['title'] ?? null,
      $error['detail'] ?? null,
      $error['meta'] ?? null,
    );
  }

}
