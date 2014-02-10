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
$string['subject'] = 'Enoncé';
$string['tips'] = 'Astuces';

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
$string['problem'] = 'Problème';
$string['level'] = 'Niveau';
$string['date'] = 'Date';
$string['author'] = 'Auteur';
$string['number_of_resolutions'] = 'Nombre de résolutions';
$string['resolved'] = 'Résolu';
$string['configure'] = 'Configurer';
$string['picture'] = 'Image';
$string['base'] = 'Base';

/*--------------------------------------------------
 * Administration
 *------------------------------------------------*/

// Language
$string['administration_language_configure_title'] = 'Configurer le langage';
$string['administration_language_configure_msg'] = 'Sélectionnez le langage dans lequel vous souhaitez compiler.';
$string['administration_language_image_title'] = 'Image';
$string['administration_language_image_msg'] = 'Choisissez l\'image de ce langage de programmation.<br/>Celle-ci sera utilisée lors de l\'affichage du classement d\'un utilisateur.';
$string['administration_language_image_success'] = 'L\'image a été modifiée';
$string['administration_language_image_save_error'] = 'Une erreur est survenue lors de l\'enregistrement de l\'image';
$string['administration_language_code_title'] = 'Base du code';
$string['administration_language_code_msg'] = 'Entrez la base du code qui sera utilisé à chaque compilation.<br/>Avant la compilation :<ul><li>La balise <span style="color: red;">&lt;lips-preconfig-import/&gt;</span> sera remplacée par le contenu de la zone "<strong>Importer des librairies</strong>" ;</li><li>La balise <span style="color: red;">&lt;lips-preconfig-code/&gt;</span> sera remplacée par le contenu de la zone "<strong>Code à compléter</strong>";</li><li>La balise <span style="color: red;">&lt;lips-preconfig-tests/&gt;</span> sera remplacée par le contenu de la zone "<strong>Tests unitaires</strong>".</li></ul>';
$string['administration_language_code_success'] = 'La préparation du code a été modifiée';
$string['administration_language_code_imports_error'] = 'Vous ne pouvez mettre qu\'une seule balise &lt;lips-preconfig-import/&gt;';
$string['administration_language_code_code_error'] = 'Vous ne pouvez mettre qu\'une seule balise &lt;lips-preconfig-code/&gt;';
$string['administration_language_code_tests_error'] = 'Vous ne pouvez mettre qu\'une seule balise &lt;lips-preconfig-tests/&gt;';
$string['administration_language_form_select'] = 'Langage de programmation';
$string['administration_language_form_select_error'] = 'Vous devez sélectionner un langage';
$string['administration_language_form_highlighting_select'] = 'Coloration syntaxique';
$string['administration_language_form_highlighting_select_error'] = 'Vous devez sélectionner une coloration syntaxique';
$string['administration_language_form_file'] = 'Sélectionner l\'image';
$string['administration_language_form_file_error'] = 'Vous devez sélectionner une image';
$string['administration_existing_problems'] = 'Attention, des problèmes existent déjà dans ce cours, la configuration peut engendrer des erreurs dans les problèmes déjà présents.';
$string['administration_no_syntax_highlighting'] = 'Aucun langage de coloration syntaxique n\'a été définie. Vous pouvez le configurer dans <strong>Administration > Langage > Configurer le langage</strong>.';
$string['administration_no_compile_language'] = 'Aucun langage de compilation n\'a été définie. Vous pouvez le configurer dans <strong>Administration > Langage > Configurer le langage</strong>.';

// Category
$string['administration_category_create_title'] = 'Créer une catégorie';
$string['administration_category_create_success'] = 'La catégorie a été créée';
$string['administration_category_modify_title'] = 'Modifier une catégorie';
$string['administration_category_modify_select'] = 'Sélection de la catégorie';
$string['administration_category_modify_select_error'] = 'Vous devez sélectionner une catégorie';
$string['administration_category_modify_success'] = 'La catégorie a été modifiée';
$string['administration_category_delete_title'] = 'Supprimer une catégorie';
$string['administration_category_msg'] = 'Choisissez soit un lien vers une documentation externe soit une documentation textuelle. <strong>Vous ne pouvez pas choisir les deux</strong>.';
$string['administration_category_name_error'] = 'Vous devez saisir le nom de la catégorie';
$string['administration_category_already_exists'] = 'Ce nom de catégorie est déjà utilisé, merci d\'en choisir un autre';
$string['administration_category_documentation_link_placeholder'] = 'Lien vers une documentation externe';
$string['administration_category_documentation_text_placeholder'] = 'Documentation textuelle';
$string['administration_category_documentation_link'] = 'Documentation (Lien)';
$string['administration_category_documentation_text'] = 'Documentation (Texte)';
$string['administration_category_documentation_error'] = 'Vous ne pouvez pas avoir les deux types de documentation, choisissez soit un lien vers une documentation externe soit une documentation textuelle.';
$string['administration_delete_category_confirmation'] = "Confirmez-vous la suppression de la catégorie ?";

// Problem
$string['administration_problem_create_title'] = 'Créer un probleme';
$string['prerequisite'] = "Prérequis";
$string['administration_problem_informations_msg'] = 'Select the category of the problem.<br/>Enter the name, the difficulty, the conditions need to resolve the problem.';
$string['difficulty'] = "Difficulty";
$string['administration_problem_create_preconfig_subtitle'] = '1. Pré-configuration';
$string['administration_problem_create_informations_subtitle'] = "2. Informations sur le problème";
$string['administration_problem_create_informations_msg'] = 'Select the category of the problem.<br/>Enter the name, the difficulty, the conditions need to resolve the problem.';
$string['administration_problem_create_subject_subtitle'] = '3. Enoncé';
$string['administration_problem_create_subject_msg'] = "Rédigez l'énoncé et les astuces du problème";
$string['administration_problem_create_code_subtitle'] = '4. Code';
$string['administration_problem_create_code_msg'] = 'Start to import all the librairies need for the resolution of the problem.<br/>Next write the unit tests that the user will complete.<br/>To define editable areas, use the tag <span style="color: red;">&lt;lips-code/&gt;</span>.';

$string['administration_problem_create_code_import_label'] = 'Importer des librairies';
$string['administration_problem_create_code_complete_label'] = 'Code à completer';
$string['administration_problem_create_code_unittest_label'] = 'Tests unitaires';
$string['administration_problem_create_success'] = 'Le problème a été créé';

/*--------------------------------------------------
 * Web services
 *------------------------------------------------*/
$string['web_service_communication_error'] = 'Une erreur est survenue lors de la communication avec le service web.<br/>Vous ne pouvez pas sélectionner un langage de programmation.';
