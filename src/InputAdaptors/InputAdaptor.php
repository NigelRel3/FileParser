<?php
declare(strict_types=1);

namespace ECD\InputAdaptors;

interface InputAdaptor
{
    /**
     *
     * @param string $inputName - name of file to read from
     */
    public function __construct( string $inputName );

    /**
     * Reads the entire input and yields each row at a time.
     * As this returns a Generator, suggested usage is something like:
     *      foreach( $input->read() as $row ) {
     *      }
     * @return string[]|false
     */
    public function read ();
    public function close() : void;
}