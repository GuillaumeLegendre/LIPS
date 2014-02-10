<?php
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
     * @return string H3 tag
     */
    public function display_h3($title, array $attributes = null) {
        $html = html_writer::tag('h3', format_string($title), $attributes);
        $html .= html_writer::tag('div', null, array('class' => 'h2_sub'));

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

        return '<ul id="administration_menu">
            <li><a href="#">' . get_string('language', 'lips') . '</a>
                <ul>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=language_configure">' . get_string('configure', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=language_picture">' . get_string('picture', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=language_base">' . get_string('base', 'lips') . '</a></li>
                </ul>
            </li>
            <li><a href="#">Badges</a>
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
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=problem_modify">' . get_string('modify', 'lips') . '</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=problem_delete">' . get_string('delete', 'lips') . '</a></li>
                </ul>
            </li>
        </ul>';
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

        // Infos
        $firstname = ucfirst($USER->firstname);
        $lastname = strtoupper($USER->lastname);
        $userpicture = new user_picture($USER);
        $user = get_user_details(array('id_user_moodle' => $USER->id));
        $rank = get_rank_details(array('id' => $user->user_rank_id));

        $menu = '<div id="profile-menu">
            <img src="' . str_replace('f2', 'f1', $userpicture->get_url($PAGE)) . '" id="picture"/>
            <a href="#" id="follow" class="lips-button">S\'abonner</a>
            <div id="background">
                <div id="infos">
                    <div id="role">' . get_string($user->user_status, 'lips') . '</div>
                    <div id="rank">' . $rank->rank_label . '</div>
                </div>
                <div id="user">' . $firstname . ' ' . $lastname . '</div>
            </div>
            <ul id="links">';

        $menu .= ($action == 'profile') ? '<li><p class="current">Profil</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=profile">Profil</a></li>';
        $menu .= ($action == 'rank') ? '<li><p class="current">Classements</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=rank">Classements</a></li>';
        $menu .= ($action == 'solved_problems') ? '<li><p class="current">Problèmes résolus</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=solved_problems">Problèmes résolus</a></li>';
        $menu .= ($action == 'challenges') ? '<li><p class="current">Défis reçus</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=challenges">Défis reçus</a></li>';
        $menu .= ($action == 'followed_users') ? '<li><p class="current">Utilisateurs suivis</p></li>' : '<li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=followed_users">Utilisateurs suivis</a></li>';
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

    public function display_documentation($documentation) {

    }

    public function display_button($moodleurl, $label) {
        return $this->render(new single_button($moodleurl, $label));
    }

    /**
     * Display the ace form
     *
     * @param string $editorid Ace editor ID
     * @param string $areaid Area ID. Use to replic the data on the textarea
     * @param string $mode Ace mode for the syntax highlightning
     * @param string $theme Ace theme
     */
    public function display_ace_form($editorid, $areaid, $mode, $theme = 'eclipse') {
        echo '<script type="text/javascript">createAce("' . $editorid . '", "' . $areaid . '", "' . $mode . '", "' . $theme . '")</script>';
    }

    public function display_top_page_category($categoryname, $categoryid) {
        $link = new action_link(new moodle_url("view.php", array('id' => $this->page->cm->id, 'view' => 'categoryDocumentation', 'categoryId' => $categoryid)), get_string("documentation", "lips"));
        return html_writer::tag('div', $this->display_p($this->render($link), array("style" => "float:right;")) . $this->display_h1($categoryname));

    }
}