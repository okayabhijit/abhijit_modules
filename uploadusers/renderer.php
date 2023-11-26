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
 * Render output html
 *
 * @package   local_uploadusers
 * @author    Abhijit singh (okabhijitsingh@gmail.com)
 * @copyright 2023 Abhijit singh
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once('lib.php');

class local_uploadusers_renderer extends plugin_renderer_base {

    protected $emailstatusheadings = ['S.no.', 'First Name', 'Last Name', 'Email', 'Email Status', 'Email Time'];
    protected $importuserheadings = ['S.no.', 'First Name', 'Last Name', 'Email', 'Time Created', 'Status'];

    /**
     * Get HTML link for uploading users
     *
     * @return string HTML link
     */
    public function get_upload_link() {
        $uploadlink = 'index.php';
        return html_writer::link(
            $uploadlink,
            get_string('uploadusers', 'local_uploadusers'),
            ['class' => 'alert alert-info mt-3']
        );
    }

    /**
     * Get HTML link for viewing email sent status
     *
     * @return string HTML link
     */
    public function get_sentemail_link() {
        $emailstatuslink = 'emailstatus.php';
        return html_writer::link(
            $emailstatuslink,
            get_string('email_sent', 'local_uploadusers'),
            ['class' => 'alert alert-info mt-3']
        );
    }

    /**
     * Generate HTML table for displaying imported CSV user data
     *
     * @param array $userdata User data from CSV
     * @return string HTML table
     */
    public function csv_user_import_table($userdata) {
        $table = new html_table();
        $table->head = $this->importuserheadings;
        $table->data = [];

        $i = 0;
        foreach ($userdata as $user) {
            $row = [
                ++$i,
                format_string($user->firstname),
                format_string($user->lastname),
                format_string($user->email),
                userdate($user->timecreated),
                format_string($user->success === true ? 'Saved' : 'Failed'),
            ];
            $table->data[] = $row;
        }

        $output = html_writer::tag('h2', get_string('usertableheading', 'local_uploadusers'));
        $output .= html_writer::table($table);

        return $output;
    }

    /**
     * Generate HTML table for displaying email sent status
     *
     * @return string HTML table
     */
    public function email_sent_status_table() {
        $users = get_imported_users();

        $table = new html_table();
        $table->head = $this->emailstatusheadings;
        $table->data = [];

        $i = 0;
        foreach ($users as $user) {
            $row = [
                ++$i,
                format_string($user->firstname),
                format_string($user->lastname),
                format_string($user->email),
                format_string($user->emailsent > 0 ? 'Sent' : 'Not sent'),
                $user->emailsent > 0 ? userdate($user->emailsent) : '--',
            ];
            $table->data[] = $row;
        }

        $output = self::get_random_user_email_information();
        $output .= html_writer::table($table);

        return $output;
    }

    /**
     * Get information about random user email setting
     *
     * @return string HTML content
     */
    public function get_random_user_email_information() {
        $config = get_config('local_uploadusers');

        $text = get_string('random_user_email', 'local_uploadusers');
        $text .= $config->sendrandomemail == "1" ? " 'on'" : " 'off'";

        $output = html_writer::tag('i', $text);

        return $output;
    }
}
