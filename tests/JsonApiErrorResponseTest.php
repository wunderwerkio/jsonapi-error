<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Wunderwerk\JsonApiError\JsonApiError;
use Wunderwerk\JsonApiError\JsonApiErrorResponse;

/**
 * Test the JsonApiErrorResponse class.
 */
#[CoversClass(JsonApiErrorResponse::class)]
#[UsesClass(JsonApiError::class)]
final class JsonApiErrorResponseTest extends TestCase {

  /**
   * Test that the response can be instantiated with a single error.
   */
  #[Test]
  public function canBeInstanciatedWithSingleError(): void {
    $response = new JsonApiErrorResponse([
      JsonApiError::fromArray([
        'status' => 400,
      ]),
    ]);

    $this->assertEquals(json_encode([
      'jsonapi' => [
        'version' => '1.0',
        'meta' => [
          'links' => [
            'self' => [
              'href' => 'http://jsonapi.org/format/1.0/',
            ],
          ],
        ],
      ],
      'errors' => [
        [
          'status' => '400',
        ],
      ],
    ]), $response->getContent());

    $this->assertInstanceOf(JsonApiErrorResponse::class, $response);
  }

  /**
   * Test that the response can be instantiated with multiple errors.
   */
  #[Test]
  public function canBeInstanciatedWithMultipleErrors(): void {
    $response = new JsonApiErrorResponse([
      JsonApiError::fromArray([
        'status' => 400,
      ]),
      JsonApiError::fromArray([
        'status' => 500,
      ]),
    ]);

    $this->assertEquals(json_encode([
      'jsonapi' => [
        'version' => '1.0',
        'meta' => [
          'links' => [
            'self' => [
              'href' => 'http://jsonapi.org/format/1.0/',
            ],
          ],
        ],
      ],
      'errors' => [
        [
          'status' => '400',
        ],
        [
          'status' => '500',
        ],
      ],
    ]), $response->getContent());

    $this->assertInstanceOf(JsonApiErrorResponse::class, $response);
  }

  /**
   * Test that the response can be created from an error.
   */
  #[Test]
  public function canBeCreatedFromError(): void {
    $response = JsonApiErrorResponse::fromError(
      code: 'test',
      title: 'Test',
      status: 400,
    );

    $this->assertEquals(json_encode([
      'jsonapi' => [
        'version' => '1.0',
        'meta' => [
          'links' => [
            'self' => [
              'href' => 'http://jsonapi.org/format/1.0/',
            ],
          ],
        ],
      ],
      'errors' => [
        [
          'status' => '400',
          'code' => 'test',
          'title' => 'Test',
        ],
      ],
    ]), $response->getContent());

    $this->assertInstanceOf(JsonApiErrorResponse::class, $response);
  }

  /**
   * Test that the response can be created from an array.
   */
  #[Test]
  public function canBeCreatedFromArray(): void {
    $response = JsonApiErrorResponse::fromArray([
      'code' => 'test',
      'title' => 'Test',
      'status' => 400,
    ]);

    $this->assertEquals(json_encode([
      'jsonapi' => [
        'version' => '1.0',
        'meta' => [
          'links' => [
            'self' => [
              'href' => 'http://jsonapi.org/format/1.0/',
            ],
          ],
        ],
      ],
      'errors' => [
        [
          'status' => '400',
          'code' => 'test',
          'title' => 'Test',
        ],
      ],
    ]), $response->getContent());

    $this->assertInstanceOf(JsonApiErrorResponse::class, $response);
  }

  /**
   * Test that the response can be created from multiple arrays.
   */
  #[Test]
  public function canBeCreatedFromArrayMultiple(): void {
    $response = JsonApiErrorResponse::fromArrayMultiple([
      [
        'code' => 'test',
        'title' => 'Test',
        'status' => 400,
      ],
      [
        'code' => 'test2',
        'title' => 'Test2',
        'status' => 500,
      ],
    ]);

    $this->assertEquals(json_encode([
      'jsonapi' => [
        'version' => '1.0',
        'meta' => [
          'links' => [
            'self' => [
              'href' => 'http://jsonapi.org/format/1.0/',
            ],
          ],
        ],
      ],
      'errors' => [
        [
          'status' => '400',
          'code' => 'test',
          'title' => 'Test',
        ],
        [
          'status' => '500',
          'code' => 'test2',
          'title' => 'Test2',
        ],
      ],
    ]), $response->getContent());

    $this->assertInstanceOf(JsonApiErrorResponse::class, $response);
  }

  /**
   * Test creation with errors with multiple status codes.
   */
  #[Test]
  public function canBeCreatedWithErrorsWithMultipleStatusCodes(): void {
    $response = new JsonApiErrorResponse([
      JsonApiError::fromArray([
        'status' => 400,
      ]),
      JsonApiError::fromArray([
        'status' => 500,
      ]),
    ]);

    $this->assertEquals(500, $response->getStatusCode());
  }

  /**
   * Test creation with errors with multiple status codes and no 500s.
   */
  #[Test]
  public function canBeCreatedWithErrorsWithMultipleStatusCodesAndNo500s(): void {
    $response = new JsonApiErrorResponse([
      JsonApiError::fromArray([
        'status' => 400,
      ]),
      JsonApiError::fromArray([
        'status' => 404,
      ]),
    ]);

    $this->assertEquals(400, $response->getStatusCode());
  }

  /**
   * Test creation with errors with multiple status codes and no 400s.
   */
  #[Test]
  public function canBeCreatedWithErrorsWithMultipleStatusCodesAndNo400s(): void {
    $response = new JsonApiErrorResponse([
      JsonApiError::fromArray([
        'status' => 501,
      ]),
      JsonApiError::fromArray([
        'status' => 504,
      ]),
    ]);

    $this->assertEquals(500, $response->getStatusCode());
  }

}
