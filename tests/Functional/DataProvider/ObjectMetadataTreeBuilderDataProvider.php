<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata\Tests\Functional\DataProvider;

use ConstupFoss\PhpPropertyMetadata\Exceptions\MetadataTreeException;
use ConstupFoss\PhpPropertyMetadata\ObjectMetadataTreeBuilder;

readonly class ObjectMetadataTreeBuilderDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'Simple node metadata' => [
                'builderFactory' => static fn (): ObjectMetadataTreeBuilder => new ObjectMetadataTreeBuilder()
                    ->addNode('simplePropertyName', function (ObjectMetadataTreeBuilder $builder): void {
                        $builder->addMetadataNode('metadataKeyName', 'metadataKeyValue');
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'simplePropertyName' => (object)[
                            'metadataKeyName' => 'metadataKeyValue',
                        ],
                    ],
                ],
            ],
            'Nested node metadata' => [
                'builderFactory' => static fn (): ObjectMetadataTreeBuilder => new ObjectMetadataTreeBuilder()
                    ->addNode('parentPropertyName', function (ObjectMetadataTreeBuilder $builder): void {
                        $builder->addNode('childPropertyName', function (ObjectMetadataTreeBuilder $builder): void {
                            $builder->addMetadataNode('metadataKeyName', 'metadataKeyValue');
                        });
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'parentPropertyName' => (object)[
                            'childPropertyName' => (object)[
                                'metadataKeyName' => 'metadataKeyValue',
                            ],
                        ],
                    ],
                ],
            ],
            'Simple property metadata' => [
                'builderFactory' => static fn (): ObjectMetadataTreeBuilder => new ObjectMetadataTreeBuilder()
                    ->addPropertyNode('simplePropertyName', function (ObjectMetadataTreeBuilder $builder): void {
                        $builder->addMetadataNode('metadataKeyName', 'metadataKeyValue');
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'simplePropertyName' => (object)[
                            'metadataKeyName' => 'metadataKeyValue',
                        ],
                    ],
                ],
            ],
            'Nested property metadata' => [
                'builderFactory' => static fn (): ObjectMetadataTreeBuilder => new ObjectMetadataTreeBuilder()
                    ->addNode('parentPropertyName', function (ObjectMetadataTreeBuilder $builder): void {
                        $builder->addPropertyNode('childPropertyName', function (ObjectMetadataTreeBuilder $builder): void {
                            $builder->addMetadataNode('metadataKeyName', 'metadataKeyValue');
                        });
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'parentPropertyName' => (object)[
                            'childPropertyName' => (object)[
                                'metadataKeyName' => 'metadataKeyValue',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function provide_ErrorFlow(): array
    {
        return [
            'Creating property node with an empty name' => [
                'builderFactory' => static fn (): ObjectMetadataTreeBuilder => new ObjectMetadataTreeBuilder()
                    ->addNode(''),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1004,
            ],
            'Creating property node with invalid name. Invalid character.' => [
                'builderFactory' => static fn (): ObjectMetadataTreeBuilder => new ObjectMetadataTreeBuilder()
                    ->addNode('invalid#name'),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating property node with invalid name. White space.' => [
                'builderFactory' => static fn (): ObjectMetadataTreeBuilder => new ObjectMetadataTreeBuilder()
                    ->addNode('invalid name'),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating metadata node with an empty key' => [
                'builderFactory' => static fn (): ObjectMetadataTreeBuilder => new ObjectMetadataTreeBuilder()
                    ->addNode('parentNode', function (ObjectMetadataTreeBuilder $builder): void {
                        $builder->addMetadataNode('', null);
                    }),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1004,
            ],
            'Creating metadata node with invalid key. Invalid character.' => [
                'builderFactory' => static fn (): ObjectMetadataTreeBuilder => new ObjectMetadataTreeBuilder()
                    ->addNode('parentNode', function (ObjectMetadataTreeBuilder $builder): void {
                        $builder->addMetadataNode('invalid&key', null);
                    }),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating metadata node with invalid key. White space.' => [
                'builderFactory' => static fn (): ObjectMetadataTreeBuilder => new ObjectMetadataTreeBuilder()
                    ->addNode('parentNode', function (ObjectMetadataTreeBuilder $builder): void {
                        $builder->addMetadataNode('invalid key', null);
                    }),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1005,
            ],
        ];
    }
}
