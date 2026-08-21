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

    /**
     * Adds an attribute arguments node.
     * Key of the node is the attribute fully qualified name.
     * Value is an object that has a property `attributeArguments` where the value of attribute arguments is stored.
     *
     * @param string $attributeFqn
     * @param array  $attributeArguments
     *
     * @return self
     */
    public function addAttributeArgumentsNode(
        string $attributeFqn,
        array $attributeArguments
    ): self;
}
