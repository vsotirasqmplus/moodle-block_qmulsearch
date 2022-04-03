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

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/locallib.php');

class block_qmulsearch extends block_base {

    /**
     * This prevents it getting added to the normal Add a Block UI
     * selector and forces the user to set the block using the settings
     * on the category level
     *
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    public function applicable_formats(): array {
        if (has_capability('moodle/site:config', context_system::instance())) {
            return array('all' => true);
        } else {
            return array('site' => true);
        }
        // Note that this is a made up page pattern type.
        // We want users to add the block via the settings only.
        // return array('mod-somewhere' => true);
    }

    function instance_allow_multiple(): bool {
        return false;
    }

    /**
     * Config and settings are handled in the plugin
     *
     * @return bool
     */
    function has_config(): bool {
        return true;
    }

    /**
     * @throws coding_exception
     * @throws dml_exception
     */
    public function init() {
        global $DB, $USER;
        $pluginname = get_string('pluginstring', __CLASS__);
        $this->title = get_string('pluginname', $pluginname);

        $cache = cache::make(__CLASS__, 'searchcourses');
        $allcache = cache::make(__CLASS__, 'allsearchcourses');

        $cache->set('mymodules_' . $USER->id, enrol_get_my_courses());
        $allcache->set('allmodules', $DB->get_records('course', null, '', 'id, idnumber, fullname, visible'));
    }

    /**
     * @throws coding_exception
     */
    public function get_content() {
        global $PAGE, $USER, $CFG;
        $search = optional_param('blockcoursesearch', '', PARAM_NOTAGS);
        $this->arialabel = $this->title;
        $this->str = $this->title;
        $this->content = new stdClass;
        $this->content->text = $this->instance->id . "<br>" . $this->context->depth . "<br>" . $this->context->path;
        $dir = $CFG->wwwroot . str_replace($CFG->dirroot, '', __DIR__);
        $query = $dir . '/query.php';
        $sesskey = sesskey();
        $my = block_qmulsearch_get_string('mycourses');
        $all = block_qmulsearch_get_string('allcourses');
        $this->content->text = <<<FORM
<form id="coursesearchform" name="coursesearch" method="post" action="{$PAGE->url->get_path()}">
    <input type="text" name="blockcoursesearch" id="blockcoursesearch" value="$search" style="width: 13rem; margin-bottom: 20px;">
    <button type="submit" name="none" value="none">&#10005;</button>
    <button type="submit" name="mycourses" value="mycourses">$my</button>
    <button type="submit" name="allcourses" value="allcourses">$all</button>
</form>
<div class="hidden qmulsearchresults" id="matchingcourses"></div>
<script>
    const f = document.getElementById("coursesearchform");
    f.onsubmit = function(event){
        const text = document.getElementById('blockcoursesearch');
        const target = document.getElementById('matchingcourses');
        const ul = document.createElement('ul');
        target.innerHTML = '';
        target.classList.add('hidden');
        if(event.submitter.value === 'none'){
            text.value = '';
            return false;
        }
        if(f.blockcoursesearch.value.length < 3){
            return false;
        }
        const data = {
            'search' : f.blockcoursesearch.value,
            'where' : event.submitter.value,
            'sesskey' : '{$sesskey}'
        };
        // POST request with data in JSON format.
        fetch('${query}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(data),
        })
        .then((response) => response.json())
        // Then with the data from the response in JSON...
        .then((datafound) => {
          // Read the response and create a list of links
          target.innerHTML = '';
          target.classList.add('hidden');
          if(datafound.length > 0) {
              target.appendChild(ul);
              for(let key in datafound) {
                  let li = document.createElement('li');
                  let a = document.createElement('a');
                  let text = document.createTextNode(datafound[key].text);
                  a.appendChild(text);
                  a.href = datafound[key].link;
                  a.title = datafound[key].text;
                  if(datafound[key].visible == 0){
                      a.classList.add('disabled');
                  }
                  li.appendChild(a);
                  ul.appendChild(li);
              }
              if(target.innerHTML.length > 0) {
                  target.classList.remove('hidden');
              }
          }
        })
        //Then with the error genereted...
        .catch((error) => {
          // console.error('Error:', error);
        });
    
        
        return false;
    }
</script>
FORM;
        $this->content->footer = block_qmulsearch_get_string('footer');
        $this->content->items = null;
        $this->content->icons = null;
        return $this->content;
    }
}