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
 * methods collection
 *
 * @package   local_uploadusers
 * @author    Abhijit singh (okabhijitsingh@gmail.com)
 * @copyright 2023 Abhijit singh
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Check if the current user can access this plugin's pages
 *
 * @throws \moodle_exception If the user does not have site admin access
 */
function check_plugin_accessibility () {
    if (!is_siteadmin()) {
        throw new \moodle_exception('accesserror', 'local_uploadusers', '', get_string('access_error', 'local_uploadusers'));
    }
}

/**
 * Handle CSV file upload
 *
 * @param string $csvfile The content of the CSV file
 * @return array An array containing user data
 * @throws \moodle_exception If there is an error loading the CSV file
 */
function handle_csv_file ($csvfile) {
    global $CFG;
    require_once($CFG->libdir . '/csvlib.class.php');

    // Create a new CSV import reader instance.
    $id = csv_import_reader::get_new_iid('uploadusers');
    $cir = new csv_import_reader($id, 'uploadusers');

    // Load CSV content from the file.
    $content = $csvfile;
    $csvloadcontent = $cir->load_csv_content($content, 'utf-8', ',');
    $csvloaderror = $cir->get_error();

    // If there is a CSV load error, throw an exception with an import error message.
    if (!is_null($csvloaderror)) {
        throw new \moodle_exception('importerror', 'local_uploadusers', '', get_string('import_error', 'local_uploadusers'));
    }

    // Initialize the CSV import reader.
    $cir->init();

    // Call a function to insert user data into the database.
    $userdata = insert_user_data($cir);

    return $userdata;
}

/**
 * Insert user data into the database
 *
 * @param object $cir CSV import reader object
 * @return array An array containing user data
 */
function insert_user_data ($cir) {
    global $DB;

    $userdata = [];

    // Iterate through the csv loaded data.
    while ($fields = $cir->next()) {
        // Insert the data into the database.
        $user = new stdClass();
        $user->firstname = $fields[0];
        $user->lastname = $fields[1];
        $user->email = $fields[2];
        $user->timecreated = time();
        $userdata[] = $user;
        $user->success = $DB->insert_record('csv_users', $user) ? true : false;
    }

    return $userdata;
}

/**
 * Get all imported users from the database
 *
 * @return mixed The users packet
 */
function get_imported_users () {
    global $DB;
    return $DB->get_records('csv_users');
}
