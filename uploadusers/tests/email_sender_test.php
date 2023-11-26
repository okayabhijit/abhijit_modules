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
 * Tests for the email_sender class methods.
 *
 * @package   local_uploadusers
 * @author    Abhijit singh (okabhijitsingh@gmail.com)
 * @copyright 2023 Abhijit singh
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_uploadusers;

use advanced_testcase;
use local_uploadusers\email_sender;

/**
 * Unit tests for the email_sender class.
 *
 * @group local_uploadusers
 */
class email_sender_test extends advanced_testcase {

    /**
     * Test the send_queued_emails method.
     * @covers \local_uploadusers\email_sender::send_queued_emails
     */
    public function test_send_queued_emails() {
        // Create a test user and add them to the queue.
        $user = $this->getDataGenerator()->create_user();
        $user->emailsent = 0;
        $this->getDataGenerator()->update_record('csv_users', $user);

        // Call the method to send queued emails.
        email_sender::send_queued_emails();

        // Assert that the email status is updated.
        $updateduser = $this->getDataGenerator()->get_record('csv_users', ['id' => $user->id]);
        $this->assertNotEquals(0, $updateduser->emailsent);
    }
}
