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

        $tabs[] = new tabobject('index', "view.php?id={$id}&amp;view=index", "Accueil");
        $tabs[] = new tabobject('problems', "view.php?id={$id}&amp;view=problems", "Problèmes");
        $tabs[] = new tabobject('users', "view.php?id={$id}&amp;view=users", "Utilisateurs");
        $tabs[] = new tabobject('rank', "view.php?id={$id}&amp;view=rank", "Classement");
        $tabs[] = new tabobject('profil', "view.php?id={$id}&amp;view=profil", "Profil");

        if (has_capability('mod/lips:administration', $context)) {
            $tabs[] = new tabobject('administration', "view.php?id={$id}&amp;view=administration", "Administration");
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
        return html_writer::tag('img', null, array_merge(array('src' => $src), $attributes));
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
            <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=langage">Langage</a></li>
            <li><a href="#">Badges</a>
                <ul>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=achievement_create">Créer</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=achievement_modify">Modifier</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=achievement_delete">Supprimer</a></li>
                </ul>
            </li>
            <li><a href="#">Catégories</a>
                <ul>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=category_create">Créer</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=category_modify">Modifier</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=category_delete">Supprimer</a></li>
                </ul>
            </li>
            <li><a href="#">Problèmes</a>
                <ul>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=problem_create">Créer</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=problem_modify">Modifier</a></li>
                    <li><a href="view.php?id=' . $id . '&amp;view=' . $view . '&amp;action=problem_delete">Supprimer</a></li>
                </ul>
            </li>
        </ul>';
    }

    public function display_documentation($documentation) {

    }
}