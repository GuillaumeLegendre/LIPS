<?php
/**
 * Created by PhpStorm.
 * User: mickael
 * Date: 07/01/14
 * Time: 20:24
 */
require_once "$CFG->libdir/tablelib.php";
require_once "$CFG->libdir/outputrenderers.php";


class categories_table extends table_sql{


    function other_cols($colname, $attempt){
        global $OUTPUT;

        if($colname=="category_name") {
            $url=new action_link(new moodle_url("ff"),$attempt->category_name);
            return $OUTPUT->render($url);
        }

        if($colname=="actions") {
            $pic=new pix_icon("t/delete","help");
            $pic2=new pix_icon("t/edit","help");
            return $OUTPUT->render($pic2)." ".$OUTPUT->render($pic);
        }
        return NULL;
    }

} 