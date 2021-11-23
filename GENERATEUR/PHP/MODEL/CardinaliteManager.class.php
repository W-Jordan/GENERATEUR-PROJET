<?php

		class CardinaliteManager {
		public static function add(Cardinalite $obj){
			$db=DbConnect::getDb();
			$q=$db->prepare("INSERT INTO Cardinalite (libelleCardinalite,idEntite,idTypeCardinalite,idRelation) VALUES (:libelleCardinalite,:idEntite,:idTypeCardinalite,:idRelation)");
			$q->bindValue(":libelleCardinalite", $obj->getLibelleCardinalite());
			$q->bindValue(":idEntite", $obj->getIdEntite());
			$q->bindValue(":idTypeCardinalite", $obj->getIdTypeCardinalite());
			$q->bindValue(":idRelation", $obj->getIdRelation());
			$q->execute();
		}

		public static function update(Cardinalite $obj){
 			$db=DbConnect::getDb();
			$q=$db->prepare("UPDATE Cardinalite SET libelleCardinalite=:libelleCardinalite,idEntite=:idEntite,idTypeCardinalite=:idTypeCardinalite,idRelation=:idRelation WHERE idCardinalite=:idCardinalite");
			$q->bindValue(":idCardinalite", $obj->getIdCardinalite());
			$q->bindValue(":libelleCardinalite", $obj->getLibelleCardinalite());
			$q->bindValue(":idEntite", $obj->getIdEntite());
			$q->bindValue(":idTypeCardinalite", $obj->getIdTypeCardinalite());
			$q->bindValue(":idRelation", $obj->getIdRelation());
			$q->execute();
		}
		public static function delete(Cardinalite $obj){
 			$db=DbConnect::getDb();
			$db->exec(
                "DELETE FROM Cardinalite WHERE idCardinalite=" .$obj->getIdCardinalite());
		}
		public static function findById($id){
 			$db=DbConnect::getDb();
			$id = (int) $id;
			$q=$db->query("SELECT * FROM Cardinalite WHERE idCardinalite =".$id);
			$results = $q->fetch(PDO::FETCH_ASSOC);
			if($results != false){
				return new Cardinalite($results);
			}else{
				return false;
			}
		}
		public static function getList(){
 			$db=DbConnect::getDb();
			$liste = [];
			$q = $db->query("SELECT * FROM Cardinalite");
			while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
				if($donnees != false){
					$liste[] = new Cardinalite($donnees);
				}
			}
			return $liste;
		}
	}