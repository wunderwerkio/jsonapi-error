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

  /**
   * Test that the error can be instantiated.
   */
  #[Test]
  public function canBeInstanciated(): void {
    $this->assertInstanceOf(JsonApiError::class, new JsonApiError(
      status: 400,
    ));

    $this->assertInstanceOf(JsonApiError::class, JsonApiError::fromArray([
      'status' => 400,
    ]));
  }

  /**
   * Test that the error can not be created without fields.
   */
  #[Test]
  public function canNotBeCreatedWithoutFields(): void {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessageMatches('/Error must have at least one of the following fields: .*/');

    new JsonApiError();
  }

  /**
   * Test that the error can not be created without fields from an array.
   */
  #[Test]
  public function canNotBeCreatedWithoutFieldsFromArray(): void {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessageMatches('/Error must have at least one of the following fields: .*/');

    JsonApiError::fromArray([]);
  }

  /**
   * Test that the error can be created with status.
   */
  #[Test]
  public function canBeCreatedWithStatus(): void {
    $error = JsonApiError::fromArray([
      'status' => 400,
    ]);

    $this->assertEquals(400, $error->getStatus());
  }

  /**
   * Test that the error can be created with id.
   */
  #[Test]
  public function canBeCreatedWithId(): void {
    $error = JsonApiError::fromArray([
      'id' => '1',
    ]);

    $this->assertEquals('1', $error->getId());
  }

  /**
   * Test that the error can be created with links.
   */
  #[Test]
  public function canBeCreatedWithLinks(): void {
    $error = JsonApiError::fromArray([
      'links' => ['about' => 'http://example.com'],
    ]);

    $this->assertEquals(['about' => 'http://example.com'], $error->getLinks());
  }

  /**
   * Test that the error can be created with code.
   */
  #[Test]
  public function canBeCreatedWithCode(): void {
    $error = JsonApiError::fromArray([
      'code' => '400',
    ]);

    $this->assertEquals('400', $error->getCode());
  }

  /**
   * Test that the error can be created with source.
   */
  #[Test]
  public function canBeCreatedWithSource(): void {
    $error = JsonApiError::fromArray([
      'source' => ['pointer' => '/data/attributes/first-name'],
    ]);

    $this->assertEquals(['pointer' => '/data/attributes/first-name'], $error->getSource());
  }

  /**
   * Test that the error can be created with title.
   */
  #[Test]
  public function canBeCreatedWithTitle(): void {
    $error = JsonApiError::fromArray([
      'title' => 'Some title',
    ]);

    $this->assertEquals('Some title', $error->getTitle());
  }

  /**
   * Test that the error can be created with detail.
   */
  #[Test]
  public function canBeCreatedWithDetail(): void {
    $error = JsonApiError::fromArray([
      'detail' => 'Some detail',
    ]);

    $this->assertEquals('Some detail', $error->getDetail());
  }

  /**
   * Test that the error can be created with meta.
   */
  #[Test]
  public function canBeCreatedWithMeta(): void {
    $error = JsonApiError::fromArray([
      'meta' => ['foo' => 'bar'],
    ]);

    $this->assertEquals(['foo' => 'bar'], $error->getMeta());
  }

  /**
   * Test that the error can be created with all fields.
   */
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

  /**
   * Test that the error can be created from an array.
   */
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
