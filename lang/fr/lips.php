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
$string['modulename_help'] = 'Le module activité LIPS offre la possibilité aux étudiants de s’autoformer sur différents langages de programmation.

Cette plateforme pédagogique permet aux étudiants, par le biais de tests unitaires, de répondre à des problèmes de programmation exprimés par les enseignants.
Ces problèmes se présentent sous la forme de code à compléter. La plateforme exécute alors le code avec la réponse fournit par l’étudiant et lui retourne le résultat d’exécution correspondant, validant ou invalidant alors sa réponse.

L’apprentissage étant au cœur du module, l’enseignant peut fournir une aide sur un problème ou sur une technologie à travers un commentaire ou des liens vers des ressources externes.
L’outil permet aux enseignants de suivre l’avancée de ses élèves, en accédant à un certain nombre de statistiques et à l’historique de leurs réponses. Ainsi, l’enseignant peut voir si l’élève est bloqué sur un exercice précis, et peut lui apporter une aide par ses propres moyens.
';
$string['lipsfieldset'] = 'Custom example fieldset';
$string['lipsname'] = 'LIPS';
$string['lipsname_help'] = 'This is the content of the help tooltip associated with the lipsname field. Markdown syntax is supported.';
$string['lips'] = 'LIPS';
$string['pluginadministration'] = 'lips administration';
$string['pluginname'] = 'LIPS';

/*--------------------------------------------------
 * Number of elements per tabs 
 *------------------------------------------------*/
$string['users_table'] = 10;
$string['my_problems_table'] = 10;
$string['followed_users_table'] = 10;
$string['notifications_limit'] = 15;
$string['challenges_table'] = 10;

/*--------------------------------------------------
 * Status
 *------------------------------------------------*/
$string['coursecreator'] = 'Créateur du cours';
$string['teacher'] = 'Enseignant';
$string['student'] = 'Etudiant';

/*--------------------------------------------------
 * Difficulties
 *------------------------------------------------*/
$string['difficulty_label_elementary'] = 'Elementaire';
$string['difficulty_label_easy'] = 'Facile';
$string['difficulty_label_medium'] = 'Intermediaire';
$string['difficulty_label_difficult'] = 'Difficile';

/*--------------------------------------------------
 * Notifications
 *------------------------------------------------*/
$string['notification_follow'] = '{img} {date} - {notification_from} a ajouté l\'utilisateur {notification_to} à sa liste de suivi';
$string['notification_followed'] = '{img} {date} - L\'utilisateur {notification_from} vous a ajouté à sa liste de suivi';
$string['notification_challenge'] = '{img} {date} - {notification_from} a défié {notification_to} sur le problème {notification_problem}';
$string['notification_challenge_accepted'] = '{img} {date} - {notification_from} a accepté de relever le défi sur le problème {notification_problem}';
$string['notification_challenge_refused'] = '{img} {date} - {notification_from} a refusé de relever le défi sur le problème {notification_problem}';
$string['notification_problem_solved'] = '{img} {date} - {notification_from} a résolu le problème {notification_problem}';
$string['notification_problem_created'] = '{img} {date} - {notification_from} a créé le problème {notification_problem}';
$string['notification_problem_modified'] = '{img} {date} - {notification_from} a modifié le problème {notification_problem}';
$string['notification_problem_deleted'] = '{img} {date} - {notification_from} a supprimé le problème {notification_text}';
$string['notification_category_created'] = '{img} {date} - {notification_from} a créé la catégorie {notification_category}';
$string['notification_category_modified'] = '{img} {date} - {notification_from} a modifié la catégorie {notification_category}';
$string['notification_category_deleted'] = '{img} {date} - {notification_from} a supprimé la catégorie {notification_text}';

/*--------------------------------------------------
 * Challenges
 *------------------------------------------------*/
$string['challenge_notification'] = '{date} - {challenge_from} vous a défié sur le problème {challenge_problem}';
$string['challenge_current'] = '{challenge_problem} lancé par {challenge_from}';
$string['received_challenges'] = 'Défis reçus';
$string['sent_challenges'] = 'Défis lancés';
$string['challenge_challenged'] = 'Défié';
$string['challenges'] = 'Défis';
$string['state'] = 'Etat';
$string['administration_cancel_challenge_confirmation'] = 'Etes-vous sûr de vouloir annuler le défi ?';
$string['WAITING'] = 'En attente';
$string['ACCEPTED'] = 'Accepté';
$string['SOLVED'] = 'Résolu';
$string['REFUSED'] = 'Refusé';

/*--------------------------------------------------
 * Constants
 *------------------------------------------------*/
