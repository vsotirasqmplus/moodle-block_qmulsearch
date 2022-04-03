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
$capabilities = array(

    /**
     * Note - not using clone permissions moodle/my:manageblocks as this
     * would add Teachers to the list. Don't want that. Course Admins will have
     * to be added manually.
     */
        'block/qmulsearch:addinstance' => array(
                'riskbitmask' => RISK_CONFIG,
                'captype' => 'write',
                'contextlevel' => CONTEXT_BLOCK,
                'archetypes' => array(
                        'manager' => CAP_ALLOW
                ),
        ),
        'block/qmulsearch:myaddinstance' => array(
                'captype' => 'write',
                'contextlevel' => CONTEXT_SYSTEM,
                'archetypes' => array(
                        'user' => CAP_PROHIBIT
                ),

                'clonepermissionsfrom' => 'moodle/my:manageblocks'
        ),

        'block/qmulsearch:editsettings' => array(
                'captype' => 'write',
                'contextlevel' => CONTEXT_SYSTEM,
                'archetypes' => array(
                        'manager' => CAP_ALLOW
                ),
        ),

);