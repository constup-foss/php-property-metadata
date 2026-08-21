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
    ): static {
        $this->validateNodeName($nodeKey);
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
    ): static {
        $this->validateNodeName($nodeKey);
        $currentNode = $this->getCurrentNode();
        $currentNode->{$nodeKey} = $value;

        return $this;
    }

    /**
     * Validate node name.
     * It is important for this validation to be more permissive than when validating a PHP property name.
     *
     * @param string $nodeName
     *
     * @throws MetadataTreeException
     *
     * @return void
     */
    private function validateNodeName(
        string $nodeName,
    ): void {
        if ($nodeName === '') {
            throw new MetadataTreeException()->emptyMetadataNodeName();
        }

        if (preg_match('/^[a-zA-Z_\x80-\xff\\\][a-zA-Z0-9_\x80-\xff\\\]*$/', $nodeName) === 1) {
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
