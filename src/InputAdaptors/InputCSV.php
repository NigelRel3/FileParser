<?php
declare(strict_types=1);

namespace ECD\InputAdaptors;

use ECD\Core\FileHelpers;

require_once __DIR__ . "/InputAdaptor.php";
require_once __DIR__ . "/../Core/FileHelpers.php";

/**
 * InputAdaptor for a CSV file.
 * Currently only coded for a comma separated file with optional " enclosures.
 */
class InputCSV implements InputAdaptor
{
    use FileHelpers;

    /**
     * The basic file handle for the input file returned by fopen()
     * @var resource
     */
    private $fileHandle = false;

    /**
     * List of the headers from the input file.
     * @var string[]
     */
    private $headers = [];

    /**
     * Checks then opens the input file.
     * Currently hard coded to read the first line as the headers.
     * @param string $inputFileName
	 * @throws \Exception if file is not readable.
     */
    public function __construct( string $inputFileName )
	{
	    $this->checkReadable($inputFileName);
    	$this->fileHandle = fopen($inputFileName, "r");

    	// TODO be able to configure header lines.
    	$this->headers = fgetcsv($this->fileHandle, null, ",", '"');
    }

	/**
	 * Reads the input file and yields a list of the records as an associative array.
	 * @return string[]|false
	 */
	public function read ()
	{
	    while ($data = fgetcsv($this->fileHandle, null, ",", '"'))  {
	        yield array_combine($this->headers, $data);
	    }
	    return false;
	}

	/**
	 * Ensures that if the file has been opened, that it is properly closed.
	 */
	public function close() : void
	{
	    if ( $this->fileHandle !== false )  {
	        fclose($this->fileHandle);
	    }
	}
}