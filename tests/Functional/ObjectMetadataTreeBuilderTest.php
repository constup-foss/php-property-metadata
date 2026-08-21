<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata\Tests\Functional;

use ConstupFoss\PhpPropertyMetadata\ObjectMetadataTreeBuilder;
use ConstupFoss\PhpPropertyMetadata\Tests\Functional\DataProvider\ObjectMetadataTreeBuilderDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class ObjectMetadataTreeBuilderTest extends TestCase
{
    #[DataProviderExternal(ObjectMetadataTreeBuilderDataProvider::class, 'provide_HappyFlow')]
    public function test_build_HappyFlow(
        callable $builderFactory,
        object $expected
    ): void {
        $builder = $builderFactory();

        $this->assertInstanceOf(ObjectMetadataTreeBuilder::class, $builder);
        $this->assertEquals($expected, $builder->build());
    }

    #[DataProviderExternal(ObjectMetadataTreeBuilderDataProvider::class, 'provide_ErrorFlow')]
    public function test_build_ErrorFlow(
        callable $builderFactory,
        string $expectedException,
        int $expectedExceptionCode
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionCode($expectedExceptionCode);

        $builderFactory();
    }
}
