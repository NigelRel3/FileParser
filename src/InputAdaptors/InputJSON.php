<?php
declare(strict_types=1);

namespace ECD\InputAdaptors;

use ECD\Core\FileHelpers;

require_once __DIR__."/InputAdaptor.php";
require_once __DIR__."/../Core/FileHelpers.php";

/**
 * InputAdaptor for a JSON file.
 * This is coded to work with a fixed format JSON file, and example of which
 * is example_1.json if the Data firectory.
 */
class InputJSON implements InputAdaptor
{
    use FileHelpers;

    /**
     * The input file after json_decode'ing it.
     * @var array
     */
    private $data = [];

    public function __construct( string $inputFileName )
	{
	    $this->checkReadable($inputFileName);
        $dataSource = file_get_contents($inputFileName);
	    $this->data = json_decode($dataSource, true);
    }

	/**
	 * Reads the input file and yields a list of the records as an associative array.
	 * @return string[]|false
	 */
	public function read ()
	{
	    foreach ( $this->data as $brandModel )    {
	        foreach ( $brandModel['phones'] as $phone )    {
	            yield ["brand_name" => $brandModel['brand_name'],
	                "model_name" => $brandModel['model_name'],
	                "condition_name" => $phone['condition_name'],
	                "grade_name" => $phone['grade_name'],
	                "gb_spec_name" => $phone['gb_spec_name'],
	                "colour_name" => $phone['colour_name'],
	                "network_name" => $phone['network_name'],
	            ];
	        }
	    }
	    return false;
	}

	/**
	 * Close, currently, has nothing to do.
	 */
	public function close() : void
	{
	}
}