$string['create'] = 'Créer';
$string['modify'] = 'Modifier';
$string['delete'] = 'Supprimer';
$string['import'] = 'Import';
$string['export'] = 'Export';
$string['category'] = 'Catégorie';
$string['documentation'] = 'Documentation';
$string['name'] = 'Nom';
$string['error_impossible'] = 'Vous ne devriez pas avoir accès à cette page';
$string['language'] = 'Langage';
$string['subject'] = 'Enoncé';
$string['tips'] = 'Astuces';
$string['none'] = 'Aucun';
$string['search'] = 'Rechercher';
$string['answer'] = 'Réponse';
$string['edit'] = 'Modifier';
$string['solutions'] = 'Solutions';
$string['follow'] = 'S\'abonner';
$string['unfollow'] = 'Se désabonner';
$string['user'] = 'Utilisateur';
$string['grade'] = 'Grade';
$string['status'] = 'Statut';
$string['ranks'] = 'Classements';
$string['solved_problems'] = 'Problèmes résolus';
$string['challenge'] = 'Défier';
$string['challenged'] = 'Défiés';
$string['challenged_users'] = 'Utilisateurs défiés';
$string['no_challenges'] = 'Aucun défis';
$string['current_challenges'] = 'Défis en cours';
$string['followed_users'] = 'Utilisateurs suivis';
$string['attempts'] = "tentatives";
$string['The'] = "Le";
$string['from'] = "de";
$string['prerequisite'] = "Prérequis";
$string['difficulty'] = "Difficulté";
$string['send_response'] = "Envoyer la réponse";
$string['similar_problems'] = 'Essayez aussi';
$string['test_problem'] = 'Tester le problème';
$string['untesting'] = 'Soumettre';
$string['testing'] = 'Mode test';
$string['recent_activity'] = 'Activité récente';
$string['achievements'] = 'Badges';
$string['at'] = 'à';
$string['notifications'] = 'Notifications';
$string['no_notifications'] = 'Aucune notifications';
$string['refuse'] = 'Refuser';
$string['solve'] = 'Résoudre';
$string['accept'] = 'Accepter';
$string['Rank'] = 'Classement';
$string['filter'] = 'Filtrer';
$string['cancel'] = 'Annuler';

/*--------------------------------------------------
 * Dates
 *------------------------------------------------*/

// Months
$string['Jan'] = 'Janvier';
$string['Feb'] = 'Février';
$string['Mar'] = 'Mars';
$string['Apr'] = 'Avril';
$string['May'] = 'Mai';
$string['Jun'] = 'Juin';
$string['Jul'] = 'Juillet';
$string['Aug'] = 'Août';
$string['Sep'] = 'Septembre';
$string['Oct'] = 'Octobre';
$string['Nov'] = 'Novembre';
$string['Dec'] = 'Décembre';

// Days
$string['Mon'] = 'Lundi';
$string['Tue'] = 'Mardi';
$string['Wed'] = 'Mercredi';
$string['Thu'] = 'Jeudi';
$string['Fri'] = 'Vendredi';
$string['Sat'] = 'Samedi';
$string['Sun'] = 'Dimanche';

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
$string['challenge_author'] = 'Auteur';
$string['resolve'] = 'Résoudre';

/*--------------------------------------------------
 * Administration
 *------------------------------------------------*/

// Language
$string['administration_language_configure_title'] = 'Configurer le langage';
$string['administration_language_configure_msg'] = 'Sélectionnez le langage dans lequel vous souhaitez compiler.';
$string['administration_language_image_title'] = 'Image';
$string['administration_language_image_msg'] = 'Choisissez l\'image de ce langage de programmation.<br/>Celle-ci sera utilisée lors de l\'affichage du classement d\'un utilisateur.<br/>Taille maximum de l\'image : ';
$string['administration_language_image_success'] = 'L\'image a été modifiée';
$string['administration_language_image_type_error'] = 'L\'image que vous avez choisie d\'uploader n\'est pas une image.';
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
$string['administration_warning_existing_language'] = 'Seulement les langages de programmation non utilisés dans les autres instances de lips sont sélectionnables.<br/>Pour rappel, voici les languages déja utilisés :';

// Category
$string['administration_category_create_title'] = 'Créer une catégorie';
$string['administration_category_create_success'] = 'La catégorie a été créée';
$string['administration_category_modify_title'] = 'Modifier une catégorie';
$string['administration_category_modify_select'] = 'Sélection de la catégorie';
$string['administration_category_modify_select_error'] = 'Vous devez sélectionner une catégorie';
$string['administration_category_modify_success'] = 'La catégorie a été modifiée';
$string['administration_category_delete_title'] = 'Supprimer une catégorie';
$string['administration_category_delete_info'] = 'Seules les catégories vides peuvent être supprimées';
$string['administration_category_msg'] = 'Choisissez soit un lien vers une documentation externe soit une documentation textuelle. <strong>Vous ne pouvez pas choisir les deux</strong>.';
$string['administration_category_name_error'] = 'Vous devez saisir le nom de la catégorie';
$string['administration_category_already_exists'] = 'Ce nom de catégorie est déjà utilisé, merci d\'en choisir un autre';
$string['administration_category_documentation_link_placeholder'] = 'Lien vers une documentation externe';
$string['administration_category_documentation_text_placeholder'] = 'Documentation textuelle';
$string['administration_category_documentation_link'] = 'Documentation (Lien)';
$string['administration_category_documentation_text'] = 'Documentation (Texte)';
$string['administration_category_documentation_error'] = 'Vous ne pouvez pas avoir les deux types de documentation, choisissez soit un lien vers une documentation externe soit une documentation textuelle.';
$string['administration_delete_category_confirmation'] = "Confirmez-vous la suppression de la catégorie ?";
$string['administration_empty_categories'] = "Aucune catégorie";

