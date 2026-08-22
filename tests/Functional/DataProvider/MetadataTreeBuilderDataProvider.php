<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata\Tests\Functional\DataProvider;

use ConstupFoss\PhpPropertyMetadata\Exceptions\MetadataTreeException;
use ConstupFoss\PhpPropertyMetadata\MetadataTreeBuilder;

readonly class MetadataTreeBuilderDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'Simple node metadata' => [
                'builderFactory' => static fn (): MetadataTreeBuilder => new MetadataTreeBuilder()
                    ->addNode('simplePropertyName', function (MetadataTreeBuilder $builder): void {
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
                'builderFactory' => static fn (): MetadataTreeBuilder => new MetadataTreeBuilder()
                    ->addNode('parentPropertyName', function (MetadataTreeBuilder $builder): void {
                        $builder->addNode('childPropertyName', function (MetadataTreeBuilder $builder): void {
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
            'Creating node with backslash in the name. Important for creating FQN nodes.' => [
                'builderFactory' => static fn (): MetadataTreeBuilder => new MetadataTreeBuilder()
                    ->addNode('property\\Name'),
                'expected' => (object)[
                    'root' => (object)[
                        'property\\Name' => (object)[],
                    ],
                ],
            ],
            /**
             * If a duplicate node name is used when building the tree, given that two duplicated nodes are in the same
             * scope and on the same depth, the second call will simply add elements to the already existing node.
             */
            'Duplicate node name.' => [
                'builderFactory' => static fn (): MetadataTreeBuilder => new MetadataTreeBuilder()
                    ->addNode('duplicate', function (MetadataTreeBuilder $builder): void {
                        $builder->addMetadataNode('firstMetadata', false);
                        $builder->addNode('firstSubnode');
                    })
                    ->addNode('sample', function (MetadataTreeBuilder $builder): void {
                        $builder->addMetadataNode('sampleMetadata', false);
                    })
                    ->addNode('duplicate', function (MetadataTreeBuilder $builder): void {
                        $builder->addMetadataNode('secondMetadata', false);
                        $builder->addNode('secondSubnode');
                        $builder->addMetadataNode('thirdMetadata', false);
                        $builder->addNode('duplicate');
                        $builder->addNode('firstSubnode', function (MetadataTreeBuilder $builder): void {
                            $builder->addMetadataNode('addsThisToSubnode', 42);
                        });
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'duplicate' => (object)[
                            'firstMetadata' => false,
                            'firstSubnode' => (object)[
                                'addsThisToSubnode' => 42,
                            ],
                            'secondMetadata' => false,
                            'secondSubnode' => (object)[],
                            'thirdMetadata' => false,
                            'duplicate' => (object)[],
                        ],
                        'sample' => (object)[
                            'sampleMetadata' => false,
                        ],
                    ]
                ],
            ],
        ];
    }

    public static function provide_ErrorFlow(): array
    {
        return [
            'Creating node with an empty name' => [
                'builderFactory' => static fn (): MetadataTreeBuilder => new MetadataTreeBuilder()
                    ->addNode(''),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1004,
            ],
            'Creating node with invalid name. Invalid character.' => [
                'builderFactory' => static fn (): MetadataTreeBuilder => new MetadataTreeBuilder()
                    ->addNode('invalid#name'),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating node with invalid name. White space.' => [
                'builderFactory' => static fn (): MetadataTreeBuilder => new MetadataTreeBuilder()
                    ->addNode('invalid name'),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating metadata node with an empty key' => [
                'builderFactory' => static fn (): MetadataTreeBuilder => new MetadataTreeBuilder()
                    ->addNode('parentNode', function (MetadataTreeBuilder $builder): void {
                        $builder->addMetadataNode('', null);
                    }),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1004,
            ],
            'Creating metadata node with invalid key. Invalid character.' => [
                'builderFactory' => static fn (): MetadataTreeBuilder => new MetadataTreeBuilder()
                    ->addNode('parentNode', function (MetadataTreeBuilder $builder): void {
                        $builder->addMetadataNode('invalid&key', null);
                    }),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating metadata node with invalid key. White space.' => [
                'builderFactory' => static fn (): MetadataTreeBuilder => new MetadataTreeBuilder()
                    ->addNode('parentNode', function (MetadataTreeBuilder $builder): void {
                        $builder->addMetadataNode('invalid key', null);
                    }),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1005,
            ],
        ];
    }
}
