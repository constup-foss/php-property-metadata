<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpPropertyMetadata\Exceptions;

use ConstupFoss\PhpExerr\Library\LibraryException;

abstract class ConstupFossPhpPropertyMetadataException extends LibraryException
{
    protected string $libraryName = 'constup-foss/php-property-metadata';
}
