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
    public function display_img($src, array $attributes = null) {
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

        $administrationmenu .= '<li><a href="#">Badges</a>
                <ul>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=achievement_create">' . get_string('create', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=achievement_modify">' . get_string('modify', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=achievement_delete">' . get_string('delete', 'lips') . '</a></li>
                </ul>
            </li>
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
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=problem_delete">' . get_string('delete', 'lips') . '</a></li>
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
        $userdetails = ($iduser == null) ? get_user_details(array('id_user_moodle' => $USER->id)) : get_user_details(array('id' => $iduser));
        $moodleuserdetails = get_moodle_user_details(array('id' => $userdetails->id_user_moodle));
        $rank = get_rank_details(array('id' => $userdetails->user_rank_id));
        $userpicture = get_user_picture_url(array('id_user_moodle' => $moodleuserdetails->id), 'f1');

        $menu = '<div id="profile-menu"><img src="' . $userpicture . '" id="picture"/>';
        if ($iduser != null)
            $menu .= '<a href="#" id="follow" class="lips-button">S\'abonner</a>';

        $menu .= '<div id="background">
            <div id="infos">
                <div id="role">' . get_string($userdetails->user_status, 'lips') . '</div>
                <div id="rank">' . $rank->rank_label . '</div>
            </div>
            <div id="user">' . ucfirst($moodleuserdetails->firstname) . ' ' . strtoupper($moodleuserdetails->lastname) . '</div>
        </div>
        <ul id="links">';

        $menu .= ($action == 'profile') ? '<li><p class="current">Profil</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;">' . get_string('profile', 'lips') . '</a></li>';
        $menu .= ($action == 'ranks') ? '<li><p class="current">Classements</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=ranks">' . get_string('ranks', 'lips') . '</a></li>';
        $menu .= ($action == 'solved_problems') ? '<li><p class="current">Problèmes résolus</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=solved_problems">' . get_string('solved_problems', 'lips') . '</a></li>';
        $menu .= ($action == 'challenges') ? '<li><p class="current">Défis reçus</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=challenges">' . get_string('challenges', 'lips') . '</a></li>';
        $menu .= ($action == 'followed_users') ? '<li><p class="current">Utilisateurs suivis</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=followed_users">' . get_string('followed_users', 'lips') . '</a></li>';
        $menu .= '</ul></div>';

        return $menu;
    }

    /**
     * Display a notification message
     *
     * @param string $msg Message to display
     * @param stirng $type Notification type (INFO, SUCCESS, WARNING, ERROR)
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
     */
    public function display_ace_form($editorid, $areaid, $mode, $flag = '', $theme = 'eclipse') {
        echo '<script type="text/javascript">createAce("' . $editorid . '", "' . $areaid . '", "' . $mode . '", "' . $theme . '", "' . $flag . '")</script>';
    }

    /**
     * Display the top of the page representing a category.
     *
     * @param object $categorydetails Category details
     * @return string div tag
     */
    public function display_top_page_category($categorydetails) {
        if ($categorydetails->category_documentation_type == 'LINK') {
            $link = new action_link(new moodle_url($categorydetails->category_documentation), get_string("documentation", "lips"));
        } else if ($categorydetails->category_documentation_type == 'TEXT') {
            $link = new action_link(new moodle_url("view.php", array('id' => $this->page->cm->id, 'view' => 'categoryDocumentation', 'categoryId' => $categorydetails->id)), get_string("documentation", "lips"));
        }
        $renderlink = "";
        if (isset($link)) {
            $renderlink = $this->render($link);
        }
        return html_writer::tag('div', $this->display_p($renderlink, array("style" => "float: right;")) . $this->display_h1($categorydetails->category_name));
    }

    /**
     * Display the top of the page representing a problem.
     *
     * @param string $problemname Name of the problem
     * @param int $problemid id of the problem
     * @return string div tag
     */
    public function display_top_page_problem($problemname, $problemid) {
        $link = new action_link(new moodle_url("view.php", array('id' => $this->page->cm->id, 'view' => 'categoryDocumentation', 'categoryId' => $problemid)), get_string("documentation", "lips"));
        return html_writer::tag('div', $this->display_p($this->render($link), array("style" => "float:right;")) . $this->display_h2($problemname));
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
     * @return string div tag
     */
    public function display_solution($data) {
        $profillink = $this->action_link(new moodle_url("view.php", array('id' => $this->page->cm->id, 'view' => 'profile', 'id_user' => $data->profil_id)), ucfirst($data->firstname) . ' ' . strtoupper($data->lastname));
        $date = html_writer::tag('div', get_string("The", "lips") . " " . date('d/m/Y', $data->problem_solved_date), array("id" => "date"));
        $header = html_writer::tag('div', get_string("problem_resolved_by", "lips") . " " . $profillink . "<br/>" . $date, array("id" => "header"));
        $content = html_writer::tag('div', $data->problem_solved_solution, array("id" => "content"));
        return html_writer::tag('div', $header . $content, array("class" => "solution"));
    }


}