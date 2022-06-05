<?php
declare(strict_types=1);

namespace ECD\OutputAdaptors;

interface OutputAdaptor
{
    /**
     *
     * @param string $outputName - name of file to write to.
     */
    public function __construct( string $outputName );
    /**
     * Writes an associative array of data to the output.
     * @param array $data
     */
    public function write ( array $data );
	public function close() : void;
}