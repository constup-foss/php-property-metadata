<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata;

use Closure;
use ConstupFoss\PhpPropertyMetadata\Exceptions\MetadataTreeException;
use stdClass;

class MetadataTreeBuilder implements MetadataTreeBuilderInterface
{
    public const string ARRAY_METADATA_KEY = '__arrayMetadata';

    private stdClass $metadata;
    private array $path = [];

    public function __construct()
    {
        $this->metadata = (object)[
            'root' => (object)[],
        ];

        $this->path[] = $this->metadata->root;
    }

    /**
     * @inheritDoc
     */
    public function build(): object
    {
        return $this->metadata;
    }

    /**
     * @inheritDoc
     *
     * @throws MetadataTreeException
     */
    public function addNode(
        string $nodeKey,
        ?Closure $children = null
    ): self {
        $this->validatePropertyNodeName($nodeKey);
        $currentNode = $this->getCurrentNode();

        if (!property_exists($currentNode, $nodeKey) || !is_object($currentNode->{$nodeKey})) {
            $currentNode->{$nodeKey} = new stdClass();
        }

        return $this->descendInto($currentNode->{$nodeKey}, $children);
    }

    /**
     * @inheritDoc
     *
     * @throws MetadataTreeException
     */
    public function addMetadataNode(
        string $nodeKey,
        int|float|string|bool|array|object|null $value
    ): self {
        // We are using the same validation for metadata keys as for PHP property names. This should provide enough
        // flexibility when defining metadata keys. If needed, implement a new `validateMetadataNodeName` method and use
        // it instead.
        $this->validatePropertyNodeName($nodeKey);
        $currentNode = $this->getCurrentNode();
        $currentNode->{$nodeKey} = $value;

        return $this;
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

    /**
     * Descend into a child node.
     *
     * @param object       $node
     * @param Closure|null $children
     *
     * @return self
     */
    private function descendInto(
        object $node,
        ?Closure $children
    ): self {
        if ($children === null) {
            return $this;
        }

        $this->path[] = $node;

        try {
            $children($this);
        } finally {
            array_pop($this->path);
        }

        return $this;
    }

    /**
     * Get the current node at the path.
     *
     * @return object
     */
    private function getCurrentNode(): object
    {
        return $this->path[array_key_last($this->path)];
    }
}
