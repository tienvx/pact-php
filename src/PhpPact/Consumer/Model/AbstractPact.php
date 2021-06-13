<?php

namespace PhpPact\Consumer\Model;

use FFI;
use PhpPact\Standalone\Installer\Model\Scripts;

/**
 * Class AbstractPact.
 */
abstract class AbstractPact
{
    protected FFI $ffi;
    protected int $id;

    /**
     * @param FFI|null $ffi
     */
    public function __construct(?FFI $ffi = null)
    {
        $this->ffi = $ffi ?? FFI::cdef(\file_get_contents(Scripts::getCode()), Scripts::getLibrary());
    }
}
