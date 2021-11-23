<?php

		class RelationManager {
		public static function add(Relation $obj){
			$db=DbConnect::getDb();
			$q=$db->prepare("INSERT INTO Relation (libelleRelation,idCoordonnee) VALUES (:libelleRelation,:idCoordonnee)");
			$q->bindValue(":libelleRelation", $obj->getLibelleRelation());
			$q->bindValue(":idCoordonnee", $obj->getIdCoordonnee());
			$q->execute();
		}

		public static function update(Relation $obj){
 			$db=DbConnect::getDb();
			$q=$db->prepare("UPDATE Relation SET libelleRelation=:libelleRelation,idCoordonnee=:idCoordonnee WHERE idRelation=:idRelation");
			$q->bindValue(":idRelation", $obj->getIdRelation());
			$q->bindValue(":libelleRelation", $obj->getLibelleRelation());
			$q->bindValue(":idCoordonnee", $obj->getIdCoordonnee());
			$q->execute();
		}
		public static function delete(Relation $obj){
 			$db=DbConnect::getDb();
			$db->exec(
                "DELETE FROM Relation WHERE idRelation=" .$obj->getIdRelation());
		}
		public static function findById($id){
 			$db=DbConnect::getDb();
			$id = (int) $id;
			$q=$db->query("SELECT * FROM Relation WHERE idRelation =".$id);
			$results = $q->fetch(PDO::FETCH_ASSOC);
			if($results != false){
				return new Relation($results);
			}else{
				return false;
			}
		}
		public static function getList(){
 			$db=DbConnect::getDb();
			$liste = [];
			$q = $db->query("SELECT * FROM Relation");
			while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
				if($donnees != false){
					$liste[] = new Relation($donnees);
				}
			}
			return $liste;
		}
	}