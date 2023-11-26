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
 * Schedule task to send email to users
 *
 * @package   local_uploadusers
 * @author    Abhijit singh (okabhijitsingh@gmail.com)
 * @copyright 2023 Abhijit singh
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_uploadusers\task;

class send_email extends \core\task\scheduled_task {

    public function get_name () {
        return get_string('random_user_email', 'local_uploadusers');
    }

    public function execute () {
        // Call the send_queued_emails method from the email_sender class.
        \local_uploadusers\email_sender::send_queued_emails();
    }
}
