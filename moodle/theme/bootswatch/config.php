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
 * Theme Bootswatch config file.
 *
 * @package    theme_bootswatch
 * @copyright  2014 Bas Brands
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$THEME->name = 'bootswatch';
$THEME->parents = array('bootstrap');

$THEME->doctype = 'html5';
$THEME->sheets = array('custom');
if (!empty($THEME->settings->bootswatch)) {
    $THEME->lessfile = $THEME->settings->bootswatch;
} else {
    $THEME->lessfile = 'default';
}
$THEME->parents_exclude_sheets = array('bootstrap' => array('moodle'));
$THEME->lessvariablescallback = 'theme_bootswatch_less_variables';
$THEME->extralesscallback = 'theme_bootswatch_extra_less';
$THEME->supportscssoptimisation = false;
$THEME->yuicssmodules = array();
$THEME->enable_dock = true;
$THEME->editor_sheets = array();

$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->csspostprocess = 'theme_bootswatch_process_css';