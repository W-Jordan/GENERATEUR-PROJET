<?php

		class TypeManager {
		public static function add(Type $obj){
			$db=DbConnect::getDb();
			$q=$db->prepare("INSERT INTO Type (libelleType) VALUES (:libelleType)");
			$q->bindValue(":libelleType", $obj->getLibelleType());
			$q->execute();
		}

		public static function update(Type $obj){
 			$db=DbConnect::getDb();
			$q=$db->prepare("UPDATE Type SET libelleType=:libelleType WHERE idType=:idType");
			$q->bindValue(":idType", $obj->getIdType());
			$q->bindValue(":libelleType", $obj->getLibelleType());
			$q->execute();
		}
		public static function delete(Type $obj){
 			$db=DbConnect::getDb();
			$db->exec(
                "DELETE FROM Type WHERE idType=" .$obj->getIdType());
		}
		public static function findById($id){
 			$db=DbConnect::getDb();
			$id = (int) $id;
			$q=$db->query("SELECT * FROM Type WHERE idType =".$id);
			$results = $q->fetch(PDO::FETCH_ASSOC);
			if($results != false){
				return new Type($results);
			}else{
				return false;
			}
		}
		public static function getList(){
 			$db=DbConnect::getDb();
			$liste = [];
			$q = $db->query("SELECT * FROM Type");
			while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
				if($donnees != false){
					$liste[] = new Type($donnees);
				}
			}
			return $liste;
		}
	}