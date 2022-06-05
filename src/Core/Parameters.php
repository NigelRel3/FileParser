<?php
declare(strict_types=1);

namespace ECD\Core;

/**
 * Class used to process command line parameters, only basic validation included.
 * Parameters must be of the format
 *      --name=value
 **/
class Parameters
{
	/**
	 * List of paramters passed into script.  It will contain entries indexed by the
	 * parameter name.  So for example:
	 *     [input] => example_1.csv
	 *     [unique-combinations] => combination_count.csv
	 *
	 * @var string[] $parameters
	 */
	private $parameters = [];

	/**
	 * @param string[] $cliParams expected $argv value
	 * 		Note that the first entry in argv is the command being run.
	 * @throws \Exception if incorrectly formatted option
	 */
	public function __construct( array $cliParams )
	{
		// Remove command from list
		array_shift($cliParams);
		// Parse parameters from command line
		foreach ( $cliParams as $parameter )	{
		    // Split into only 2 parts (subsequent ='s are assumed part of the value)
		    $parts = explode("=", $parameter, 2);

		    // Parameter name must start with --
		    if ( substr($parts[0], 0, 2) != '--' )	{
		    	throw new \Exception("Incorrectly formatted parameter: " . $parameter);
		    }
		    $this->parameters [ ltrim($parts[0], "-") ] = $parts[1] ?? '';
		}
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function getParameter (string $name) : string
	{
		return $this->parameters [ $name ];
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasParameter (string $name) : bool
	{
		return isset($this->parameters [ $name ]);
	}

	/**
	 * Returns all of the parameters.
	 * @return string[]
	 */
	public function getParameters() : array
	{
		return $this->parameters;
	}
}