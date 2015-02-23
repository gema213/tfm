<?php
// This file is part of Moodle - http://moodle.org/
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
 * Shoelace theme with the underlying Bootstrap theme.
 *
 * @package    theme
 * @subpackage shoelace
 * @copyright  &copy; 2013-onwards G J Barnard in respect to modifications of the Clean theme.
 * @author     G J Barnard - gjbarnard at gmail dot com and {@link http://moodle.org/user/profile.php?id=442195}
 * @author     Based on code originally written by Mary Evans, Bas Brands, Stuart Lamour and David Scotson.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    /* CDN Fonts - 1 = no, 2 = yes. */
    $name = 'theme_shoelace/cdnfonts';
    $title = get_string('cdnfonts', 'theme_shoelace');
    $description = get_string('cdnfonts_desc', 'theme_shoelace');
    $default = 1;
    $choices = array(
        1 => new lang_string('no'),   // No.
        2 => new lang_string('yes')   // Yes.
    );
    $settings->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

    // Invert Navbar to dark background.
    $name = 'theme_shoelace/invert';
    $title = get_string('invert', 'theme_shoelace');
    $description = get_string('invertdesc', 'theme_shoelace');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Logo file setting.
    $name = 'theme_shoelace/logo';
    $title = get_string('logo','theme_shoelace');
    $description = get_string('logodesc', 'theme_shoelace');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Number of marketing blocks.
    $name = 'theme_shoelace/nummarketingblocks';
    $title = get_string('nummarketingblocks','theme_shoelace');
    $description = get_string('nummarketingblocksdesc', 'theme_shoelace');
    $choices = array(
        1 => new lang_string('one', 'theme_shoelace'),
        2 => new lang_string('two', 'theme_shoelace'),
        3 => new lang_string('three', 'theme_shoelace'),
        4 => new lang_string('four', 'theme_shoelace')
    );
    $default = 2;
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);

    // Number of footer blocks.
    $name = 'theme_shoelace/numfooterblocks';
    $title = get_string('numfooterblocks','theme_shoelace');
    $description = get_string('numfooterblocksdesc', 'theme_shoelace');
    $choices = array(
        1 => new lang_string('one', 'theme_shoelace'),
        2 => new lang_string('two', 'theme_shoelace'),
        3 => new lang_string('three', 'theme_shoelace'),
        4 => new lang_string('four', 'theme_shoelace')
    );
    $default = 2;
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);

    // Custom CSS file.
    $name = 'theme_shoelace/customcss';
    $title = get_string('customcss', 'theme_shoelace');
    $description = get_string('customcssdesc', 'theme_shoelace');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Footnote setting.
    $name = 'theme_shoelace/footnote';
    $title = get_string('footnote', 'theme_shoelace');
    $description = get_string('footnotedesc', 'theme_shoelace');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
}
