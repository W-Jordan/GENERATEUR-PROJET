<?php

	function chargerPage($tab){
		$fichier = $tab['page'];
		$titre = $tab['titre'];
		// $logger = $tab['logger'];
		// $roles = $tab['roles'];
		$api=$tab['api'];
		$rep = $tab['rep'];

		if ($api){
			include $rep.$fichier.'.php';
		}else{
			include './PHP/VIEW/UTIL/header.php';
			// if (($logger && !isset($_SESSION['utilisateur'])) || (isset($_SESSION['utilisateur']) && !in_array($_SESSION['utilisateur']->getRole(), $roles))){
			// 	$fichier = "Accueil";
			// }
			include $rep.$fichier.'.php';
			include './PHP/VIEW/UTIL/footer.php';
		}
	}

	function crypter($mdp){
		return md5(md5($mdp).strlen($mdp));
	}

	function ChargerClasse($classe){
		if (file_exists("./PHP/CONTROLLER/" . $classe . ".class.php")){
			require "./PHP/CONTROLLER/" . $classe . ".class.php";
		}
		if (file_exists("./PHP/MODEL/" . $classe . ".class.php")){
			require "./PHP/MODEL/" . $classe . ".class.php";
		}

		if (file_exists("./CONTROLLER/" . $classe . ".class.php")){
			require "./CONTROLLER/" . $classe . ".class.php";
		}
		if (file_exists("./MODEL/" . $classe . ".class.php")){
			require "./MODEL/" . $classe . ".class.php";
		}


		if (file_exists("./GENERATEUR XML/class/" . $classe . ".class.php")){
			require "./GENERATEUR XML/class/" . $classe . ".class.php";
		}
	}