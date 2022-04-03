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
 * @throws moodle_exception
 */
function block_qmulsearch_search_courses(string $search, array $courses): array {
    $found = [];
    if (strlen($search) < 3) {
        return $found;
    }

    foreach ($courses as $course) {
        if (strpos(strtoupper($course->idnumber . ' ' . $course->fullname), $search) !== false) {
            $found[] = [
                    'link' => (string) (new moodle_url('/course/view.php?id=' . $course->id)),
                    'text' => $course->fullname,
                    'visible' => $course->visible,
            ];
        }
    }
    return $found;
}

global $USER;
const AJAX_SCRIPT = true;
require_once(dirname(__FILE__) . '/../../config.php');
$data = json_decode(file_get_contents('php://input'), true);
$search = strtoupper($data['search']);
$where = $data['where'];
$sesskey = $data['sesskey'];
if ($sesskey !== sesskey() || strlen($search) < 3) {
    die;
}
$component = 'block_qmulsearch';
$cache = cache::make($component, 'searchcourses');
$allcache = cache::make($component, 'allsearchcourses');
$courses = [];
if ($where == 'allcourses') {
    $courses = $allcache->get('allmodules');
}
if ($where == 'mycourses') {
    $courses = $cache->get('mymodules_' . $USER->id);
}
// Prepare the links of the found courses.
$data = block_qmulsearch_search_courses($search, $courses);
if (!$data) {
    die;
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);