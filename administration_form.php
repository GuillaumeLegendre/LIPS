<?php
// Inclusion de la classe dont on hérite.
require_once ($CFG->dirroot.'/course/moodleform_mod.php');
// Définition de notre classe qui hérite de moodleform (formslib).
class mod_lips_administration_form extends moodleform {
// Définition du formulaire
    function definition()
    {
        $mform =& $this->_form;
        /* Entête du formulaire */
        $mform->addElement('header', 'header1',
            "Créer une catégorie");
        /* Un champ de texte */
        $mform->addElement('text', 'category_name',
            "Catégorie", array('size'=>'64'));

        $mform->addElement('text', 'category_documentation',
            "Documentation", array('size'=>'64'));
        /* Ajout d'un contrôle de saisie. Ce champ est
        obligatoire. */

        $mform->setType('category_name', PARAM_TEXT);                   //Set type of element
        $mform->setType('category_documentation', PARAM_TEXT);                   //Set type of element


        $mform->addRule('category_name', "required",
            'required', null, 'client');

        $mform->addRule('category_documentation', "required",
            'required', null, 'client');
// Bouton enregistrement et annulation standard
        $this->add_action_buttons();
    }

    function validation($data,$files)
    {
        $errors= array();

        return $errors;
    }
}
