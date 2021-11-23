<?php

	class ProjetManager {
		
		public static function add(Projet $obj){
			$db=DbConnect::getDb();
			$q=$db->prepare("INSERT INTO Projet (nomProjet) VALUES (:nomProjet)");
			$q->bindValue(":nomProjet", $obj->getNomProjet());
			$q->execute();
		}

		public static function update(Projet $obj){
 			$db=DbConnect::getDb();
			$q=$db->prepare("UPDATE Projet SET nomProjet=:nomProjet WHERE idProjet=:idProjet");
			$q->bindValue(":idProjet", $obj->getIdProjet());
			$q->bindValue(":nomProjet", $obj->getNomProjet());
			$q->execute();
		}
		public static function delete(Projet $obj){
 			$db=DbConnect::getDb();
			$db->exec(
                "DELETE FROM Projet WHERE idProjet=" .$obj->getIdProjet());
		}
		public static function findById($id){
 			$db=DbConnect::getDb();
			$id = (int) $id;
			$q=$db->query("SELECT * FROM Projet WHERE idProjet =".$id);
			$results = $q->fetch(PDO::FETCH_ASSOC);
			if($results != false){
				return new Projet($results);
			}else{
				return false;
			}
		}
		public static function getList(){
 			$db=DbConnect::getDb();
			$liste = [];
			$q = $db->query("SELECT * FROM projet");
			while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
				if($donnees != false){
					$liste[] = new Projet($donnees);
				}
			}
			return $liste;
		}
	}