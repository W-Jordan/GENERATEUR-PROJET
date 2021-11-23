<?php

    $dossier = './XML/';
    $fichier = basename($_FILES['ficXML']['name']);
    if(move_uploaded_file($_FILES['ficXML']['tmp_name'], $dossier . $fichier)){
        // c'est OK
    }else{
        echo "Echec du téléchargement !";
        header("refresh:4;url=index.php?page=XML");
    }

    // Test la présence du fichier
    if(file_exists($dossier . $fichier)){
        $xml=simplexml_load_file($dossier . $fichier);
    }else{
        exit("Echec lors de l'ouverture du fichier xml.");
    }

    $leProjet = new XMLProjet(
        ['nom'=>str_replace(' ','',substr($fichier,0,-4)),
        'repertoireSource'=>'./GENERATION/'.str_replace(' ','',substr($fichier,0,-4)),
        'userDBDev'=>'devDB',
        'userDBUser'=>'userDB',
        ]);
        

    $leProjet->traiterXML($xml);
    $leProjet->creationRepertoireProjet();

    // On se connecte à la BDD pour executer les requetes
    DBConnect::init();

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
    
    // PHP
        $leProjet->genererClassesPHP();       
        $leProjet->genererManagerPHP();
        $leProjet->genererOutils();
        $leProjet->genererParametre();
        $leProjet->genereIndexPHP();
            $leProjet->genere404PHP();
            $leProjet->genereActionPHP();

            // ! (voir pour les TestManager)
    
    // ZIP
        
        function recurseRmdir($dir) {
            $files = array_diff(scandir($dir), array('.','..'));
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? recurseRmdir("$dir/$file") : unlink("$dir/$file");
            }
            return rmdir($dir);
        }

        // Recursive qui supprime le repertoire et sous repertoire

        recurseRmdir($leProjet->getRepertoireSource().'/GENERATION/');

        
        // Création repertoire GENERATION
        $rootPath = realpath($leProjet->getRepertoireSource());
        $zip = new ZipArchive();
        $zip->open($leProjet->getRepertoireSource()."/".$leProjet->getNom().'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Itérateur de répertoire récursif
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file){
            if (!$file->isDir()){
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        header('Content-type: application/zip');
        header('Content-Transfer-Encoding: fichier');
        header('Content-Disposition: attachment; filename='.$leProjet->getRepertoireSource().'/'.$leProjet->getNom().'.zip');
        header('location:'.$leProjet->getRepertoireSource().'/'.$leProjet->getNom().'.zip');