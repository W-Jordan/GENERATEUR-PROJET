<?php

		class CoordonneeManager {
		public static function add(Coordonnee $obj){
			$db=DbConnect::getDb();
			$q=$db->prepare("INSERT INTO Coordonnee (xCoordonnee,yCoordonnee) VALUES (:xCoordonnee,:yCoordonnee)");
			$q->bindValue(":xCoordonnee", $obj->getXCoordonnee());
			$q->bindValue(":yCoordonnee", $obj->getYCoordonnee());
			$q->execute();
		}

		public static function update(Coordonnee $obj){
 			$db=DbConnect::getDb();
			$q=$db->prepare("UPDATE Coordonnee SET xCoordonnee=:xCoordonnee,yCoordonnee=:yCoordonnee WHERE idCoordonnee=:idCoordonnee");
			$q->bindValue(":idCoordonnee", $obj->getIdCoordonnee());
			$q->bindValue(":xCoordonnee", $obj->getXCoordonnee());
			$q->bindValue(":yCoordonnee", $obj->getYCoordonnee());
			$q->execute();
		}
		public static function delete(Coordonnee $obj){
 			$db=DbConnect::getDb();
			$db->exec(
                "DELETE FROM Coordonnee WHERE idCoordonnee=" .$obj->getIdCoordonnee());
		}
		public static function findById($id){
 			$db=DbConnect::getDb();
			$id = (int) $id;
			$q=$db->query("SELECT * FROM Coordonnee WHERE idCoordonnee =".$id);
			$results = $q->fetch(PDO::FETCH_ASSOC);
			if($results != false){
				return new Coordonnee($results);
			}else{
				return false;
			}
		}
		public static function getList(){
 			$db=DbConnect::getDb();
			$liste = [];
			$q = $db->query("SELECT * FROM Coordonnee");
			while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
				if($donnees != false){
					$liste[] = new Coordonnee($donnees);
				}
			}
			return $liste;
		}
	}