<?php
declare(strict_types=1);

namespace ECD\Translators;

use ECD\Core\Product;

require_once __DIR__ . '/../Core/Product.php';

interface Translator
{
    /**
     * Passed an array of column names in the order they should be extracted
     * for the product details.
     * @param string[] $format
     */
    public function setTranslation ( array $format ) : void;

    /**
     * Taking data from an input array, format it to create a Product.
     * @param array $input
     * @return Product
	 * @throws \Exception on any failure.
     */
    public function translate( array $input ) : Product;
}