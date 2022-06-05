<?php
use PHPUnit\Framework\TestCase;
use ECD\Core\Parameters;

require_once __DIR__ . "/../../src/Core/Parameters.php";

class ParametersTest extends TestCase
{

	public function testCreateOK()
	{
		$params = [ "fileParser.php",
				"--input=example_1.csv",
				"--output=combination_count.csv"
		];

		$parameter = new Parameters($params);
		$this->assertTrue($parameter->hasParameter("input"));
		$this->assertEquals("example_1.csv", $parameter->getParameter("input"));
		$this->assertTrue($parameter->hasParameter("output"));
		$this->assertEquals("combination_count.csv", $parameter->getParameter("output"));
	}

	public function testCreateFailInvalidParameters()
	{
	    $params = [ "fileParser.php",
	        "-input=example_1.csv",
	        "+-output=combination_count.csv"
	    ];

	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Incorrectly formatted parameter: -input=example_1.csv");
	    $parameter = new Parameters($params);
	}

	public function testGetParameters()
	{
	    $params = [ "fileParser.php",
	        "--input=example_1.csv",
	        "--outputA=combination_count.csv"
	    ];

	    $parameter = new Parameters($params);
	    $list = $parameter->getParameters();
	    $this->assertIsArray($list);
	    $this->assertCount(2, $list);
	    $this->assertArrayHasKey("input", $list);
	    $this->assertEquals("example_1.csv", $list["input"]);
	    $this->assertArrayHasKey("outputA", $list);
	    $this->assertEquals("combination_count.csv", $list["outputA"]);
	}
}