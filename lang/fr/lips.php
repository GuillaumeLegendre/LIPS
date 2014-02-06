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
 * French strings for lips
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/*--------------------------------------------------
 * Module informations
 *------------------------------------------------*/
$string['modulename'] = 'LIPS';
$string['modulenameplural'] = 'LIPS';
$string['modulename_help'] = 'Use the lips module for... | The lips module allows...';
$string['lipsfieldset'] = 'Custom example fieldset';
$string['lipsname'] = 'LIPS';
$string['lipsname_help'] = 'This is the content of the help tooltip associated with the lipsname field. Markdown syntax is supported.';
$string['lips'] = 'LIPS';
$string['pluginadministration'] = 'lips administration';
$string['pluginname'] = 'LIPS';

/*--------------------------------------------------
 * Constants
 *------------------------------------------------*/
$string['create'] = 'Créer';
$string['modify'] = 'Modifier';
$string['delete'] = 'Supprimer';
$string['category'] = 'Catégorie';
$string['documentation'] = 'Documentation';
$string['name'] = 'Nom';
$string['error_impossible'] = 'Vous ne devriez pas avoir accès à cette page';
$string['language'] = 'Langage';

/*--------------------------------------------------
 * Tabs
 *------------------------------------------------*/
$string['index'] = 'Accueil';
$string['problems'] = 'Problèmes';
$string['users'] = 'Utilisateurs';
$string['rank'] = 'Classement';
$string['profile'] = 'Profil';
$string['administration'] = 'Administration';

/*--------------------------------------------------
 * Tables
 *------------------------------------------------*/
$string['number_of_problems'] = 'Nombre de problèmes';
+$string['problem'] = 'Problème';
+$string['level'] = 'Niveau';
+$string['date'] = 'Date';
+$string['author'] = 'Auteur';
+$string['number_of_resolutions'] = 'Nombre de résolutions';
+$string['resolved'] = 'Résolu';

/*--------------------------------------------------
 * Administration
 *------------------------------------------------*/

// Language
$string['administration_language_configure_title'] = 'Configurer le langage';
$string['administration_language_configure_msg'] = 'Selectionnez le langage dans lequel vous souhaitez compiler.';
$string['administration_language_image_title'] = 'Image';
$string['administration_language_image_msg'] = 'Choisissez l\'image de ce langage de programmation.<br/>Celle-ci sera utilisée lors de l\'affichage du classement d\'un utilisateur.';
$string['administration_language_code_title'] = 'Base du code';
$string['administration_language_code_msg'] = 'Entrez la base du code qui sera utilisé à chaque compilation.<br/>Avant la compilation :<ul><li>La balise <span style="color: red;">&lt;lips-preconfig-import/&gt;</span> sera remplacée par le contenu de la zone "<strong>Importer des librairies</strong>" ;</li><li>La balise <span style="color: red;">&lt;lips-preconfig-code/&gt;</span> sera remplacée par le contenu de la zone "<strong>Code à compléter</strong>";</li><li>La balise <span style="color: red;">&lt;lips-preconfig-tests/&gt;</span> sera remplacée par le contenu de la zone "<strong>Tests unitaires</strong>".</li></ul>';
$string['administration_language_form_select'] = 'Langage de programmation';
$string['administration_language_form_select_error'] = 'Vous devez sélectionner un langage';
$string['administration_language_form_file'] = 'Sélectionner l\'image';
$string['administration_language_form_file_error'] = 'Vous devez sélectionner une image';

// Category
$string['administration_category_create_title'] = 'Créer une catégorie';
$string['administration_category_create_success'] = 'La catégorie a été créée';
$string['administration_category_msg'] = 'Choisissez soit un lien vers une documentation externe soit une documentation textuelle. <strong>You can\'t choose both</strong>.';
$string['administration_category_name_error'] = 'Vous devez saisir le nom de la catégorie';
$string['administration_category_already_exists'] = 'Ce nom de catégorie est déjà utilisé, merci d\'en choisir un autre';
$string['administration_category_documentation_link_placeholder'] = 'Lien vers une documentation externe';
$string['administration_category_documentation_text_placeholder'] = 'Documentation textuelle';
$string['administration_category_documentation_link'] = 'Documentation (Lien)';
$string['administration_category_documentation_text'] = 'Documentation (Texte)';
$string['administration_category_documentation_error'] = 'Vous ne pouvez pas avoir les deux types de documentation, choisissez soit un lien vers une documentation externe soit une documentation textuelle.';
$string['administration_delete_category_confirmation'] = "Confirmez-vous la suppression de la catégorie ?";

/*--------------------------------------------------
 * Web services
 *------------------------------------------------*/
$string['web_service_communication_error'] = 'Une erreur est survenue lors de la communication avec le service web';