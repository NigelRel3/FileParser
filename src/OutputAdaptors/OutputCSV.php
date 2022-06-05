<?php
declare(strict_types=1);

namespace ECD\OutputAdaptors;

use ECD\Core\FileHelpers;

require_once __DIR__ . "/OutputAdaptor.php";
require_once __DIR__ . "/../Core/FileHelpers.php";

class OutputCSV implements OutputAdaptor
{
    use FileHelpers;

    /**
     * The basic file handle for the input file returned by fopen()
     * @var resource
     */
    private $fileHandle = null;

    /**
     * Set to true once output headers sent.
     * TODO - Allow flagging to indicate if headers are needed.
     * TODO - Allow to set headers rather than just use data keys.
     *
     * @var boolean
     */
    private $headersWritten = false;

    /**
     *
     * @param string $outputFileName
	 * @throws \Exception if file is not writable.
     */
    public function __construct( string $outputFileName )
	{
	    $this->checkWritable($outputFileName);
	    $this->fileHandle = fopen($outputFileName, "w");
	}

	public function write ( array $data )
	{
	    try    {
    	    // If need to write file headers, output data keys as headers
    	    if ( $this->headersWritten === false ) {
    	        // TODO - Define parameters for fputcsv - delimiter, enclosure, escape
    	        fputcsv($this->fileHandle, array_keys($data), ",", '"');
    	        $this->headersWritten = true;
    	    }
    	    fputcsv($this->fileHandle, $data, ",", '"');
	    }
	    catch (\Exception $e)  {
	        throw new \Exception("Failed to write to output.");
	    }
	}

	public function close() : void
	{
	    if ( $this->fileHandle !== false )  {
	        fclose($this->fileHandle);
	    }
	}
}