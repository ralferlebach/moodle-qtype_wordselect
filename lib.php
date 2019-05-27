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
 * Serve question type files
 *
 * @since      2.0
 * @package    qtype_wordselect
 * @copyright  Marcus Green 2016

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once $CFG->libdir.'/formslib.php';

/**
 * Checks file access for wordselect questions.
 * @package  qtype_wordselect
 * @category files
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool
 */
function qtype_wordselect_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $CFG;
    require_once($CFG->libdir . '/questionlib.php');
    question_pluginfile($course, $context, 'qtype_wordselect', $filearea, $args, $forcedownload, $options);
}

class feedback_form extends \moodleform {
    //Add elements to form
    public function definition() {
 
        $mform = $this->_form; 
        $mform->addElement('html','<div id="item_feedback">');

        $mform->addElement('editor', 'correct', 'Correct', ['rows' => 4,'cols'=>50],'Correct', $this->editoroptions);
        $mform->addElement('editor', 'incorrect', 'Incorrect', ['rows' => 4,'cols'=>50], $this->editoroptions);

        $repeatarray = [];
        $repeatarray[] = $mform->createElement('text','response','Response',['size'=>50]);
        $repeatarray[] = $mform->createElement('editor','feedback','Feedback',['rows'=>2,'cols'=>50]);
        $repeateloptions = [];
        $START_REPETITIONS = 1;
        $this->repeat_elements($repeatarray, $START_REPETITIONS,
            $repeateloptions, 'extended_feedback_repeats', 'extended_feedback', 1, null, true);
        $this->add_action_buttons();
        $mform->addElement('html','</div>');

    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}


function qtype_wordselect_output_fragment_feedbackedit($args) {
    global $PAGE;
    $context = $args['context'];
    // if ($context->contextlevel != CONTEXT_COURSE) {
    //     return null;
    // }

    $output = $PAGE->get_renderer('core', '', RENDERER_TARGET_GENERAL);
    $mform= new feedback_form();
    
    if($mform->get_data()){
        return;
    }
    return $mform->render();

}
