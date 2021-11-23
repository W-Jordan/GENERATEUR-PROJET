<?php

    switch($mode){
        case "XML":

            if(isset($_FILES['ficXML'])){ 
                $dossier = '../XML/';
                $fichier = basename($_FILES['ficXML']['name']);
                if(move_uploaded_file($_FILES['ficXML']['tmp_name'], $dossier . $fichier)){
                    echo 'Upload effectué avec succès !';
                }else{
                    echo "Echec du téléchargement !";
                    header("refresh:3;url=index.php?page=XML");
                }

                // Test la présence du fichier
                (file_exists($dossier . $fichier))? $xml=simplexml_load_file($dossier . $fichier):exit('Echec lors de l\'ouverture du fichier xml.');
                
                $leProjet = new XMLProjet(
                                ['nom'=>'monProjet',
                                'repertoireSource'=>getcwd().'/monProjet',
                                'userDBDev'=>'devDB',
                                'userDBUser'=>'userDB',
                                ]);
                $leProjet->traiterXML($xml);
                
                $leProjet->creationRepertoireProjet();

                DBConnect::init($leProjet->getNom());

                // SQL
                // Creation des tables du projet
                if($leProjet->executerScriptCreateTable()){
                    // Sauvegarde fichier CREATE TABLE
                    file_put_contents(
                    $leProjet->getRepertoireSource()."/SQL/script_creation_".$leProjet->getNom().".sql",
                    $leProjet->scriptSQLCreateTable()
                    );
                }

                // Creation des Vues du projet
                if($leProjet->executerScriptCreateView()){
                    // Sauvegarde fichier CREATE VIEW
                    file_put_contents(
                    $leProjet->getRepertoireSource()."/SQL/script_views_".$leProjet->getNom().".sql",
                    $leProjet->scriptSQLCreateView()
                    );
                }    
                
                // Creation des droits SQL du Projet (GRANT)
                if($leProjet->executerScriptGrant()){
                    // Sauvegarde fichier CREATE VIEW
                    file_put_contents(
                    $leProjet->getRepertoireSource()."/SQL/script_GRANT_".$leProjet->getNom().".sql",
                    $leProjet->scriptSQLGRANT()
                    );
                }
                
                // ! Changer les comment en mettant les intitules (pour automatiser dans Class)

                // PHP
                    // Creation index.php
                    // file_put_contents($leProjet->getRepertoireSource()."/PHP/index.php",
                    //             "<?php"."\n\t".'function chargementClass($classe){'."\n\t\t".'require("class/$classe .class.php");'."\n\t"."}".
                    //             "\n\t"."spl_autoload_register('chargementClass');");
                    
                    // Creation des Classes
                    $leProjet->genererClassesPHP();

                    // Creation des Classes Manager 
                    // ! (voir pour les TestManager)
                    $leProjet->genererManagerPHP();

            }
            break;
        case "PHP":
            break;
        case "DB":
            break;
        default:
            break;
    }

include "footer.php";

    // header("location:./index.php");