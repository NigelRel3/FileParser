<?php
use PHPUnit\Framework\TestCase;
use ECD\Core\Parser;
use ECD\Core\Parameters;

require_once __DIR__ . "/../../src/Core/Parser.php";
require_once __DIR__ . "/../../src/Core/Parameters.php";

class ParserTest extends TestCase
{
	public function testCreateOK()
	{
		$parameter = $this->createMock(Parameters::class);
		$parameter->expects($this->once())
    		->method("getParameters")
    		->willReturn([
    		      "input" => "example_1.csv",
    		      "output" => "combination_count.csv"
    		]);
		$parse = new Parser($parameter);
	}

	/**
	 * Missing required parameters
	 */
	public function testCreateFailMissingRequired()
	{
	    $parameter = $this->createMock(Parameters::class);
	    $parameter->expects($this->once())
    	    ->method("getParameters")
    	    ->willReturn([
    	        "output" => "combination_count.csv"
    	    ]);

	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Usage fileParser --input=example_1.csv --output=combination_count.csv");
	    $parse = new Parser($parameter);
	}

	/**
	 * Optional parameters
	 */
	public function testCreateOptionalParams()
	{
	    $parameter = $this->createMock(Parameters::class);
	    $parameter->expects($this->once())
    	    ->method("getParameters")
    	    ->willReturn([
    	        "input" => "example_1.csv",
    	        "output" => "combination_count.csv",
    		    "reject" => "error.csv"
    	    ]);

	    $parse = new Parser($parameter);
	}

	/**
	 * Invalid optional parameters
	 */
	public function testCreateFailOptionalParams()
	{
	    $parameter = $this->createMock(Parameters::class);
	    $parameter->expects($this->once())
    	    ->method("getParameters")
    	    ->willReturn([
    	        "input" => "example_1.csv",
    	        "output" => "combination_count.csv",
    	        "rejected" => "error.csv"
    	    ]);

	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Invalid parameters specified (reject, csvfieldorder, inputadaptor, input, output)");
	    $parse = new Parser($parameter);
	    $this->assertNotNull($parse);
	}

	/*
	 * Can't find input adaptor
	 */
	public function testCreateFailInputAdaptor()
	{
	    $parameter = $this->createMock(Parameters::class);
	    $parameter->expects($this->once())
    	    ->method("getParameters")
    	    ->willReturn([
    	        "input" => "example_1.xxx",
    	        "output" => "combination_count.csv"
    	    ]);

	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Input adaptor for this file not found.");
	    $parse = new Parser($parameter);
	    $this->assertNotNull($parse);
	    $parse->parse();
	}

	/*
	 * Can't find output adaptor
	 */
	public function testCreateFailOutputAdaptor()
	{
	    $parameter = $this->createMock(Parameters::class);
	    $parameter->expects($this->once())
    	    ->method("getParameters")
    	    ->willReturn([
    	        "input" => __DIR__ . "/Data/example_1.csv",
    	        "output" => __DIR__ . "/Data/combination_count.xxx"
    	    ]);

	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Output adaptor for this file not found.");
	    $parse = new Parser($parameter);
	    $this->assertNotNull($parse);
	    $parse->parse();
	}

	/**
	 * Process example data CSV file.
	 */
	public function testCSVToCSV()
	{
	    $parameter = $this->createMock(Parameters::class);
	    $parameter->expects($this->once())
    	    ->method("getParameters")
    	    ->willReturn([
    	        "input" => __DIR__ . "/Data/example_1.csv",
    	        "output" => __DIR__ . "/Data/output1.csv"
    	    ]);

	    // Check console output of records
	    $this->expectOutputString('AASTRA,6865I,Black,Not Applicable,Not Applicable,Grade B - Good Condition,Working' . PHP_EOL
	        . 'AASTRA,6865I,Black,Not Applicable,Not Applicable,Grade A - Very Good Condition,Working' . PHP_EOL);
	    $parse = new Parser($parameter);
	    $parse->parse();

	    // Check output file against expected file
	    $this->assertEquals(file_get_contents(__DIR__ . "/Data/test_1.csv"),
	        file_get_contents(__DIR__ . "/Data/output1.csv"));

	    unlink(__DIR__ . "/Data/output1.csv");
	}

	/**
	 * Process file with missing model in row
	 */
	public function testCSVToCSVMissingModel()
	{
	    $parameter = $this->createMock(Parameters::class);
	    $parameter->expects($this->once())
    	    ->method("getParameters")
    	    ->willReturn([
    	        "input" => __DIR__ . "/Data/example_missing_model.csv",
    	        "output" => __DIR__ . "/Data/output1.csv"
    	    ]);

	    // Check console output of records
	    $this->expectOutputString('AASTRA,,Working,Grade B - Good Condition,Not Applicable,Black,Not Applicable,Model must contain a value' . PHP_EOL
 	        . 'AASTRA,6865I,Black,Not Applicable,Not Applicable,Grade A - Very Good Condition,Working' . PHP_EOL);
	    $parse = new Parser($parameter);
	    $parse->parse();

	    // Check output file against expected file
	    $this->assertEquals(file_get_contents(__DIR__ . "/Data/test_missing_model.csv"),
	        file_get_contents(__DIR__ . "/Data/output1.csv"));

	    unlink(__DIR__ . "/Data/output1.csv");
	}

	// missing data
	// set input adaptor
	// set invalid input adaptor

	/**
	 * Process example data json file.
	 */
	public function testJSONToCSV()
	{
	    $parameter = $this->createMock(Parameters::class);
	    $parameter->expects($this->once())
	    ->method("getParameters")
	    ->willReturn([
	        "input" => __DIR__ . "/Data/example_1.json",
	        "output" => __DIR__ . "/Data/output1.csv"
	    ]);

	    // Check console output of records
	    $this->expectOutputString('AASTRA,6865Ia,Black,Not Applicable,Not Applicable,Grade B - Good Condition,Working' . PHP_EOL
	        . 'AASTRA,6865Ia,Black,Not Applicable,Not Applicable,Grade A - Very Good Condition,Working' . PHP_EOL
	        . 'ABOOK,1720Wx,Black,4GB,Not Applicable,Grade B - Good Condition,Working' . PHP_EOL);
	    $parse = new Parser($parameter);
	    $parse->parse();

	    // Check output file against expected file
	    $this->assertEquals(file_get_contents(__DIR__ . "/Data/test_json.csv"),
	        file_get_contents(__DIR__ . "/Data/output1.csv"));

	    unlink(__DIR__ . "/Data/output1.csv");
	}
}