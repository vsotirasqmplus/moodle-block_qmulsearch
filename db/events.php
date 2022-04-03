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
 * Block QMUL Course Search plugin event handler definition.
 *
 * @package block_qmul_search
 * @category event
 * @copyright 2022 Vasileios Sotiras {@link https://www.linkedin.com/in/vasileios-sotiras-msc-ba-hons-144a4028/}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// List of observers to trigger cache update.
$observers = [

        [
                'eventname' => '\core\event\user_enrolment_created',
                'callback' => '\block_qmulsearch\block_qmulsearch::update_cache',
        ],
        [
                'eventname' => '\core\event\user_enrolment_deleted',
                'callback' => '\block_qmulsearch\block_qmulsearch::update_cache',
        ],
        [
                'eventname' => '\core\event\user_enrolment_updated',
                'callback' => '\block_qmulsearch\block_qmulsearch::update_cache',
        ],
        [
                'eventname' => '\core\event\enrol_instance_updated',
                'callback' => '\block_qmulsearch\block_qmulsearch::update_cache',
        ],
        [
                'eventname' => '\core\event\role_assigned',
                'callback' => '\block_qmulsearch\block_qmulsearch::update_cache',
        ],
        [
                'eventname' => '\core\event\role_unassigned',
                'callback' => '\block_qmulsearch\block_qmulsearch::update_cache',
        ],
        [
                'eventname' => '\core\event\course_deleted',
                'callback' => '\block_qmulsearch\block_qmulsearch::update_cache',
        ],
        [
                'eventname' => '\core\event\course_updated',
                'callback' => '\block_qmulsearch\block_qmulsearch::update_cache',
        ],
];
