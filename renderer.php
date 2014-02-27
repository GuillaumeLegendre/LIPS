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
 * @package   mod_lips
 * @copyright 2014 LIPS
 *
 * @author Valentin Got
 * @author Guillaume Legendre
 * @author Mickael Ohlen
 * @author Anaïs Picoreau
 * @author Julien Senac
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/locallib.php');

class mod_lips_renderer extends plugin_renderer_base {

    /**
     * Display the tab tree
     *
     * @param string $activetab Current active tab
     * @return array Tab tree
     */
    public function tabs($activetab) {
        $id = $this->page->cm->id;
        $context = context_module::instance($id);

        $tabs[] = new tabobject('index', "view.php?id={$id}&amp;view=index", get_string('index', 'lips'));
        $tabs[] = new tabobject('problems', "view.php?id={$id}&amp;view=problems", get_string('problems', 'lips'));
        $tabs[] = new tabobject('users', "view.php?id={$id}&amp;view=users", get_string('users', 'lips'));
        $tabs[] = new tabobject('rank', "view.php?id={$id}&amp;view=rank", get_string('rank', 'lips'));
        $tabs[] = new tabobject('profile', "view.php?id={$id}&amp;view=profile", get_string('profile', 'lips'));

        if (has_capability('mod/lips:administration', $context)) {
            $tabs[] = new tabobject('administration', "view.php?id={$id}&amp;view=administration", get_string('administration', 'lips'));
        }

        return $this->tabtree($tabs, convert_active_tab($activetab));
    }

    /**
     * Display an H1 title
     *
     * @param string $title Title content
     * @param array $attributes Title attributes
     * @return string H1 tag
     */
    public function display_h1($title, array $attributes = null) {
        return html_writer::tag('h1', format_string($title), $attributes);
    }

    /**
     * Display an H2 title
     *
     * @param string $title Title content
     * @param array $attributes Title attributes
     * @return string H2 tag
     */
    public function display_h2($title, array $attributes = null) {
        $html = html_writer::tag('h2', format_string($title), $attributes);
        $html .= html_writer::tag('div', null, array('class' => 'h2_sub'));

        return $html;
    }

    /**
     * Display an H3 title
     *
     * @param string $title Title content
     * @param array $attributes Title attributes
     * @param boolean $sub Display a sub on the bottom of the title
     * @return string H3 tag
     */
    public function display_h3($title, array $attributes = null, $sub = true) {
        $html = html_writer::tag('h3', format_string($title), $attributes);
        if ($sub) {
            $html .= html_writer::tag('div', null, array('class' => 'h3_sub'));
        }
        return $html;
    }

    /**
     * Display a "p" tag
     *
     * @param string $content Text content
     * @param array $attributes Attributes
     * @return string P tag
     */
    public function display_p($content, array $attributes = null) {
        return html_writer::tag('p', $content, $attributes);
    }

    /**
     * Display an image
     *
     * @param string $src Image source
     * @param array $attributes Image attributes
     * @return string Img tag
     */
    public function display_img($src, array $attributes = array()) {
        return html_writer::tag('img', null, array_merge(array('src' => './images/' . $src), $attributes));
    }

