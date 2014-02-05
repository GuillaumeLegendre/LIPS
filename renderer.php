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
        return html_writer::tag('h2', format_string($title), $attributes);
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
        return '<ul id="administration_menu">
            <li><a href="#">Langage</a></li>
            <li><a href="#">Badges</a>
                <ul>
                    <li><a href="#">Créer</a></li>
                    <li><a href="#">Modifier</a></li>
                    <li><a href="#">Supprimer</a></li>
                </ul>
            </li>
            <li><a href="#">Catégories</a>
                <ul>
                    <li><a href="#">Créer</a></li>
                    <li><a href="#">Modifier</a></li>
                    <li><a href="#">Supprimer</a></li>
                </ul>
            </li>
            <li><a href="#">Problèmes</a>
                <ul>
                    <li><a href="#">Créer</a></li>
                    <li><a href="#">Modifier</a></li>
                    <li><a href="#">Supprimer</a></li>
                </ul>
            </li>
        </ul>';
    }
}