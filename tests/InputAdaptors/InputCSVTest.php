<?php
use PHPUnit\Framework\TestCase;
use ECD\InputAdaptors\InputCSV;

require_once __DIR__ . "/../../src/InputAdaptors/InputCSV.php";

class InputCSVTest extends TestCase
{
    /**
     *  Ensure main test file is readable
     */
    public function setUp() : void
    {
        $inputFile = __DIR__ . "/Data/example_1.csv";
        chmod($inputFile, 0770);
    }

    /**
     *  Ensure main test file is readable
     */
    public function tearDown() : void
    {
        $inputFile = __DIR__ . "/Data/example_1.csv";
        chmod($inputFile, 0770);
    }

    /**
     * Test reading first record of file works OK.
     */
	public function testCreateRead1OK()
	{
        $inputFile = __DIR__ . "/Data/example_1.csv";

        $input = new InputCSV($inputFile);
        $row1 = $input->read()->current();
        $this->assertIsArray($row1);
        $this->assertEquals([ "brand_name" => "AASTRA",
            "model_name" => "6865I",
            "condition_name" => "Working",
            "grade_name" => "Grade B - Good Condition",
            "gb_spec_name" => "Not Applicable",
            "colour_name" => "Black",
            "network_name" => "Not Applicable"
        ], $row1);
        $input->close();
	}

	public function testCreateReadFile()
	{
	    $inputFile = __DIR__ . "/Data/example_1.csv";

	    $input = new InputCSV($inputFile);
	    $rows = [];
	    foreach ( $input->read() as $row ) {
	        $rows[] = $row;
	    }
	    $input->close();
	    $this->assertIsArray($rows);
	    $this->assertCount(2, $rows);
	    $this->assertEquals([ "brand_name" => "AASTRA",
	        "model_name" => "6865I",
	        "condition_name" => "Working",
	        "grade_name" => "Grade B - Good Condition",
	        "gb_spec_name" => "Not Applicable",
	        "colour_name" => "Black",
	        "network_name" => "Not Applicable"
	    ], $rows[0]);
	    $this->assertEquals([ "brand_name" => "AASTRA",
	        "model_name" => "6865I",
	        "condition_name" => "Working",
	        "grade_name" => "Grade A - Very Good Condition",
	        "gb_spec_name" => "Not Applicable",
	        "colour_name" => "Black",
	        "network_name" => "Not Applicable"
	    ], $rows[1]);
	}

	/**
	 * Ensures file doesn't exist and then tries to use it for input.
	 */
	public function testCreateFailFileNotFound()
	{
	    $inputFile = __DIR__ . "/Data/example_1a.csv";

	    if ( file_exists($inputFile) ) {
	       unlink($inputFile);
	    }
	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Input file {$inputFile} not found/readable.");
	    $input = new InputCSV($inputFile);
	}

	/**
	 * Try and read directory.
	 */
	public function testCreateFailFileNotAFile()
	{
	    $inputFile = __DIR__ . "/Data";

	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Input file {$inputFile} not a file.");
	    $input = new InputCSV($inputFile);
	}

	/**
	 * Changes the access to the example_2.csv file to make this file no longer
	 * readable and then tries to use it as input.
	 */
	public function testCreateFailFileNotReadable()
	{
	    $inputFile = __DIR__ . "/Data/example_1.csv";
	    chmod($inputFile, 0000);
	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Input file {$inputFile} not found/readable.");
	    $input = new InputCSV($inputFile);
	}
}