    /**
     * Display the administration menu
     *
     * @return string The Administration menu
     */
    public function display_administration_menu() {
        $id = $this->page->cm->id;
        $view = optional_param('view', 0, PARAM_TEXT);

        $administrationmenu = '<ul id="administration_menu">';
        if (has_role('adminplugin')) {
            $administrationmenu .= '<li><a href="#">' . get_string('language', 'lips') . '</a>
                <ul>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=language_configure">' . get_string('configure', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=language_picture">' . get_string('picture', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=language_base">' . get_string('base', 'lips') . '</a></li>
                </ul>
            </li>';
        }

        $administrationmenu .= '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=achievement_select">Badges</a></li>
            <li><a href="#">Catégories</a>
                <ul>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=category_create">' . get_string('create', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=category_select_modify">' . get_string('modify', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=category_delete">' . get_string('delete', 'lips') . '</a></li>
                </ul>
            </li>
            <li><a href="#">Problèmes</a>
                <ul>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=problem_create">' . get_string('create', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=problem_select_modify">' . get_string('modify', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=problem_category_select_delete">' . get_string('delete', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=problems_import">' . get_string('import', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=problems_export">' . get_string('export', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=my_problems">' . get_string('administration_my_problems_title', 'lips') . '</a></li>
                </ul>
            </li>
        </ul>';

        return $administrationmenu;
    }

    /**
     * Display the profile menu
     *
     * @param string $current Current action
     * @return string Profile menu
     */
    public function display_profile_menu($current) {
        global $USER, $PAGE;

        $id = $this->page->cm->id;
        $view = optional_param('view', null, PARAM_TEXT);
        $action = optional_param('action', 'profile', PARAM_TEXT);
        $iduser = optional_param('id_user', null, PARAM_TEXT);

        // Infos
        $lips = get_current_instance();
        $currentuserdetails = get_user_details(array('id_user_moodle' => $USER->id));
        $userdetails = ($iduser == null) ? $currentuserdetails : get_user_details(array('id' => $iduser));
        $moodleuserdetails = get_moodle_user_details(array('id' => $userdetails->id_user_moodle));
        $rank = get_rank_details(array('id' => $userdetails->user_rank_id));
        $userpicture = get_user_picture_url(array('id_user_moodle' => $moodleuserdetails->id), 'f1');
        $userstatus = get_user_status($userdetails->id, $lips->id);

        $menu = '<div id="profile-menu"><img src="' . $userpicture . '" id="picture"/>';
        if ($iduser != null && $iduser != $currentuserdetails->id) {
            if(is_following($currentuserdetails->id, $userdetails->id)) {
                $menu .= $this->render(new action_link(new moodle_url('action.php', array(
                    'id' => $this->page->cm->id,
                    'action' => 'unfollow',
                    'originV' => 'profile',
                    'originUser' => $userdetails->id,
                    'to_unfollow' => $userdetails->id
                )),
                get_string('unfollow', 'lips'), null, array("class" => "lips-button right-button", "style" => "margin-left: 15px")));
            } else {
                $menu .= $this->render(new action_link(new moodle_url('action.php', array(
                    'id' => $this->page->cm->id,
                    'action' => 'follow',
                    'originV' => 'profile',
                    'originUser' => $userdetails->id,
                    'to_follow' => $userdetails->id
                )),
                get_string('follow', 'lips'), null, array("class" => "lips-button right-button", "style" => "margin-left: 15px")));
            }

            $menu .= $this->render(new action_link(new moodle_url('#'), get_string('send_message', 'lips'), null, array("class" => "lips-button right-button")));
        }

        $menu .= '<div id="background">
            <div id="infos">
                <div id="role">' . ((isset($userstatus->user_rights_status)) ? get_string($userstatus->user_rights_status, 'lips') : get_string('student', 'lips')) . '</div>
                <div id="rank">' . $rank->rank_label . '</div>
            </div>
            <div id="user">' . ucfirst($moodleuserdetails->firstname) . ' ' . ucfirst($moodleuserdetails->lastname) . '</div>
        </div>
        <ul id="links">';

        $menu .= ($action == 'profile') ? '<li><p class="current">Profil</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . (($iduser != null) ? '&amp;id_user=' . $iduser : '') . '">' . get_string('profile', 'lips') . '</a></li>';
        $menu .= ($action == 'ranks') ? '<li><p class="current">Classements</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=ranks' . (($iduser != null) ? '&amp;id_user=' . $iduser : '') . '">' . get_string('ranks', 'lips') . '</a></li>';
        $menu .= ($action == 'solved_problems') ? '<li><p class="current">Problèmes résolus</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=solved_problems' . (($iduser != null) ? '&amp;id_user=' . $iduser : '') . '">' . get_string('solved_problems', 'lips') . '</a></li>';
        $menu .= ($action == 'challenges') ? '<li><p class="current">Défis</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=challenges' . (($iduser != null) ? '&amp;id_user=' . $iduser : '') . '">' . get_string('challenges', 'lips') . '</a></li>';
        $menu .= ($action == 'followed_users') ? '<li><p class="current">Utilisateurs suivis</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=followed_users' . (($iduser != null) ? '&amp;id_user=' . $iduser : '') . '">' . get_string('followed_users', 'lips') . '</a></li>';
        $menu .= '</ul></div>';

        return $menu;
    }

    /**
     * Display a notification message
     *
     * @param string $msg Message to display
     * @param string $type Notification type (INFO, SUCCESS, WARNING, ERROR)
     * @return string The notification message
     */
    public function display_notification($msg, $type) {
        switch ($type) {
            case 'INFO':
                return '<div id="notify" class="notifyInfo">' . $msg . '</div>';
                break;

            case 'SUCCESS':
                return '<div id="notify" class="notifySuccess">' . $msg . '</div>';
                break;

            case 'WARNING':
                return '<div id="notify" class="notifyWarning">' . $msg . '</div>';
                break;

            case 'ERROR':
                return '<div id="notify" class="notifyError">' . $msg . '</div>';
                break;
        }
    }

    /**
     * Display a button
     *
     * @param object $moodleurl Moodle url
     * @param string $label Button label
     * @return object Button renderer
     */
    public function display_button($moodleurl, $label) {
        return $this->render(new single_button($moodleurl, $label));
    }

    /**
     * Display the ace form
     *
     * @param string $editorid Ace editor ID
     * @param string $areaid Area ID. Use to replic the data on the textarea
     * @param string $mode Ace mode for the syntax highlightning
     * @param string $flag Ace flag (configure, code or unit-test)
     * @param string $theme Ace theme
     * @param string $comment Base comment
     */
    public function display_ace_form($editorid, $areaid, $mode, $flag = '', $theme = 'eclipse', $comment = '') {
        echo '<script type="text/javascript">createAce("' . $editorid . '", "' . $areaid . '", "' . $mode . '", "' . $theme . '", "' . $flag . '", "' . $comment . '")</script>';
    }

    /**
     * Display a div
     *
     * @param string $content Text content
     * @param array $attributes Attributes
     * @return string div tag
     */
    public function display_div($content, array $attributes = null) {
        return html_writer::tag('div', $content, $attributes);
    }

    /**
     * Display a span tag
     *
     * @param string $content Text content
     * @param array $attributes Attributes
     * @return string span tag
     */
    public function display_span($content, array $attributes = null) {
        return html_writer::tag("span", $content, $attributes);
    }

    /**
     * Display a solution
     *
     * @param array $data
     * @param object LIPS instance
     */
    public function display_solution($data, $lips) {
        $profillink = $this->action_link(new moodle_url("view.php", array('id' => $this->page->cm->id, 'view' => 'profile', 'id_user' => $data->profil_id)), ucfirst($data->firstname) . ' ' . ucfirst($data->lastname));
        $date = html_writer::tag('div', get_string("The", "lips") . " " . format_date($data->problem_solved_date), array("id" => "date"));
        $header = html_writer::tag('div', get_string("problem_resolved_by", "lips") . " " . $profillink . "<br/>" . $date, array("id" => "header-solved"));
        $content = html_writer::tag('div', '<div id="aceSolution_' . $data->id . '" class="ace readonly">' . $data->problem_solved_solution . '</div>', array("id" => "content"));
        echo html_writer::tag('div', $header . $content, array("class" => "solution"));
        
        $this->display_ace_form('aceSolution_' . $data->id, '', $lips->coloration_language, 'readonly-notsizable');
    }

    /**
     * Display the documentation
     *
     * @param object $categorydetails Category details
     * @return object|null Link to the category or null if no category
     */
    public function display_documentation($categorydetails) {
        $url = null;
        if ($categorydetails->category_documentation_type == 'LINK') {
            $url = new moodle_url($categorydetails->category_documentation);
        } else if ($categorydetails->category_documentation_type == 'TEXT') {
            $url = new moodle_url("view.php", array('id' => $this->page->cm->id, 'view' => 'categoryDocumentation', 'categoryId' => $categorydetails->id));
        }

        if($url != null) {
            return $this->display_div($this->render(new action_link($url, get_string("documentation", "lips"))), array("style" => "float: right;"));
        }

        return null;
    }

    /**
     * Display a problem information
     *
     * @param string $info Information title
     * @param string $text Information text
     * @return string The problem information
     */
    public function display_problem_information($info, $text) {
        return $this->display_p($this->display_span($info, array("class" => "label_field_page_problem")) . " " . $text);
    }

    /**
     * Display the notifications
     *
     * @param object $notifications Notifications informations
     * @return string The rendered notifications
     */
    public function display_notifications($notifications) {
        $display = '';

        if(count($notifications) == 0) {
            $display = get_string('no_notifications', 'lips');
        } else {
            $display .= '<div style="margin-top: 15px;"></div>';

            foreach($notifications as $notification) {
                // Notification message
                $language = '';
                if($notification->notification_language != null) {
                    $lips = get_instance($notification->notification_language);
                    if($lips->compile_language != null) {
                        $language = '<div class="notification-language">' . ucfirst($lips->compile_language) . '</div>';
                    }
                }
                $notification_msg = '<div class="notification-content">' . $language . get_string($notification->notification_type, 'lips') . '</div>';

                // Set the picture
                $notification_msg = str_replace('{img}', '<img src="images/' .  get_string($notification->notification_type . '_picture', 'lips') . '"/>', $notification_msg);

                // Set the date
                $notification_msg = str_replace('{date}', '<span>' . format_date($notification->notification_date) . '</span>', $notification_msg);

                // Set the notification_from
                $notification_from = get_moodle_user_details(array('id' => get_user_details(array('id' => $notification->notification_from))->id_user_moodle));
                $notification_msg = str_replace('{notification_from}', $this->display_user_link($notification->notification_from, $notification_from->firstname, $notification_from->lastname), $notification_msg);

                // Set the notification_to
                if($notification->notification_to != 0) {
                    $notification_to = get_moodle_user_details(array('id' => get_user_details(array('id' => $notification->notification_to))->id_user_moodle));
                    $notification_msg = str_replace('{notification_to}', $this->display_user_link($notification->notification_to, $notification_to->firstname, $notification_to->lastname), $notification_msg);
                }

                // Set the notification_problem
                if($notification->notification_problem != null) {
                    $notification_problem = get_problem_details($notification->notification_problem);
                    $url_problem = new action_link(new moodle_url('view.php', array(
                        'id' => $lips->instance_link,
                        'view' => 'problem',
                        'problemId' => $notification->notification_problem
                    )),
                    $notification_problem[$notification->notification_problem]->problem_label);
                    $notification_msg = str_replace('{notification_problem}', $this->render($url_problem), $notification_msg);
                }

                // Set the notification_category
                if($notification->notification_category != null) {
                    $notification_category = get_category_details($notification->notification_category);
                    $url_category = new action_link(new moodle_url('view.php', array(
                        'id' => $lips->instance_link,
                        'view' => 'category',
                        'categoryId' => $notification->notification_category
                    )),
                    $notification_category->category_name);
                    $notification_msg = str_replace('{notification_category}', $this->render($url_category), $notification_msg);
                }

                // Set the notification_text
                if($notification->notification_text != null) {
                    $notification_msg = str_replace('{notification_text}', '<strong>' . $notification->notification_text . '</strong>', $notification_msg);
                }

                $display .= $notification_msg;

                // Notification border
                $display .= '<div class="notification-border"></div>';
            }
        }

        return $display;
    }

    /**
     * Display a user link
     *
     * @param int $userid User ID
     * @param string $firstname First name
     * @param string $lastname Last name
     * @param int $languageid Language ID
     * @return string User link
     */
    public function display_user_link($userid, $firstname, $lastname, $languageid = null) {
        $url = new action_link(new moodle_url('view.php', array(
            'id' => (($languageid == null) ? $this->page->cm->id : $languageid),
            'view' => 'profile',
            'id_user' => $userid
        )),
        ucfirst($firstname) . ' ' . ucfirst($lastname));

        return $this->render($url);
    }

    /**
     * Display the dialog box
     *
     * @param string $category Category name
     * @param string $problem Problem name
     * @param array $challengedusers Challenged users
     * @return string Dialog box
     */
    public function display_challenge_dialog($category, $problem, $challengedusers) {
        $dialog = '<div id="challenge-users" title="' . get_string('challenge', 'lips') . ' - ' . $category . ' - ' . $problem . '">
            <div id="notify" class="notifySuccess" style="width: 98%; display: none">' . get_string('problem_challenge_success', 'lips') . '</div>

            <h2>' . $problem . '</h2>
            <p>Sélectionnez la liste des utilisateurs que vous souhaitez défier sur ce problème.</p>
            <input id="challenge-user"/>
            <h3>' . get_string('challenged_users', 'lips') . '</h3><p id="challenged-users">';

        if(count($challengedusers) > 0) {
            foreach($challengedusers as $user) {
                $dialog .= ucfirst($user->firstname) . ' ' . ucfirst($user->lastname) . ', ';
            }
            $dialog = substr($dialog, 0, -2);
        } else {
            $dialog .= "";
        }

        $dialog .= '</p><h3>' . get_string('challenged', 'lips') . '</h3><div id="challenged-players"></div></div>';

        return $dialog;
    }

    /**
     * Display the challenges
     *
     * @param object $challenges Challenges informations
     * @return string The rendered challenges
     */
    public function display_challenges($challenges) {
        $display = '';

        foreach($challenges as $challenge) {
            // Challenge border
            $display .= '<div class="notification-border"></div>';

            $lips = get_instance($challenge->challenge_language);

            $language = '';
            $lips = get_instance($challenge->challenge_language);
            if($lips->compile_language != null) {
                $language = '<div class="notification-language">' . ucfirst($lips->compile_language) . '</div>';
            }
            // Challenge message
            $challenge_msg = '<div class="challenge-content">' . $language . get_string('challenge_notification', 'lips') . '<br/>{challenge_solve} {challenge_refuse}</div>';

            // Set the date
            $challenge_msg = str_replace('{date}', '<span>' . format_date($challenge->challenge_date) . '</span>', $challenge_msg);

            // Set the challenge_from
            $challenge_from = get_moodle_user_details(array('id' => get_user_details(array('id' => $challenge->challenge_from))->id_user_moodle));
            $challenge_msg = str_replace('{challenge_from}', $this->display_user_link($challenge->challenge_from, $challenge_from->firstname, $challenge_from->lastname), $challenge_msg);

            // Set the challenge_problem
            $challenge_problem = get_problem_details($challenge->challenge_problem);
            $url_problem = new action_link(new moodle_url('view.php', array(
                'id' => $lips->instance_link,
                'view' => 'problem',
                'problemId' => $challenge->challenge_problem
            )),
            $challenge_problem[$challenge->challenge_problem]->problem_label);
            $challenge_msg = str_replace('{challenge_problem}', $this->render($url_problem), $challenge_msg);

            // Set the buttons
            $url_solve = new moodle_url('action.php', array(
                'id' => $this->page->cm->id,
                'action' => 'accept_challenge',
                'challenge_id' => $challenge->id
            ));
            $url_refuse = new moodle_url('action.php', array(
                'id' => $this->page->cm->id,
                'action' => 'refuse_challenge',
                'challenge_id' => $challenge->id
            ));
            $challenge_msg = str_replace('{challenge_solve}', '<a href="' . $url_solve . '" class="lips-button">' . get_string('accept', 'lips') . '</a>', $challenge_msg);
            $challenge_msg = str_replace('{challenge_refuse}', '<a href="' . $url_refuse . '" class="lips-button">' . get_string('refuse', 'lips') . '</a>', $challenge_msg);

            $display .= $challenge_msg;
        }

        if($display != '') {
            $display .= '<div class="notification-border"></div>';
        }

        return $display;
    }

    /**
     * Display the current challenges
     *
     * @param object $challenges Challenges informations
     * @return string The rendered challenges
     */
    public function display_current_challenges($challenges) {
        $display = '';

        foreach($challenges as $challenge) {

            // Challenge border
            $display .= '<div class="notification-border"></div>';

            $language = '';
            $lips = get_instance($challenge->challenge_language);
            if($lips->compile_language != null) {
                $language = '<div class="notification-language">' . ucfirst($lips->compile_language) . '</div>';
            }
            // Challenge message
            $challenge_msg = '<div class="challenge-content current">' . $language . '<span>' . get_string('challenge_current', 'lips') . '</span></div>';

            // Set the challenge_problem
            $challenge_problem = get_problem_details($challenge->challenge_problem);
            $url_problem = new action_link(new moodle_url('view.php', array(
                'id' => $lips->instance_link,
                'view' => 'problem',
                'problemId' => $challenge->challenge_problem
            )),
            $challenge_problem[$challenge->challenge_problem]->problem_label);
            $challenge_msg = str_replace('{challenge_problem}', $this->render($url_problem), $challenge_msg);

            // Set the challenge_from
            $challenge_from = get_moodle_user_details(array('id' => get_user_details(array('id' => $challenge->challenge_from))->id_user_moodle));
            $challenge_msg = str_replace('{challenge_from}', $this->display_user_link($challenge->challenge_from, $challenge_from->firstname, $challenge_from->lastname), $challenge_msg);

            $display .= $challenge_msg;
        }

        if($display != '') {
            $display .= '<div class="notification-border"></div>';
        }

        return $display;
    }

    /**
     * Display the user ranks
     *
     * @param int $userid User ID
     * @return string User ranks
     */
    public function display_ranks($userid) {
        $display = '<div id="ranks">';

        $numberofusers = number_of_users();
        $instances = fetch_all_instances();
        foreach($instances as $instance) {
            $userrank = user_rank_for_language($instance->instance_id, $userid);

            if($instance->compile_language != null) {
                $display .= '<table><tr>
                                <td class="img"><img src="images/' . $instance->language_picture . '"/></td>
                                <td><p class="rank-language">' . ucfirst($instance->compile_language) . '</p>';

                if($userrank == null) {
                    $display .= '<p class="rank-rank">' . get_string('unranked', 'lips') . '</p>';
                } else {
                    $display .= '<p class="rank-rank">Classement ' . $numberofusers . ' / ' . $numberofusers . '</p>';
                }
                
                $display .= '</td></tr></table>';
            }
        }

        $display .= '</div>';

        return $display;
    }

    /**
     * Display the user achievements
     *
     * @param object $achievements Achievements
     * @return string Achievements
     */
    public function display_achievements($achievements) {
        $display = '';

        if(count($achievements) == 0) {
            $display = get_string('no_achievements', 'lips');
        } else {
            $display = '<div id="achievements">';

            foreach($achievements as $achievement) {
                $display .= '<table><tr>
                                <td class="img"><img src="images/' . $achievement->achievement_picture . '"/></td>
                                <td><span class="title">' . $achievement->achievement_label . '</span> : 
                                ' . $achievement->achievement_desc . ' - <span class="date">' . date('d/m/Y', $achievement->au_date) . '</span></td></tr></table>';
            }

            $display .= '</div>';
        }

        return $display;
    } 
}