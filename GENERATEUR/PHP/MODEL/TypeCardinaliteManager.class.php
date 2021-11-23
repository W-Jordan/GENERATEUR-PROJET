<?php

		class TypeCardinaliteManager {
		public static function add(TypeCardinalite $obj){
			$db=DbConnect::getDb();
			$q=$db->prepare("INSERT INTO TypeCardinalite (libelleTypeCardinalite) VALUES (:libelleTypeCardinalite)");
			$q->bindValue(":libelleTypeCardinalite", $obj->getLibelleTypeCardinalite());
			$q->execute();
		}

		public static function update(TypeCardinalite $obj){
 			$db=DbConnect::getDb();
			$q=$db->prepare("UPDATE TypeCardinalite SET libelleTypeCardinalite=:libelleTypeCardinalite WHERE idTypeCardinalite=:idTypeCardinalite");
			$q->bindValue(":idTypeCardinalite", $obj->getIdTypeCardinalite());
			$q->bindValue(":libelleTypeCardinalite", $obj->getLibelleTypeCardinalite());
			$q->execute();
		}
		public static function delete(TypeCardinalite $obj){
 			$db=DbConnect::getDb();
			$db->exec(
                "DELETE FROM TypeCardinalite WHERE idTypeCardinalite=" .$obj->getIdTypeCardinalite());
		}
		public static function findById($id){
 			$db=DbConnect::getDb();
			$id = (int) $id;
			$q=$db->query("SELECT * FROM TypeCardinalite WHERE idTypeCardinalite =".$id);
			$results = $q->fetch(PDO::FETCH_ASSOC);
			if($results != false){
				return new TypeCardinalite($results);
			}else{
				return false;
			}
		}
		public static function getList(){
 			$db=DbConnect::getDb();
			$liste = [];
			$q = $db->query("SELECT * FROM TypeCardinalite");
			while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
				if($donnees != false){
					$liste[] = new TypeCardinalite($donnees);
				}
			}
			return $liste;
		}
	}