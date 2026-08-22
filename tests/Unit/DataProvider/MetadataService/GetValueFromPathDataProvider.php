<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata\Tests\Unit\DataProvider\MetadataService;

use ConstupFoss\PhpPropertyMetadata\Exceptions\MetadataTreeException;

readonly class GetValueFromPathDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'Object context. Valid object path.' => [
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'foo' => (object)[
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => 'root->foo',
                'expected' => (object)[
                    'bar' => 'baz',
                ],
            ],
            'Object context. Valid array path.' => [
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'foo' => [
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => 'root->foo',
                'expected' => [
                    'bar' => 'baz',
                ],
            ],
            'Object context. Deeply nested path.' => [
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'foo' => (object)[
                            'bar' => [
                                'baz' => (object)[
                                    'fee' => 'fie',
                                ],
                            ],
                        ],
                    ],
                ],
                'path' => 'root->foo->bar->baz->fee',
                'expected' => 'fie',
            ],
            'Array context. Valid path.' => [
                'attributeArguments' => [
                    'root' => [
                        'foo' => [
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => 'root->foo',
                'expected' => [
                    'bar' => 'baz',
                ],
            ],
            'Array context. Deeply nested path.' => [
                'attributeArguments' => [
                    'root' => [
                        'foo' => [
                            'bar' => [
                                'baz' => [
                                    'fee' => 'fie',
                                ],
                            ],
                        ],
                    ],
                ],
                'path' => 'root->foo->bar->baz->fee',
                'expected' => 'fie',
            ],
        ];
    }

    public static function provide_ErrorFlow(): array
    {
        return [
            'Path is empty' => [
                'attributeArguments' => [
                    'root' => [
                        'foo' => [
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => '',
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1001,
            ],
            'Path contains an empty segment' => [
                'attributeArguments' => [
                    'foo' => [
                        'bar' => 'baz',
                    ],
                ],
                'path' => 'foo->->bar',
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1002,
            ],
            'Path not found in object context. Object path.' => [
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'foo' => (object)[],
                    ],
                ],
                'path' => 'root->baz',
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1000,
            ],
            'Path not found in object context. Array path.' => [
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'foo' => [
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => 'root->foo->bar->fee',
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1000,
            ],
            'Path not found in array context.' => [
                'attributeArguments' => [
                    'root' => [
                        'foo' => [],
                    ],
                ],
                'path' => 'root->baz',
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1000,
            ],
            'Path contains a missing segment.' => [
                'attributeArguments' => [
                    'root' => [
                        'foo' => [
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => 'root->fee->bar',
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1000,
            ],
            'Context is empty' => [
                'attributeArguments' => [],
                'path' => 'root->foo->bar',
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1003,
            ],
        ];
    }
}
