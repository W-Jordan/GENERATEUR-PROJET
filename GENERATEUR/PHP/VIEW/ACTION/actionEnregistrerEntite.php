<?php
    $idProjet="";
    if(isset($_POST['entiteIdProjet'])){
        $idProjet = $_POST['entiteIdProjet'];
        $entite = new Entite(['nomEntite'=>$_POST['nomEntite'],'idProjet'=>$idProjet]);
        
        // var_dump($entite);
        // if(EntiteManager::checkDuplicate(1,$entite->getNomEntite())){
            
        // }
        
        EntiteManager::add($entite);
        $entite = EntiteManager::findById(EntiteManager::getIdMax());
    
        if(isset($_POST['nomAttribut'])){
            for ($i=0; $i <count($_POST['nomAttribut']); $i++) {
                $attribut =  new Attribut([
                    'nomAttribut' => $_POST['nomAttribut'][$i],
                    'longueurAttribut' => $_POST['longueurAttribut'][$i],
                    'nullAttribut' => $_POST['nullAttribut'][$i],
                    'libelleLabelAttribut' => $_POST['libelleLabelAttribut'][$i],
                    'idCategorie' => $_POST['idCategorie'][$i],
                    'idType' => $_POST['idType'][$i],
                    'idEntite'=> 1,
                ]);
                AttributManager::add($attribut);
            }
        }

    }
    header("location:?page=MCD&idProjet=".$idProjet);
