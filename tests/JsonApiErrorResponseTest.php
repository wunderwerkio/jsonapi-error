<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Wunderwerk\JsonApiError\JsonApiError;
use Wunderwerk\JsonApiError\JsonApiErrorResponse;

#[CoversClass(JsonApiErrorResponse::class)]
#[UsesClass(JsonApiError::class)]
final class JsonApiErrorResponseTest extends TestCase {

  #[Test]
  public function canBeInstanciatedWithSingleError(): void {
    $response = new JsonApiErrorResponse([
      JsonApiError::fromArray([
        'status' => 400,
      ]),
    ]);

    $this->assertEquals(json_encode([
      'errors' => [
        [
          'status' => 400,
        ],
      ],
    ]), $response->getContent());

    $this->assertInstanceOf(JsonApiErrorResponse::class, $response);
  }

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
      'errors' => [
        [
          'status' => 400,
        ],
        [
          'status' => 500,
        ],
      ],
    ]), $response->getContent());

    $this->assertInstanceOf(JsonApiErrorResponse::class, $response);
  }

  #[Test]
  public function canBeCreatedFromArray(): void {
    $response = JsonApiErrorResponse::fromArray([
      'code' => 'test',
      'title' => 'Test',
      'status' => 400,
    ]);

    $this->assertEquals(json_encode([
      'errors' => [
        [
          'status' => 400,
          'code' => 'test',
          'title' => 'Test',
        ],
      ],
    ]), $response->getContent());

    $this->assertInstanceOf(JsonApiErrorResponse::class, $response);
  }

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
      'errors' => [
        [
          'status' => 400,
          'code' => 'test',
          'title' => 'Test',
        ],
        [
          'status' => 500,
          'code' => 'test2',
          'title' => 'Test2',
        ],
      ],
    ]), $response->getContent());

    $this->assertInstanceOf(JsonApiErrorResponse::class, $response);
  }

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
