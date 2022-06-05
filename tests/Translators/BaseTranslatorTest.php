<?php
use PHPUnit\Framework\TestCase;
use ECD\Core\Product;
use ECD\Translators\BaseTranslator;

require_once __DIR__ . "/../../src/Translators/BaseTranslator.php";

class BaseTranslatorTest extends TestCase
{
	/**
	 * Straight forward trasnlation with valid data.
	 */
	public function testTrans1()
	{
        $fileName = __DIR__ . "/Data/example_1.csv";
        $testName = __DIR__ . "/Data/test_1.csv";

        $trans = new BaseTranslator();
        $product = $trans->translate([ "brand_name" => "AASTRA",
            "model_name" => "6865I",
            "condition_name" => "Working",
            "grade_name" => "Grade B - Good Condition",
            "gb_spec_name" => "Not Applicable",
            "colour_name" => "Black",
            "network_name" => "Not Applicable"
        ]);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals(["make" => "AASTRA",
            "model" => "6865I",
            "colour" => "Black",
            "capacity" => "Not Applicable",
            "network" => "Not Applicable",
            "grade" => "Grade B - Good Condition",
            "condition" => "Working",
        ],
            $product->getDataAsArray());
	}

	/**
	 * Try and translate with empty make (a required field).
	 */
	public function testTransFailMake()
	{
	    $fileName = __DIR__ . "/Data/example_1.csv";
	    $testName = __DIR__ . "/Data/test_1.csv";

	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Make must contain a value");
	    $trans = new BaseTranslator();
	    $product = $trans->translate([ "brand_name" => "",
	        "model_name" => "6865I",
	        "condition_name" => "Working",
	        "grade_name" => "Grade B - Good Condition",
	        "gb_spec_name" => "Not Applicable",
	        "colour_name" => "Black",
	        "network_name" => "Not Applicable"
	    ]);
	}

	/**
	 * Translate with missing (non-required) array element.
	 */
	public function testTransNoColour()
	{
	    $fileName = __DIR__ . "/Data/example_1.csv";
	    $testName = __DIR__ . "/Data/test_1.csv";

	    $trans = new BaseTranslator();
	    $product = $trans->translate([ "brand_name" => "AASTRA",
	        "model_name" => "6865I",
	        "condition_name" => "Working",
	        "grade_name" => "Grade B - Good Condition",
	        "gb_spec_name" => "Not Applicable",
	        "network_name" => "Not Applicable"
	    ]);
	    $this->assertInstanceOf(Product::class, $product);
	    $this->assertEquals(["make" => "AASTRA",
	        "model" => "6865I",
	        "colour" => "",
	        "capacity" => "Not Applicable",
	        "network" => "Not Applicable",
	        "grade" => "Grade B - Good Condition",
	        "condition" => "Working",
	    ],
	        $product->getDataAsArray());
	}

	/**
	 * Set new translation names and then translate.
	 */
	public function testTransAlternateNames()
	{
	    $fileName = __DIR__ . "/Data/example_1.csv";
	    $testName = __DIR__ . "/Data/test_1.csv";

	    $trans = new BaseTranslator();
	    // Changed color_name and spec_name.
	    $trans->setTranslation([
	        "brand_name", "model_name", "color_name", "spec_name",
	        "network_name", "grade_name", "condition_name"
	    ]);
	    $product = $trans->translate([ "brand_name" => "AASTRA",
	        "model_name" => "6865I",
	        "condition_name" => "Working",
	        "grade_name" => "Grade B - Good Condition",
	        "spec_name" => "Not Applicable",
	        "color_name" => "Black",
	        "network_name" => "Not Applicable"
	    ]);
	    $this->assertInstanceOf(Product::class, $product);
	    $this->assertEquals(["make" => "AASTRA",
	        "model" => "6865I",
	        "colour" => "Black",
	        "capacity" => "Not Applicable",
	        "network" => "Not Applicable",
	        "grade" => "Grade B - Good Condition",
	        "condition" => "Working",
	    ],
	        $product->getDataAsArray());
	}
}