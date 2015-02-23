<?php
// This file is part of the Bootswatch theme for Moodle
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme bootswatch lib.
 *
 * @package    theme_bootswatch
 * @copyright  2014 Bas Brands
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);
define('CACHE_DISABLE_ALL', true);

require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once($CFG->libdir.'/clilib.php');
require_once($CFG->dirroot.'/theme/bootswatch/lib.php');

// now get cli options
list($options, $unrecognized) = cli_get_params(
    array(
        'all'   => false,
        'custom'    => false,
        'help'      => false
    ),
    array(
        'h' => 'help'
    )
);

if ($options['help']) {
    $help =
"Command line Bootswatch Swatch generation.

This script generates the bootswatch files for the Moodle bootswatch theme from the original upstream bootswatch project.

Options:
--all            Generate all default bootswatch files (default)
--custom         name of your custom bootswatch
-h, --help            Print out this help

Example:
\$/usr/bin/php gen_swatchfiles.php --all
";

    echo $help;
    die;
}


$all = $options['all'];
$custom = $options['custom'];

$defaultswatches = theme_bootswatch_get_swatches();

$swatchpath = $CFG->dirroot . '/theme/bootswatch/less/';
$pixpath =  $CFG->dirroot . '/theme/bootswatch/pix/';

if ($custom) {
    echo "Not implemented yet";
} 

if ($all) {
    foreach ($defaultswatches as $swatch) {
        if (file_exists($swatchpath . 'bootswatch/' .$swatch)) {
            // Generate the Swatch files
            $swatchfile = $swatchpath . $swatch . '.less';
            $content = '@import "../../bootstrap/less/moodle.less";' . "\n";
            $content .= '@import "bootswatch/'.$swatch.'/variables.less";' . "\n";
            $content .= '@import "bootswatch/'.$swatch.'/bootswatch.less";' . "\n";
            $content .= '@import "glyphicons.less";' . "\n";
            $fh = fopen($swatchfile, 'w');
            fwrite($fh, $content);
            fclose($fh);
            echo "Added " . $swatch . " \n";

            $pixfile = $swatchpath . 'bootswatch/' . $swatch . '/thumbnail.png';
            $destpath = $pixpath  . $swatch;
            $destfile = $pixpath  . $swatch . '/thumbnail.png';

            if (is_file($pixfile)) {
                mkdir($destpath);
                $content=file_get_contents($pixfile);
                file_put_contents($destfile, $content);
            } 
            echo "done";
        }
    }
}
