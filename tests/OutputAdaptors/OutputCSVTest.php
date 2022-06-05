<?php
use PHPUnit\Framework\TestCase;
use ECD\OutputAdaptors\OutputCSV;

require_once __DIR__ . "/../../src/OutputAdaptors/OutputCSV.php";

class OutputCSVTest extends TestCase
{
    /**
     *  Ensure main test file is readable
     */
    public function setUp() : void
    {
        $fileName = __DIR__ . "/Data/example_1.csv";
        chmod($fileName, 0770);
        chmod(dirname($fileName), 0775);
    }

    /**
     *  Ensure main test file is readable
     */
    public function tearDown() : void
    {
        $fileName = __DIR__ . "/Data/example_1.csv";
        chmod($fileName, 0770);
        chmod(dirname($fileName), 0775);
    }

    /**
     * Test output file created.
     */
	public function testCreateOK()
	{
        $fileName = __DIR__ . "/Data/example_1a.csv";

        $output = new OutputCSV($fileName);
        $output->close();
        $this->assertFileExists($fileName);
        $this->assertEquals("", file_get_contents($fileName));
        unlink($fileName);
	}

	/**
	 * Write 1 record to output and compare with expected file contents.
	 */
	public function testCreateWrite()
	{
        $fileName = __DIR__ . "/Data/example_1.csv";
        $testName = __DIR__ . "/Data/test_1.csv";

        $output = new OutputCSV($fileName);
        $row1 = $output->write([ "brand_name" => "AASTRA",
            "model_name" => "6865I",
            "condition_name" => "Working",
            "grade_name" => "Grade B - Good Condition",
            "gb_spec_name" => "Not Applicable",
            "colour_name" => "Black",
            "network_name" => "Not Applicable"
        ]);
        $output->close();
        $this->assertFileExists($fileName);
        $this->assertEquals(file_get_contents($testName), file_get_contents($fileName));
	}

	/**
	 * Write 1 record to new output and compare with expected file contents.
	 */
	public function testCreateNewWrite()
	{
	    $fileName = __DIR__ . "/Data/example_1a.csv";
	    $testName = __DIR__ . "/Data/test_1.csv";

	    $output = new OutputCSV($fileName);
	    $row1 = $output->write([ "brand_name" => "AASTRA",
	        "model_name" => "6865I",
	        "condition_name" => "Working",
	        "grade_name" => "Grade B - Good Condition",
	        "gb_spec_name" => "Not Applicable",
	        "colour_name" => "Black",
	        "network_name" => "Not Applicable"
	    ]);
	    $output->close();
	    $this->assertFileExists($fileName);
	    $this->assertEquals(file_get_contents($testName), file_get_contents($fileName));
	    unlink($fileName);
	}

	/**
	 * Set file to non-writable.
	 */
	public function testCreateFailFileNotWriteable()
	{
	    $fileName = __DIR__ . "/Data/example_1.csv";
	    chmod($fileName, 0444);

	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("File {$fileName} not writable.");
	    $output = new OutputCSV($fileName);
	}

	/**
	 * Set data directory to read/execute and try and create file in there.
	 */
	public function testCreateFailDirNotWriteable()
	{
	    $fileName = __DIR__ . "/Data/example_1a.csv";
	    if ( file_exists($fileName) ) {
	        unlink($fileName);
	    }
	    chmod(dirname($fileName), 0555);

	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("File {$fileName} not writable.");
	    $output = new OutputCSV($fileName);
	}

	/**
	 * Open output and then close it before writing, write should fail.
	 */
	public function testWriteFail()
	{
	    $fileName = __DIR__ . "/Data/example_1.csv";

	    $output = new OutputCSV($fileName);
	    $output->close();
	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Failed to write to output.");
	    $row1 = $output->write([ "brand_name" => "AASTRA",
	        "model_name" => "6865I",
	        "condition_name" => "Working",
	        "grade_name" => "Grade B - Good Condition",
	        "gb_spec_name" => "Not Applicable",
	        "colour_name" => "Black",
	        "network_name" => "Not Applicable"
	    ]);
	}

}