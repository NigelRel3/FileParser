<?php
declare(strict_types=1);

namespace ECD\Core;

use ECD\Core\Parameters;
use ECD\Core\Product;
use ECD\Core\ProductList;
use ECD\InputAdaptors\InputCSV;
use ECD\InputAdaptors\InputJSON;
use ECD\InputAdaptors\InputAdaptor;
use ECD\Translators\BaseTranslator;

require_once __DIR__ . '/Parameters.php';
require_once __DIR__ . '/Product.php';
require_once __DIR__ . '/ProductList.php';
require_once __DIR__ . '/../InputAdaptors/InputCSV.php';
require_once __DIR__ . '/../InputAdaptors/InputJSON.php';
require_once __DIR__ . '/../Translators/BaseTranslator.php';
require_once __DIR__ . '/../OutputAdaptors/OutputCSV.php';

/**
 * Main class dealing with parsing an input file and produce an output.
 */
class Parser
{
    /**
     * A set of the CLI parameters for this rum
     * @var Parameters
     */
    private $params = null;

    /**
     * The parameters which MUST be passed in to run the process.
     * @var string[]
     */
    private $requiredParams = ["input", "output"];

    /**
     * A list of all of the possible parameters.
     * @var string[]
     */
    private $allParams = ["reject", "csvfieldorder", "inputadaptor", "input", "output"];

    /**
     * The source of the data, configured in setAdaptors().
     * @var InputAdaptor
     */
    private $inputAdaptor = null;

    /**
     * The destination of the data, configured in setAdaptors().
     * @var OutputAdaptor
     */
    private $outputAdaptor = null;

    /**
     * @param Parameters $parameters
     * @throws \Exception when invalid parameters passed in.
     */
    public function __construct( Parameters $parameters )
    {
        $this->params = $parameters->getParameters();
        // Check required parameters present
        $parameterNames = array_keys($this->params);
        if ( count(array_intersect($this->requiredParams, $parameterNames)) !== 2 )	{
            throw new \Exception("Usage fileParser --input=example_1.csv --output=combination_count.csv");
        }

        // Check other parameters are at least valid
        if ( count(array_diff($parameterNames, $this->allParams)) !== 0 )	{
            throw new \Exception("Invalid parameters specified (" .
                implode(", ", $this->allParams) . ")");
        }

    }

    /**
     * Main processing method.
     * @throws \Exception may be raised at various stages.
     */
    public function parse()
    {
        $this->setAdaptors();

        // TODO be able to configure this from either adaptors or command line.
        $trans = new BaseTranslator();

        // Configure input adaptor with CLI parameters.
        $input = new $this->inputAdaptor($this->params["input"]);
        $productList = new ProductList();

        // Input adaptor uses a Generator to return succesive rows of data
        foreach( $input->read() as $row ) {
            $error = '';
            try {
                // Translate will throw an exception if data is invalid
                $product = $trans->translate($row);
                // Display data
                echo implode(",", $product->getDataAsArray()) . PHP_EOL;

                $productList->addProduct($product);
            }
            catch ( \Exception $e ) {
                // Any errors will show the input row followed by the message.
                // TODO output to reject file.
                $row[] = $e->getMessage();
                echo implode(",", $row) . PHP_EOL;
                // TODO assumption is to continue to process the file.
            }
        }
        $input->close();

        // Configure output adaptor from CLI parameters.
        $output = new $this->outputAdaptor($this->params["output"]);

        // Saves summarised product list to the output.
        $productList->saveTo($output);
        $output->close();
    }

    /**
     * This determines which output adaptors to use.
     * Currently it uses the extensions of the file names, this could be
     * configured by the command line.
     * @throws \Exception
     */
    private function setAdaptors() : void
    {
        // Which input adaptor to use
        $inputFile = $this->params["input"];
        $fileType = strtoupper(pathinfo($inputFile, PATHINFO_EXTENSION));
        // Adpator for input.csv will be ECD\InputAdaptors\InputCSV
        $inputAdaptor = "ECD\InputAdaptors\Input{$fileType}";

        // Check adaptor exists
        if ( class_exists($inputAdaptor) === false )	{
            // Note that inputadaptor is not yet implemented.
            throw new \Exception("Input adaptor for this file not found." . PHP_EOL.
                "Consider using --inputadaptor to hint which one to use.");
        }
        $this->inputAdaptor = new $inputAdaptor($inputFile);

        // Which output adaptor to use
        $outputFile = $this->params["output"];
        $fileType = strtoupper(pathinfo($outputFile, PATHINFO_EXTENSION));
        $outputAdaptor = "ECD\OutputAdaptors\Output{$fileType}";

        // Check adaptor exists
        if ( class_exists($outputAdaptor) === false )	{
            // Note that outputadaptor is not yet implemented.
            throw new \Exception("Output adaptor for this file not found." . PHP_EOL.
                "Consider using --outputadaptor to hint which one to use.");
        }
        $this->outputAdaptor = new $outputAdaptor($outputFile);
    }
}