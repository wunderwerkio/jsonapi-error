<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Wunderwerk\JsonApiError\JsonApiError;

/**
 * Test The JsonApiError class.
 */
#[CoversClass(JsonApiError::class)]
final class JsonApiErrorTest extends TestCase {

  #[Test]
  public function canBeInstanciated(): void {
    $this->assertInstanceOf(JsonApiError::class, new JsonApiError(
      status: 400,
    ));

    $this->assertInstanceOf(JsonApiError::class, JsonApiError::fromArray([
      'status' => 400,
    ]));
  }

  #[Test]
  public function canNotBeCreatedWithoutFields(): void {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessageMatches('/Error must have at least one of the following fields: .*/');

    new JsonApiError();
  }

  #[Test]
  public function canNotBeCreatedWithoutFieldsFromArray(): void {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessageMatches('/Error must have at least one of the following fields: .*/');

    JsonApiError::fromArray([]);
  }

  #[Test]
  public function canBeCreatedWithStatus(): void {
    $error = JsonApiError::fromArray([
      'status' => 400,
    ]);

    $this->assertEquals(400, $error->getStatus());
  }

  #[Test]
  public function canBeCreatedWithId(): void {
    $error = JsonApiError::fromArray([
      'id' => '1',
    ]);

    $this->assertEquals('1', $error->getId());
  }

  #[Test]
  public function canBeCreatedWithLinks(): void {
    $error = JsonApiError::fromArray([
      'links' => ['about' => 'http://example.com'],
    ]);

    $this->assertEquals(['about' => 'http://example.com'], $error->getLinks());
  }

  #[Test]
  public function canBeCreatedWithCode(): void {
    $error = JsonApiError::fromArray([
      'code' => '400',
    ]);

    $this->assertEquals('400', $error->getCode());
  }

  #[Test]
  public function canBeCreatedWithSource(): void {
    $error = JsonApiError::fromArray([
      'source' => ['pointer' => '/data/attributes/first-name'],
    ]);

    $this->assertEquals(['pointer' => '/data/attributes/first-name'], $error->getSource());
  }

  #[Test]
  public function canBeCreatedWithTitle(): void {
    $error = JsonApiError::fromArray([
      'title' => 'Some title',
    ]);

    $this->assertEquals('Some title', $error->getTitle());
  }

  #[Test]
  public function canBeCreatedWithDetail(): void {
    $error = JsonApiError::fromArray([
      'detail' => 'Some detail',
    ]);

    $this->assertEquals('Some detail', $error->getDetail());
  }

  #[Test]
  public function canBeCreatedWithMeta(): void {
    $error = JsonApiError::fromArray([
      'meta' => ['foo' => 'bar'],
    ]);

    $this->assertEquals(['foo' => 'bar'], $error->getMeta());
  }

  #[Test]
  public function canBeCreatedWithAllFields(): void {
    $error = JsonApiError::fromArray([
      'status' => 400,
      'id' => '1',
      'links' => ['about' => 'http://example.com'],
      'code' => '400',
      'source' => ['pointer' => '/data/attributes/first-name'],
      'title' => 'Some title',
      'detail' => 'Some detail',
      'meta' => ['foo' => 'bar'],
    ]);

    $this->assertEquals(400, $error->getStatus());
    $this->assertEquals('1', $error->getId());
    $this->assertEquals(['about' => 'http://example.com'], $error->getLinks());
    $this->assertEquals('400', $error->getCode());
    $this->assertEquals(['pointer' => '/data/attributes/first-name'], $error->getSource());
    $this->assertEquals('Some title', $error->getTitle());
    $this->assertEquals('Some detail', $error->getDetail());
    $this->assertEquals(['foo' => 'bar'], $error->getMeta());
  }

  #[Test]
  public function canBeCreatedFromArray(): void {
    $error = JsonApiError::fromArray([
      'status' => 400,
      'id' => '1',
      'links' => ['about' => 'http://example.com'],
      'code' => '400',
      'source' => ['pointer' => '/data/attributes/first-name'],
      'title' => 'Some title',
      'detail' => 'Some detail',
      'meta' => ['foo' => 'bar'],
    ]);

    $this->assertEquals([
      'status' => 400,
      'id' => '1',
      'links' => ['about' => 'http://example.com'],
      'code' => '400',
      'source' => ['pointer' => '/data/attributes/first-name'],
      'title' => 'Some title',
      'detail' => 'Some detail',
      'meta' => ['foo' => 'bar'],
    ], $error->toArray());
  }

}
