<?php
use PHPUnit\Framework\TestCase;
use ECD\Core\Product;

require_once __DIR__ . "/../../src/Core/Product.php";

class ProductTest extends TestCase
{

	public function testGetMakeSetMake()
	{
		$product = new Product();
		$product->setMake("Make");
		$this->assertEquals("Make", $product->getMake());
	}

	/**
	 * Check setting required fields to blank throws an exception.
	 */
	public function testSetMakeEmpty()
	{
	    $product = new Product();
	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Make must contain a value");
	    $product->setMake("");
	}

	public function testSetModelEmpty()
	{
	    $product = new Product();
	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Model must contain a value");
	    $product->setModel("");
	}

	/**
	 * Check if not setting a make first, throws an excecption when trying to
	 * retrieve the value.
	 */
	public function testGetMakeEmpty()
	{
	    $product = new Product();
	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Make does not contain a value");
	    $product->getMake();
	}

	    /**
	 * Check if not setting a model first, throws an excecption when trying to
	 * retrieve the value.
	 */
	public function testGetModelEmpty()
	{
	    $product = new Product();
	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Model does not contain a value");
	    $product->getModel();
	}

	/**
	 * It is valid to set non-required fields to empty.
	 */
	public function testSetColourEmpty()
	{
	    $product = new Product();
	    $product->setColour("");
	    $this->assertEquals("", $product->getColour());
	}

	/**
	 * Fetching a non-required field before setting it should return null.
	 */
	public function testGetColourEmpty()
	{
	    $product = new Product();
	    $this->assertEquals(null, $product->getColour());
	}

	public function testValidateOK()
	{
	    $product = new Product();
	    $product->setMake("Make");
	    $product->setModel("Model");
	    $product->setColour("");
	    $this->assertTrue($product->validate());
	}

	public function testValidateFail()
	{
	    $product = new Product();
	    $product->setMake("Make");
	    $product->setColour("");
	    $this->assertFalse($product->validate());
	}

	public function testGetUniqueIdentifier()
	{
	    $product = new Product();
	    $product->setMake("MakeValue");
	    $product->setModel("ModelValue");
	    $product->setColour("ColourValue");
	    $product->setCapacity("CapacityValue");
	    $product->setNetwork("NetworkValue");
	    $product->setGrade("GradeValue");
	    $product->setCondition("ConditionValue");
	    $this->assertEquals('{"make":"MakeValue","model":"ModelValue","colour":"ColourValue","capacity":"CapacityValue","network":"NetworkValue","grade":"GradeValue","condition":"ConditionValue"}'
	           , $product->getUniqueIdentifier());
	}

	public function testGetDataAsArray()
	{
	    $product = new Product();
	    $product->setMake("MakeValue");
	    $product->setModel("ModelValue");
	    $product->setColour("ColourValue");
	    $product->setCapacity("CapacityValue");
	    $product->setNetwork("NetworkValue");
	    $product->setGrade("GradeValue");
	    $product->setCondition("ConditionValue");
	    $details = $product->getDataAsArray();
	    $this->assertIsArray($details);
	    $this->assertEquals(["make" => "MakeValue",
    	        "model" => "ModelValue",
    	        "colour" => "ColourValue",
    	        "capacity" => "CapacityValue",
	            "network" => "NetworkValue",
    	        "grade" => "GradeValue",
    	        "condition" => "ConditionValue"
    	    ],
	        $details);
	}

	/**
	 * Test exception thrown due to not setting required value (make).
	 */
	public function testGetDataAsArrayFail()
	{
	    $product = new Product();
	    $product->setModel("ModelValue");
	    $product->setColour("ColourValue");
	    $product->setCapacity("CapacityValue");
	    $product->setNetwork("NetworkValue");
	    $product->setGrade("GradeValue");
	    $product->setCondition("ConditionValue");
	    $this->expectException(Exception::class);
	    $this->expectExceptionMessage("Invalid product data.");
	    $details = $product->getDataAsArray();

	}
}