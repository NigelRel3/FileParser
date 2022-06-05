<?php
declare(strict_types=1);

namespace ECD\Translators;

use ECD\Core\Product;

require_once __DIR__ . '/Translator.php';
require_once __DIR__ . '/../Core/Product.php';

/**
 * Translator which relies on a simple mapping from the input associative
 * array to the Product details.
 * It is a plain 1 input field is copied to 1 field in the Product mapping.
 *
 *  TODO should the input adaptor be able to set a default translator?
 *  That would allow a more direct relationship between the input and how
 *  it needs to be coverted.
 *  Should also allow command line to override default, even if set by
 *  the input adaptor.
 */

class BaseTranslator implements Translator
{
    /**
     * Default key names to be used for standard format Product.
     * See translate() to see how they are used and relate to the
     * product fields themselves.
     * @var string[]
     */
    private $translations = [
        "brand_name", "model_name", "colour_name", "gb_spec_name",
        "network_name", "grade_name", "condition_name"
    ];

    /**
     * Passed an array of column names in the order they should be extracted
     * for the product details.
     * @param string[] $format
     */
    public function setTranslation ( array $format ) : void
    {
       $this->translations = $format;
    }

    /**
     * Taking data from an input array, format it to create a Product.
     * @param array $input
     * @return Product
	 * @throws \Exception if any setter fails (i.e. missing data).
     */
    public function translate( array $input ) : Product
    {
        $newProduct = new Product();
        $trans = $this->translations;
        // TODO missing (as opposed to just empty) fields are assumed empty,
        //      should they throw an exception?
        $newProduct->setMake($input[$trans[0]] ?? '');
        $newProduct->setModel($input[$trans[1]] ?? '');
        $newProduct->setColour($input[$trans[2]] ?? '');
        $newProduct->setCapacity($input[$trans[3]] ?? '');
        $newProduct->setNetwork($input[$trans[4]] ?? '');
        $newProduct->setGrade($input[$trans[5]] ?? '');
        $newProduct->setCondition($input[$trans[6]] ?? '');

        return $newProduct;
    }
}