<?php
/**
 * Created by PhpStorm.
 * User: Mickael
 * Date: 12/01/14
 * Time: 03:10
 */

class mod_lips_renderer extends plugin_renderer_base {

    function tabs($activeTab) {
        $id =$this->page->cm->id;
        $tabs[] = new tabobject('index', "view.php?id={$id}&amp;view=index", "Accueil");
        $tabs[] = new tabobject('problems', "view.php?id={$id}&amp;view=problems", "Problèmes");
        $tabs[] = new tabobject('users', "view.php?id={$id}&amp;view=users","Utilisateurs");
        $tabs[] = new tabobject('rank', "view.php?id={$id}&amp;view=rank","Classement");
        $tabs[] = new tabobject('profil', "view.php?id={$id}&amp;view=profil","Profil");
        $tabs[] = new tabobject('administration', "view.php?id={$id}&amp;view=administration","Administration");
        return $this->tabtree($tabs,$activeTab);
    }

}