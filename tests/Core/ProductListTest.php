<?php
use PHPUnit\Framework\TestCase;
use ECD\Core\ProductList;
use ECD\Core\Product;
use ECD\OutputAdaptors\OutputAdaptor;

require_once __DIR__ . "/../../src/Core/ProductList.php";
require_once __DIR__ . "/../../src/OutputAdaptors/OutputAdaptor.php";

class ProductListTest extends TestCase
{
    /**
     * Test with 1 product.
     */
	public function testAddProductBasic()
	{
	    $productList = new ProductList();
	    $product = new Product();
	    $product->setMake("MakeValue");
	    $product->setModel("ModelValue");
	    $product->setColour("ColourValue");
	    $product->setCapacity("CapacityValue");
	    $product->setNetwork("NetworkValue");
	    $product->setGrade("GradeValue");
	    $product->setCondition("ConditionValue");

        $productList->addProduct($product);

        $this->assertEquals(1, $productList->getCount());
	}

	/**
	 * Test with different products (grade).
	 */
	public function testAddProductMultiDifferent()
	{
	    $productList = new ProductList();
	    $product = new Product();
	    $product->setMake("MakeValue");
	    $product->setModel("ModelValue");
	    $product->setColour("ColourValue");
	    $product->setCapacity("CapacityValue");
	    $product->setNetwork("NetworkValue");
	    $product->setGrade("GradeValue");
	    $product->setCondition("ConditionValue");

	    $productList->addProduct($product);

	    $product1 = new Product();
	    $product1->setMake("MakeValue");
	    $product1->setModel("ModelValue");
	    $product1->setColour("ColourValue");
	    $product1->setCapacity("CapacityValue");
	    $product1->setNetwork("NetworkValue");
	    $product1->setGrade("GradeValue1");
	    $product1->setCondition("ConditionValue");

	    $productList->addProduct($product1);

	    $this->assertEquals(2, $productList->getCount());
	}

	/**
	 * Test with multiple same product.
	 * This should count as 1 product, but have a count of 2 (not tested here).
	 */
	public function testAddProductMultiSame()
	{
	    $productList = new ProductList();
	    $product = new Product();
	    $product->setMake("MakeValue");
	    $product->setModel("ModelValue");
	    $product->setColour("ColourValue");
	    $product->setCapacity("CapacityValue");
	    $product->setNetwork("NetworkValue");
	    $product->setGrade("GradeValue");
	    $product->setCondition("ConditionValue");

	    $productList->addProduct($product);

	    $product1 = new Product();
	    $product1->setMake("MakeValue");
	    $product1->setModel("ModelValue");
	    $product1->setColour("ColourValue");
	    $product1->setCapacity("CapacityValue");
	    $product1->setNetwork("NetworkValue");
	    $product1->setGrade("GradeValue");
	    $product1->setCondition("ConditionValue");

	    $productList->addProduct($product1);

	    $this->assertEquals(1, $productList->getCount());
	}

    /**
     * Saving two different products.
     */
	public function testSaveToDifferent()
	{
	    $productList = new ProductList();
	    $product = new Product();
	    $product->setMake("MakeValue");
	    $product->setModel("ModelValue");
	    $product->setColour("ColourValue");
	    $product->setCapacity("CapacityValue");
	    $product->setNetwork("NetworkValue");
	    $product->setGrade("GradeValue");
	    $product->setCondition("ConditionValue");

	    $productList->addProduct($product);

	    $product1 = new Product();
	    $product1->setMake("MakeValue");
	    $product1->setModel("ModelValue");
	    $product1->setColour("ColourValue");
	    $product1->setCapacity("CapacityValue");
	    $product1->setNetwork("NetworkValue");
	    $product1->setGrade("GradeValue1");
	    $product1->setCondition("ConditionValue");

	    $productList->addProduct($product1);

	    $saveOutput = $this->createMock(OutputAdaptor::class);
        $saveOutput->expects($this->exactly(2))
            ->method("write")
            ->withConsecutive(
                [ $this->equalTo(["make" => "MakeValue",
                    "model" => "ModelValue",
                    "colour" => "ColourValue",
                    "capacity" => "CapacityValue",
                    "network" => "NetworkValue",
                    "grade" => "GradeValue",
                    "condition" => "ConditionValue",
                    "count" => "1"
                ])],
                [ $this->equalTo(["make" => "MakeValue",
                    "model" => "ModelValue",
                    "colour" => "ColourValue",
                    "capacity" => "CapacityValue",
                    "network" => "NetworkValue",
                    "grade" => "GradeValue1",
                    "condition" => "ConditionValue",
                    "count" => "1"
                    ]
                )]
            );

        $productList->saveTo($saveOutput);
	}

	/**
	 * Saving two of same products. This checks count of 2 is produced.
	 */
	public function testSaveToSame()
	{
	    $productList = new ProductList();
	    $product = new Product();
	    $product->setMake("MakeValue");
	    $product->setModel("ModelValue");
	    $product->setColour("ColourValue");
	    $product->setCapacity("CapacityValue");
	    $product->setNetwork("NetworkValue");
	    $product->setGrade("GradeValue");
	    $product->setCondition("ConditionValue");

	    $productList->addProduct($product);

	    $product1 = new Product();
	    $product1->setMake("MakeValue");
	    $product1->setModel("ModelValue");
	    $product1->setColour("ColourValue");
	    $product1->setCapacity("CapacityValue");
	    $product1->setNetwork("NetworkValue");
	    $product1->setGrade("GradeValue");
	    $product1->setCondition("ConditionValue");

	    $productList->addProduct($product1);

	    $saveOutput = $this->createMock(OutputAdaptor::class);
	    $saveOutput->expects($this->once())
    	    ->method("write")
    	    ->withConsecutive(
    	        [ $this->equalTo(["make" => "MakeValue",
    	            "model" => "ModelValue",
    	            "colour" => "ColourValue",
    	            "capacity" => "CapacityValue",
    	            "network" => "NetworkValue",
    	            "grade" => "GradeValue",
    	            "condition" => "ConditionValue",
    	            "count" => "2"
    	        ])]
    	   );

	    $productList->saveTo($saveOutput);
	}

}