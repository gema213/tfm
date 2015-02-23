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
 * Theme Bootswatch version file.
 *
 * @package    theme_bootswatch
 * @copyright  2014 Bas Brands
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/theme/bootswatch/lib.php');

if ($ADMIN->fulltree) {

        // Selected Bootswatch
    $options = theme_bootswatch_get_swatches();
    $name = 'theme_bootswatch/bootswatch';
    $title = get_string('bootswatch', 'theme_bootswatch');
    $description = get_string('bootswatchdesc', 'theme_bootswatch');
    $description .= html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('bootswatch', 'theme')));
    $default = '0';
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);


    // Logo file setting.
    $name = 'theme_bootswatch/logo';
    $title = get_string('logo', 'theme_bootswatch');
    $description = get_string('logodesc', 'theme_bootswatch');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Custom CSS file.
    $name = 'theme_bootswatch/customcss';
    $title = get_string('customcss', 'theme_bootswatch');
    $description = get_string('customcssdesc', 'theme_bootswatch');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

}
