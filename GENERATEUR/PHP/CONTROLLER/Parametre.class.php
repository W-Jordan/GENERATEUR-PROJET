<?php
	class Parametre{
		private static $host;
		private static $port;
		private static $dbname;
		private static $login;
		private static $pwd;

		public static function getHost(){return self::$host;}
		public static function getPort(){return self::$port;}
		public static function getDbname(){return self::$dbname;}
		public static function getLogin(){return self::$login;}
		public static function getPwd(){return self::$pwd;}

		public static function init(){

			// Ouvrir le JSON
			if (file_exists("Parametre.json")){
				$fichierJSON = file_get_contents('Parametre.json');
				$fichierJSON = json_decode($fichierJSON, true);

				self::$host = $fichierJSON['Host'];
				self::$port = $fichierJSON['Port'];
				self::$dbname = $fichierJSON['DBName'];
				self::$login = $fichierJSON['Login'];
				self::$pwd = $fichierJSON['Pwd'];
			}else{
				var_dump("Le fichier Parametre est introuvable !!");
			}
		}

		public static function add($table,$lesColonnes,$filtre,$api){

		
		}

		public static function update($table,$lesColonnes,$filtre,$api){

		
		}

		public static function delete($table,$lesColonnes,$filtre,$api){

			
		}

		public static function getListByFiltre($table,$lesColonnes,$filtre,$api){
			$laCondition="";
			$operateur="";
			$i=0;

			// Boucle sur les filtres
			foreach ($filtre as $key => $value) {
				$operateur=($i==0)?" WHERE ":" AND ";
				$laCondition.=$operateur.$key;

				// Si c'est un array 
				if(is_array($value)){
					// Boucle sur le tableau pour affichage dans le IN
					$in="";
					foreach ($value as $key ) {
						$in.="'".$key."',";
					}
					$laCondition.=" IN (".substr($in,0,-1).") ";
				// Valeur toute simple
				}else{
					$laCondition.="='".$value."'";
				}

				$i++;
			}
			
			// Transformation du tableau de colonne en liste
			$lesColonnes = (is_array($lesColonnes))?implode(",",$lesColonnes):$lesColonnes;

			$db=DBConnect::getDb();
			$liste = [];
			$q = $db->query("SELECT ".$lesColonnes." FROM ".$table." " .$laCondition);
			while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
				if($donnees != false){
					$liste[]=($api)?$donnees:new $table($donnees);
				}
			}
			return $liste;
		}

		public static function creerSelect($table,$idtable,$info,$colonne,$classCSS,$filtre,$disabled,$api){
			Gestionnaire::getListByFiltre($table,$colonne,$filtre,$api);
			echo'<select class="borderRad8 borderBlack1" name='.$idtable. ' class='.$classCSS.' '.$disabled." >";
				
			echo'</select>';
		}

	}
