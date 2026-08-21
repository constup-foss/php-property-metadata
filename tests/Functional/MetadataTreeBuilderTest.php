<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata\Tests\Functional;

use ConstupFoss\PhpPropertyMetadata\MetadataTreeBuilder;
use ConstupFoss\PhpPropertyMetadata\Tests\Functional\DataProvider\MetadataTreeBuilderDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class MetadataTreeBuilderTest extends TestCase
{
    #[DataProviderExternal(MetadataTreeBuilderDataProvider::class, 'provide_HappyFlow')]
    public function test_build_HappyFlow(
        callable $builderFactory,
        object $expected
    ): void {
        $builder = $builderFactory();

        $this->assertInstanceOf(MetadataTreeBuilder::class, $builder);
        $this->assertEquals($expected, $builder->build());
    }

    #[DataProviderExternal(MetadataTreeBuilderDataProvider::class, 'provide_ErrorFlow')]
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
