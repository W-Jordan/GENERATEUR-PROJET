<?php

    switch($_POST['mode']){
        case 'creationProjet':
            $_SESSION['projet']=new Projet($_POST);
            header("location:index.php?page=MCD");
            break;
        case 'creationMCD':
            // 
            break;
        default:
            break;
    }
   