<?php
 
/**
 * Define all the backup steps that will be used by the backup_lips_activity_task
 */

/**
 * Define the complete lips structure for backup, with file and id annotations
 */     
class backup_lips_activity_structure_step extends backup_activity_structure_step {
 
    protected function define_structure() {
 
        // To know if we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');
 
        // Define each element separated
 
        // Build the tree
 
        // Define sources
 
        // Define id annotations
 
        // Define file annotations
 
        // Return the root element (choice), wrapped into standard activity structure
 
    }
}