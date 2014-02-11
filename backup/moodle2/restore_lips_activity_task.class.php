<?

/**
 * lips restore task that provides all the settings and steps to perform one
 * complete restore of the activity
 */
 
require_once($CFG->dirroot . '/mod/lips/backup/moodle2/restore_lips_stepslib.php');
 
class restore_lips_activity_task extends restore_activity_task {
 
    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity
    }
 
    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        $this->add_step(new restore_lips_activity_structure_step('lips_structure', 'lips.xml'));
    }
 
    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    static public function define_decode_contents() {
        $contents = array();
 
        // $contents[] = new restore_decode_content('lips', array('intro'), 'lips');
 
        return $contents;
    }
 
    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder
     */
    static public function define_decode_rules() {
        $rules = array();
 
        // $rules[] = new restore_decode_rule('CHOICEVIEWBYID', '/mod/choice/view.php?id=$1', 'course_module');
        // $rules[] = new restore_decode_rule('CHOICEINDEX', '/mod/choice/index.php?id=$1', 'course');
 
        return $rules;
 
    }
 
    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * lips logs. It must return one array
     * of {@link restore_log_rule} objects
     */
    static public function define_restore_log_rules() {
        $rules = array();
 
        // $rules[] = new restore_log_rule('choice', 'add', 'view.php?id={course_module}', '{choice}');
        // $rules[] = new restore_log_rule('choice', 'update', 'view.php?id={course_module}', '{choice}');
        // $rules[] = new restore_log_rule('choice', 'view', 'view.php?id={course_module}', '{choice}');
        // $rules[] = new restore_log_rule('choice', 'choose', 'view.php?id={course_module}', '{choice}');
        // $rules[] = new restore_log_rule('choice', 'choose again', 'view.php?id={course_module}', '{choice}');
        // $rules[] = new restore_log_rule('choice', 'report', 'report.php?id={course_module}', '{choice}');
 
        return $rules;
    }
 
    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();
 
        // Fix old wrong uses (missing extension)
        // $rules[] = new restore_log_rule('choice', 'view all', 'index?id={course}', null,
        //                                 null, null, 'index.php?id={course}');
        // $rules[] = new restore_log_rule('choice', 'view all', 'index.php?id={course}', null);
 
        return $rules;
    }
 
}