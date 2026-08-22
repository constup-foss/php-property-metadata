<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata;

use ConstupFoss\PhpPropertyMetadata\Exceptions\ConstupFossPhpPropertyMetadataException;
use ConstupFoss\PhpPropertyMetadata\Exceptions\MetadataTreeException;
use stdClass;

readonly class MetadataService implements MetadataServiceInterface
{
    /**
     * @inheritDoc
     */
    public static function getValueFromPath(
        array|stdClass $metadataTree,
        string $path
    ): mixed {
        if (trim($path) === '') {
            throw new MetadataTreeException()->pathIsEmpty();
        }

        if (empty($metadataTree)) {
            throw new MetadataTreeException()->metadataTreeIsEmpty();
        }

        $keys = explode(MetadataServiceInterface::SEPARATOR, $path);

        if (in_array('', $keys, true)) {
            throw new MetadataTreeException()->pathContainsEmptySegment();
        }

        $sentinel = new class() {};
        $value = array_reduce(
            $keys,
            function (mixed $carry, string $key) use ($sentinel): mixed {
                if ($carry === $sentinel) {
                    return $sentinel;
                }
                if (is_array($carry)) {
                    return array_key_exists($key, $carry) ? $carry[$key] : $sentinel;
                }
                if ($carry instanceof stdClass) {
                    return property_exists($carry, $key) ? $carry->$key : $sentinel;
                }

                return $sentinel;
            },
            (static fn (): stdClass|array => $metadataTree)()
        );

        if ($value === $sentinel) {
            throw new MetadataTreeException()->pathNotFound($path);
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public static function hasPath(
        array|object $metadataTree,
        string $path
    ): bool {
        try {
            self::getValueFromPath($metadataTree, $path);

            return true;
        } catch (ConstupFossPhpPropertyMetadataException) {
            return false;
        }
    }
}
