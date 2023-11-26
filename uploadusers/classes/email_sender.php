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
 * Email sending handler class
 *
 * @package   local_uploadusers
 * @author    Abhijit singh (okabhijitsingh@gmail.com)
 * @copyright 2023 Abhijit singh
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_uploadusers;

/**
 * Handle the email sending to user
 */
class email_sender {

    const DEFAULT_EMAIL_SEND_LIMIT = 5; // Default email send limit.

    /**
     * Method to send queued emails to handler method
     */
    public static function send_queued_emails () {
        global $USER;

        $cloneuser = clone($USER);
        $queuedemails = self::get_queued_emails ();

        foreach ($queuedemails as $userinfo) {
            $cloneuser->email = $userinfo->email;
            $cloneuser->lastname  = $userinfo->lastname;
            $cloneuser->firstname = $userinfo->firstname;

            $emailtext = get_string('email_message', 'local_uploadusers');
            $emailsubject = get_string('email_subject', 'local_uploadusers');

            $emailstatus = self::send_email($cloneuser, $USER, $emailsubject, $emailtext);

            self::update_email_status($emailstatus, $userinfo);
        }
    }

    /**
     * Method to retrieve queued emails
     * @return array set of users object.
     */
    private static function get_queued_emails () {
        global $DB;

        // Define conditions for retrieving queued emails.
        $conditions = ['emailsent' => 0];
        $limit = self::DEFAULT_EMAIL_SEND_LIMIT;
        $config = get_config('local_uploadusers');

        // Query to get queued emails based on configuration.
        if ($config->sendrandomemail == "1") {
            $users = $DB->get_records_sql("
                        SELECT *
                        FROM {csv_users}
                        WHERE emailsent = :emailsent
                        ORDER BY RAND()
                        LIMIT $limit
                    ", ['emailsent' => $conditions['emailsent']]);
        } else {
            $users = $DB->get_records('csv_users', $conditions, '', '*', 0, $limit);
        }

        return $users;
    }

    /**
     * Send email handler
     *
     * @param stdClass $userinfo  A {@link $USER} object
     * @param stdClass $from A {@link $cloneuser} object
     * @param string $emailsubject plain text subject line of the email.
     * @param string $emailtext plain text version of the message.
     * @return bool true if the email succeed. false otherwise.
     */
    private static function send_email ($userinfo, $from, $emailsubject, $emailtext) {

        if (!email_to_user($userinfo, $from, $emailsubject, '', $emailtext)) {
            $emailstatus = false;
            ob_start();
            ob_get_contents();
            ob_end_clean();
        } else {
            $emailstatus = true;
        }

        return $emailstatus;
    }

    /**
     * Method to update email status in the database
     *
     * @param bool $emailstatus.
     * @param stdClass $user A {@link $userinfo} object
     */
    private static function update_email_status ($emailstatus, $user) {
        global $DB;

        // If email succeed, update email sent timestamp.
        if ($emailstatus === true) {
            $user->emailsent = time();
            $DB->update_record('csv_users', $user);
        }
    }
}