// Problem
$string['administration_problem_create_title'] = 'Créer un probleme';
$string['administration_problem_modify_title'] = 'Modifier un problème';
$string['administration_problem_create_preconfig_subtitle'] = '1. Pré-configuration';
$string['administration_problem_create_informations_subtitle'] = "2. Informations sur le problème";
$string['administration_problem_create_informations_msg'] = 'Sélectionnez la catégorie à laquelle appartiendras votre problème.<br/>Entrez le nom, la difficulté et la liste des prérequis nécessaires à la réalisation du problème.';
$string['administration_problem_create_subject_subtitle'] = '3. Enoncé';
$string['administration_problem_create_subject_msg'] = "Rédigez l'énoncé et les astuces du problème";
$string['administration_problem_create_code_subtitle'] = '4. Code';
$string['administration_problem_create_code_msg'] = 'Commencez par importer les libraires utiles à la résolution du problème.<br/><br/>Ensuite, écrivez le code que l\'utilisateur devra complèter.<br/>Pour définir les zones éditables, utilisez la balise <span style="color: red">&lt;lips-code&gt;</span><br/><br/>Pour finir, rédigez la partie <strong>Tests unitaires</strong> qui correspond à la liste des tests effectués pour valider ou non le problème. Le contenu de cette zone correspond au <strong>main</strong> du programme.</br/>Le programme doit retourner <strong>True</strong> en cas de réussite et <strong>False</strong> dans le cas contraire.<br/>Vous pouvez définir la liste des tests unitaires qui seront affichés lors de la consultation du problème. Pour cela, entourez le test unitaire à afficher d\'une balise <span style="color: red">&lt;lips-unit-test&gt;&lt;/lips-unit-test&gt;.</span>';
$string['administration_problem_create_code_import_label'] = 'Importer des librairies';
$string['administration_problem_create_code_complete_label'] = 'Code à completer';
$string['administration_problem_create_code_unittest_label'] = 'Tests unitaires';
$string['administration_problem_create_success'] = 'Le problème a été créé';
$string['administration_problem_modify_success'] = 'Le problème a été modifié';
$string['administration_problems_import_confirmation'] = "Vous allez être redirigé vers la page Moodle pour la restauration de cours. Elle va vous permettre d'importer des problèmes dans LIPS.";
$string['administration_problems_export_confirmation'] = "Vous allez être redirigé vers la page Moodle pour la sauvegarde de cours. Elle va vous permettre d'exporter les problèmes de LIPS.";
$string['administration_problem_similar_subtitle'] = '5. Conseil de problèmes similaires';
$string['administration_problem_similar_subtitle_msg'] = 'Conseillez des problèmes que l\'utilisateur pourrait vouloir réaliser après la réalisation de celui-ci.';
$string['administration_language_form_code_error'] = 'Vous devez renseigner du code à completer';
$string['administration_unittests_form_code_error'] = 'You devez renseigner les tests unitaires';
$string['administration_problem_already_exists'] = 'Ce nom de problème est déjà utilisé, merci d\'en choisir un autre';
$string['administration_problem_delete_title'] = 'Supprimer un problème';
$string['administration_delete_problems_confirmation'] = 'Confirmez vous la suppression des problèmes suivants ?';
$string['administration_delete_problem_confirmation'] = 'Confirmez vous la suppression du problème suivant ?';
$string['administration_empty_problems'] = 'Aucun problème';

// My problems
$string['administration_my_problems_title'] = "Mes problèmes";

/*--------------------------------------------------
 * Web services
 *------------------------------------------------*/
$string['web_service_communication_error'] = 'Une erreur est survenue lors de la communication avec le service web.<br/>Vous ne pouvez pas sélectionner un langage de programmation.';
$string['web_service_compil_communication_error'] = 'Une erreur est survenue lors de la communication avec le service web.<br/>Vous ne pouvez pas envoyer votre solution pour le moment.';


/*--------------------------------------------------
 * Problems
 *------------------------------------------------*/
$string['problem_author'] = 'Rédacteur';
$string['problem_date_creation'] = 'Date de création';
$string['problem_nb_resolutions'] = 'Nombre de résolutions';
$string['problem_resolved_by'] = 'Résolu par';
$string['problem_owner'] = 'Problème que vous avez créé';
$string['problem_testing_picture'] = 'Problème en mode de test';
$string['problem_testing_info'] = 'Ce problème est en mode <strong>Test</strong>. Vous pouvez le passer en mode <strong>Affichage</strong> dans <strong>Administration > Problèmes > Mes problèmes</strong>.';
$string['problem_challenge_success'] = 'Les utilisateurs sélectionnés ont été défiés';
$string['problem_solved_success'] = 'Félicitations vous avez réussi !';
$string['problem_solved_fail'] = 'Solution incorrecte ';
