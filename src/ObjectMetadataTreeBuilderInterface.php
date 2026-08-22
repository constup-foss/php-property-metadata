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
     * @return static
     */
    public function addPropertyNode(
        string $propertyName,
        ?Closure $children = null
    ): static;

    /**
     * Adds an attribute arguments node.
     * Key of the node is the attribute fully qualified name.
     * Value is an object that has a property `attributeArguments` where the value of attribute arguments is stored.
     *
     * @param string $attributeFqn       Fully qualified name of the attribute.
     * @param array  $attributeArguments Array of attribute arguments.
     *
     * @return static
     */
    public function addAttributeArgumentsNode(
        string $attributeFqn,
        array $attributeArguments
    ): static;
}
