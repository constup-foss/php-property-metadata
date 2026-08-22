<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata\Tests\Unit;

use ConstupFoss\PhpPropertyMetadata\MetadataService;
use ConstupFoss\PhpPropertyMetadata\Tests\Unit\DataProvider\MetadataService\GetValueFromPathDataProvider;
use ConstupFoss\PhpPropertyMetadata\Tests\Unit\DataProvider\MetadataService\HasPathDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use stdClass;

class MetadataServiceTest extends TestCase
{
    #[DataProviderExternal(
        GetValueFromPathDataProvider::class,
        'provide_HappyFlow'
    )]
    public function test_getByPath_HappyFlow(
        array|stdClass $attributeArguments,
        string         $path,
        mixed          $expected
    ): void {
        $class = new MetadataService();
        $result = $class->getValueFromPath($attributeArguments, $path);

        $this->assertEquals($expected, $result);
    }

    #[DataProviderExternal(
        GetValueFromPathDataProvider::class,
        'provide_ErrorFlow'
    )]
    public function test_getByPath_ErrorFlow(
        array|stdClass $attributeArguments,
        string         $path,
        string         $expectedException,
        int            $expectedExceptionCode
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionCode($expectedExceptionCode);

        $class = new MetadataService();
        $class->getValueFromPath($attributeArguments, $path);
    }

    #[DataProviderExternal(
        HasPathDataProvider::class,
        'provide_HappyFlow'
    )]
    public function test_hasPath_HappyFlow(
        array|stdClass $attributeArguments,
        string         $path,
        bool           $expected
    ): void {
        $class = new MetadataService();
        $result = $class->hasPath($attributeArguments, $path);

        $this->assertEquals($expected, $result);
    }
}
