<?php

namespace block_qmulsearch;

use cache;
use coding_exception;
use dml_exception;

defined('MOODLE_INTERNAL') || die();
global $CFG;
$dir = $CFG->wwwroot . str_replace($CFG->dirroot, '', __DIR__);
require_once($dir . '/../locallib.php');

class block_qmulsearch {
    /**
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function update_cache(): void {
        global $DB, $USER;
        $pluginname = block_qmulsearch_get_string('pluginstring', 'block_qmulsearch');

        $cache = cache::make($pluginname, 'searchcourses');
        $allcache = cache::make($pluginname, 'allsearchcourses');

        $cache->set('mymodules_' . $USER->id, enrol_get_my_courses());
        $allcache->set('allmodules', $DB->get_records('course', null, '', 'id, idnumber, fullname, visible'));
    }
}