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
 * page to view email status for users
 *
 * @package   local_uploadusers
 * @author    Abhijit singh (okabhijitsingh@gmail.com)
 * @copyright 2023 Abhijit singh
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot .'/local/uploadusers/lib.php');

// Ensure user is logged in, and check plugin accessibility.
require_login();
check_plugin_accessibility();

// Set up page details.
$PAGE->set_pagelayout('standard');
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/uploadusers/sentemail.php'));
$PAGE->set_title(get_string('sentemail_title', 'local_uploadusers'));
$PAGE->set_heading(get_string('sentemail_heading', 'local_uploadusers'));

// Get renderer for the local_uploadusers plugin.
$renderer = $PAGE->get_renderer('local_uploadusers');

echo $OUTPUT->header();

// Display upload link and email sent status table using the renderer.
echo $renderer->get_upload_link();
echo $renderer->email_sent_status_table();

echo $OUTPUT->footer();
