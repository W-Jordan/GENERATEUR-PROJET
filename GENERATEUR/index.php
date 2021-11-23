<?php 

    include './PHP/Outils.php';
    spl_autoload_register('ChargerClasse');

    session_start();

    Parametre::init();
    $db = DbConnect::init();

    $routes=[
        'default'=>['rep'=>'./PHP/VIEW/FORM/','page'=>'formIndex','titre'=>'Menu','api'=>false],
        '404'=>['rep'=>'./PHP/VIEW/UTIL/','page'=>'404','titre'=>'Erreur 404','api'=>false],

        'PROJET'=>['rep'=>'./PHP/VIEW/FORM/','page'=>'formMCD','titre'=>'Création du Projet','api'=>false],
        'MCD'=>['rep'=>'./PHP/VIEW/FORM/','page'=>'formMCD','titre'=>'MCD','api'=>false],
        'XML'=>['rep'=>'./PHP/VIEW/FORM/','page'=>'formXML','titre'=>'XML','api'=>false],
        'MYSQL'=>['rep'=>'./PHP/VIEW/FORM/','page'=>'formMYSQL','titre'=>'MYSQL','api'=>false],
        'PHP'=>['rep'=>'./PHP/VIEW/FORM/','page'=>'formPHP','titre'=>'PHP','api'=>false],
        'HTML'=>['rep'=>'./PHP/VIEW/FORM/','page'=>'formHTML','titre'=>'HTML','api'=>false],
        'CSS'=>['rep'=>'./PHP/VIEW/FORM/','page'=>'formCSS','titre'=>'CSS','api'=>false],

        'actionXML'=>['rep'=>'./PHP/VIEW/ACTION/','page'=>'actionXML','titre'=>'ActionXML','api'=>false],
        'actionMCD'=>['rep'=>'./PHP/VIEW/ACTION/','page'=>'actionXMCD','titre'=>'MCD','api'=>false],
        'actionEntite'=>['rep'=>'./PHP/VIEW/ACTION/','page'=>'actionEnregistrerEntite','titre'=>'Ajouter Entite','api'=>false],
        'listeAPI'=>['rep'=>'./PHP/VIEW/ACTION/','page'=>'listeAPI','titre'=>'AJAX','api'=>true]
    ];

    if (isset($_GET['page'])){
        if(isset($routes[$_GET['page']])){
            chargerPage($routes[$_GET['page']]);
        }else{
            chargerPage($routes["404"]);
        }
    }else{
        chargerPage($routes["default"]);
    }