<?php

	class AttributManager {

		public static function add(Attribut $obj){
			$db=DbConnect::getDb();
			$q=$db->prepare("INSERT INTO Attribut (nomAttribut,longueurAttribut,nullAttribut,LibelleLabelAttribut,idCategorie,idType,idCoordonnee,idEntite) 
										VALUES (:nomAttribut,:longueurAttribut,:nullAttribut,:LibelleLabelAttribut,:idCategorie,:idType,:idCoordonnee,:idEntite)");
			$q->bindValue(":nomAttribut", $obj->getNomAttribut());
			$q->bindValue(":longueurAttribut", $obj->getLongueurAttribut());
			$q->bindValue(":nullAttribut", $obj->getNullAttribut());
			$q->bindValue(":LibelleLabelAttribut", $obj->getLibelleLabelAttribut());
			$q->bindValue(":idCategorie", $obj->getIdCategorie());
			$q->bindValue(":idType", $obj->getIdType());
			$q->bindValue(":idCoordonnee", $obj->getIdCoordonnee());
			$q->bindValue(":idEntite", $obj->getIdEntite());
			$q->execute();
		}

		public static function update(Attribut $obj){
 			$db=DbConnect::getDb();
			$q=$db->prepare("UPDATE Attribut SET nomAttribut=:nomAttribut,longueurAttribut=:longueurAttribut,nullAttribut=:nullAttribut,LibelleLabelAttribut=:LibelleLabelAttribut,idCategorie=:idCategorie,idType=:idType,idCoordonnee=:idCoordonnee,idEntite=:idEntite WHERE idAttribut=:idAttribut");
			$q->bindValue(":idAttribut", $obj->getIdAttribut());
			$q->bindValue(":nomAttribut", $obj->getNomAttribut());
			$q->bindValue(":longueurAttribut", $obj->getLongueurAttribut());
			$q->bindValue(":nullAttribut", $obj->getNullAttribut());
			$q->bindValue(":LibelleLabelAttribut", $obj->getLibelleLabelAttribut());
			$q->bindValue(":idCategorie", $obj->getIdCategorie());
			$q->bindValue(":idType", $obj->getIdType());
			$q->bindValue(":idCoordonnee", $obj->getIdCoordonnee());
			$q->bindValue(":idEntite", $obj->getIdEntite());
			$q->execute();
		}

		public static function delete(Attribut $obj){
 			$db=DbConnect::getDb();
			$db->exec(
                "DELETE FROM Attribut WHERE idAttribut=" .$obj->getIdAttribut());
		}

		public static function findById($id){
 			$db=DbConnect::getDb();
			$id = (int) $id;
			$q=$db->query("SELECT * FROM Attribut WHERE idAttribut =".$id);
			$results = $q->fetch(PDO::FETCH_ASSOC);
			if($results != false){
				return new Attribut($results);
			}else{
				return false;
			}
		}

		public static function getList(){
 			$db=DbConnect::getDb();
			$liste = [];
			$q = $db->query("SELECT * FROM Attribut");
			while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
				if($donnees != false){
					$liste[] = new Attribut($donnees);
				}
			}
			return $liste;
		}
		
	}