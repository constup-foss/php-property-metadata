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
        $this->validatePropertyNodeName($propertyName);

        return $this->addNode($propertyName, $children);
    }

    /**
     * @inheritDoc
     *
     * @throws MetadataTreeException
     */
    public function addAttributeArgumentsNode(
        string $attributeFqn,
        array $attributeArguments
    ): self {
        return $this->addNode($attributeFqn, function (ObjectMetadataTreeBuilder $builder) use ($attributeArguments): void {
            $builder->addMetadataNode('attributeArguments', $attributeArguments);
        });
    }

    /**
     * Validate a property node name. The official regex is used for validation (available in the link).
     *
     * @param string $propertyName
     *
     * @throws MetadataTreeException
     *
     * @return void
     *
     * @link https://www.php.net/manual/en/language.variables.basics.php
     */
    private function validatePropertyNodeName(
        string $propertyName,
    ): void {
        if ($propertyName === '') {
            throw new MetadataTreeException()->emptyMetadataNodeName();
        }

        // Official regex available at https://www.php.net/manual/en/language.variables.basics.php
        if (preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $propertyName) === 1) {
            return;
        }

        throw new MetadataTreeException()->invalidNodeName();
    }
}
