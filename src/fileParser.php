<?php
declare(strict_types=1);

namespace ECD;

use ECD\Core\Parser;
use ECD\Core\Parameters;

/**
 * Developed using PHP Version => 7.4.3
 *
 * Usage fileParser --input=example_1.csv --output=combination_count.csv
 *   --input - file name to read from
 *   --output - file name to write to
 *   --reject - file name to write any rejected lines to
 *   --inputadaptor - which adaptor to use (defaults to file extension)
 *   --outputadaptor - which adaptor to use (defaults to file extension)
 */

require_once __DIR__ . '/Core/Parser.php';
require_once __DIR__ . '/Core/Parameters.php';

// Convert CLI parameters ($argv) into a Parameter object.
$cliParams = new Parameters($argv);

$parser = new Parser($cliParams);
$parser->parse();
