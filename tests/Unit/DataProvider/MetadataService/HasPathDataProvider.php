<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata\Tests\Unit\DataProvider\MetadataService;

readonly class HasPathDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
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
                'expected' => true,
            ],
            'Object context. Invalid path segment.' => [
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'foo' => (object)[
                            'bar' => [
                                'baz' => 'fee',
                            ],
                        ],
                    ],
                ],
                'path' => 'root->foo->fie->baz->fee',
                'expected' => false,
            ],
            'Object context. Missing path segment.' => [
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'foo' => (object)[
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => 'root->foo->->bar->baz',
                'expected' => false,
            ],
            'Object context. Missing leaf segment.' => [
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'foo' => (object)[
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => 'root->foo->fee',
                'expected' => false,
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
                'expected' => true,
            ],
            'Path is empty.' => [
                'attributeArguments' => [],
                'path' => '',
                'expected' => false,
            ],
        ];
    }
}
