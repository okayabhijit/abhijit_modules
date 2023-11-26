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
 * settings page
 *
 * @package   local_uploadusers
 * @author    Abhijit singh (okabhijitsingh@gmail.com)
 * @copyright 2023 Abhijit singh
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_category('local_uploadusers', new lang_string('pluginname', 'local_uploadusers')));

    $settings = new admin_settingpage('local_uploadusers_settings', get_string('pluginname', 'local_uploadusers'));

    $settings->add(new admin_setting_configcheckbox(
        'local_uploadusers/sendrandomemail',
        get_string('sendrandomemail', 'local_uploadusers'),
        get_string('sendrandomemail_desc', 'local_uploadusers'),
        0
    ));

    $ADMIN->add('local_uploadusers', $settings);
}


