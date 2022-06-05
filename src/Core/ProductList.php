<?php
declare(strict_types=1);

namespace ECD\Core;

use ECD\OutputAdaptors\OutputAdaptor;

require_once __DIR__ . "/Product.php";
require_once __DIR__ . "/../OutputAdaptors/OutputAdaptor.php";

/**
 * A list of products, this will contain a product along with
 * the quantity of that particular product.
 *
 * This uses the Product::getUniqueIdentifier() method to determine
 * how products are grouped together.
 */
class ProductList
{
    /**
     * Associative array with the key as the product unique identifier.
     * Each value is a combination of the original Product record and
     * a count.
     * @var array
     */
    private $list = [];

    /**
     * Increments the count of this product in the list.  Adding
     * new elements as required.
     * @param Product $add
     */
    public function addProduct(Product $add) : void
    {
        // Get unique id for this product
        $productKey = $add->getUniqueIdentifier();
        if ( isset($this->list[$productKey]) )  {
            $this->list[$productKey]["count"]++;
        }
        else    {
            $this->list[$productKey] = [ "product" => $add, "count" => 1 ];
        }
    }


    /**
     * Saves the list of products (which includes the count) to the output.
     * @param OutputAdaptor $output
     */
    public function saveTo(OutputAdaptor $output) : void
    {
        foreach ( $this->list as $product ) {
            // Build array of data to write to output
            $data = $product['product']->getDataAsArray();
            $data["count"] = $product['count'];

            $output->write($data);
        }
    }

    /**
     * Returns the number of distinct products.
     * @return int
     */
    public function getCount() : int
    {
        return count($this->list);
    }
}