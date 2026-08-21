<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata\Exceptions;

class MetadataTreeException extends ConstupFossPhpPropertyMetadataException
{
    /**
     * The path was not found in the metadata tree.
     *
     * @param string $metadataTreePath
     *
     * @return $this
     */
    public function pathNotFound(string $metadataTreePath): self
    {
        $this->message = 'Path not found in metadata tree.';
        $this->debugMessage = 'Path: ' . $metadataTreePath . ' not found in metadata tree.';
        $this->code = 1000;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when the metadata tree path is empty.
     *
     * @return $this
     */
    public function pathIsEmpty(): self
    {
        $this->message = 'Metadata tree path is empty.';
        $this->debugMessage = 'Metadata tree path is empty.';
        $this->code = 1001;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when the metadata tree path contains an empty segment. ('foo->->bar')
     *
     * @return $this
     */
    public function pathContainsEmptySegment(): self
    {
        $this->message = 'Metadata tree path contains an empty segment.';
        $this->debugMessage = 'Metadata tree path contains an empty segment.';
        $this->code = 1002;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when the metadata tree is empty.
     *
     * @return $this
     */
    public function metadataTreeIsEmpty(): self
    {
        $this->message = 'Metadata tree is empty.';
        $this->debugMessage = 'Metadata tree is empty.';
        $this->code = 1003;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when trying to add an empty node when building a metadata tree.
     *
     * @return $this
     */
    public function emptyMetadataNodeName(): self
    {
        $this->message = 'Metadata tree node name is empty.';
        $this->debugMessage = 'Metadata tree node name is empty.';
        $this->code = 1004;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when trying to create a metadata property node that has an invalid name. Use the official regex for
     * checking if the variable name is valid (available in the link).
     *
     * @return $this
     *
     * @link https://www.php.net/manual/en/language.variables.basics.php
     */
    public function invalidNodeName(): self
    {
        $this->message = 'Invalid metadata tree node name.';
        $this->debugMessage = 'Invalid metadata tree node name.';
        $this->code = 1005;
        $this->recoverable = false;

        return $this;
    }
}
