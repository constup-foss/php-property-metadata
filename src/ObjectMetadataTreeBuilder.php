<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata;

use Closure;
use ConstupFoss\PhpPropertyMetadata\Exceptions\MetadataTreeException;

class ObjectMetadataTreeBuilder extends MetadataTreeBuilder implements ObjectMetadataTreeBuilderInterface
{
    /**
     * @inheritDoc
     *
     * @throws MetadataTreeException
     */
    public function addPropertyNode(
        string $propertyName,
        ?Closure $children = null
    ): self {
        return $this->addNode($propertyName, $children);
    }
}
