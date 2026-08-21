<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata;

use ConstupFoss\PhpPropertyMetadata\Exceptions\MetadataTreeException;
use stdClass;

interface MetadataServiceInterface
{
    public const string SEPARATOR = '->';

    /**
     * Retrieves the value from a metadata tree based on the provided path.
     *
     * This method traverses a metadata tree (as array or stdClass object) using a path string containing keys separated
     * by a `SEPARATOR`.
     *
     * @param array|stdClass $metadataTree The metadata tree to search within.
     * @param string         $path         The path string, represented as keys separated by a `SEPARATOR`.
     *
     * @throws MetadataTreeException Throws an exception if the path is empty, the metadata tree is empty, the path
     *                               contains an empty segment, or the path cannot be resolved.
     *
     * @return mixed The value found at the specified path.
     */
    public static function getByPath(
        array|stdClass $metadataTree,
        string $path
    ): mixed;

    /**
     * Determines if the given path exists in a metadata tree.
     *
     * @param array|object $metadataTree
     * @param string       $path
     *
     * @return bool
     */
    public static function hasPath(
        array|object $metadataTree,
        string $path
    ): bool;
}
