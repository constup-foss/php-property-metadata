<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata;

use Closure;

interface ObjectMetadataTreeBuilderInterface
{
    /**
     * Add a class or object property node.
     *
     * @param string       $propertyName
     * @param Closure|null $children
     *
     * @return self
     */
    public function addPropertyNode(
        string $propertyName,
        ?Closure $children = null
    ): self;
}
