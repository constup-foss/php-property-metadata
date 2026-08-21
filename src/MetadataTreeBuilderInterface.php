<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata;

use Closure;

interface MetadataTreeBuilderInterface
{
    /**
     * Return a built metadata tree.
     *
     * @return object
     */
    public function build(): object;

    /**
     * Add a new simple node to the metadata tree.
     *
     * @param string       $nodeKey
     * @param Closure|null $children
     *
     * @return static
     */
    public function addNode(
        string $nodeKey,
        ?Closure $children = null
    ): static;

    /**
     * Add a new metadata node to the metadata tree.
     *
     * @param string                                  $nodeKey
     * @param int|float|string|bool|array|object|null $value
     *
     * @return static
     */
    public function addMetadataNode(
        string $nodeKey,
        int|float|string|bool|array|object|null $value
    ): static;
}
