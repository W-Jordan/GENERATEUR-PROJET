<?php
	class EntiteManager {
		public static function add(Entite $obj){
			$db=DbConnect::getDb();
			$q=$db->prepare("INSERT INTO Entite (nomEntite,idRelation,idCoordonnee,idProjet) VALUES (:nomEntite,:idRelation,:idCoordonnee,:idProjet)");
			$q->bindValue(":nomEntite", $obj->getNomEntite());
			$q->bindValue(":idRelation", $obj->getIdRelation());
			$q->bindValue(":idCoordonnee", $obj->getIdCoordonnee());
			$q->bindValue(":idProjet", $obj->getIdProjet());
			$q->execute();
		}

		public static function update(Entite $obj){
 			$db=DbConnect::getDb();
			$q=$db->prepare("UPDATE Entite SET nomEntite=:nomEntite,idRelation=:idRelation,idCoordonnee=:idCoordonnee,idProjet=:idProjet WHERE idEntite=:idEntite");
			$q->bindValue(":idEntite", $obj->getIdEntite());
			$q->bindValue(":nomEntite", $obj->getNomEntite());
			$q->bindValue(":idRelation", $obj->getIdRelation());
			$q->bindValue(":idCoordonnee", $obj->getIdCoordonnee());
			$q->bindValue(":idProjet", $obj->getIdProjet());
			$q->execute();
		}
		public static function delete(Entite $obj){
 			$db=DbConnect::getDb();
			$db->exec(
                "DELETE FROM Entite WHERE idEntite=" .$obj->getIdEntite());
		}
		public static function findById($id){
 			$db=DbConnect::getDb();
			$id = (int) $id;
			$q=$db->query("SELECT * FROM Entite WHERE idEntite =".$id);
			$results = $q->fetch(PDO::FETCH_ASSOC);
			if($results != false){
				return new Entite($results);
			}else{
				return false;
			}
		}
		public static function getList(){
 			$db=DbConnect::getDb();
			$liste = [];
			$q = $db->query("SELECT * FROM Entite");
			while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
				if($donnees != false){
					$liste[] = new Entite($donnees);
				}
			}
			return $liste;
		}

		public static function getIdMax(){
			$db=DbConnect::getDb();
			$q=$db->query("SELECT MAX(idEntite) FROM entite");
			$results = $q->fetch(PDO::FETCH_ASSOC);
			if($results != false){
				return $results['MAX(idEntite)'];
			}else{
				return false;
			}
		}

		public static function getListByIdProjet($idProjet,$api){
			$db=DbConnect::getDb();
			$liste = $json=[];
			$q = $db->query("SELECT * FROM Entite WHERE idProjet=".$idProjet);
			while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
				if($donnees != false){
					$liste[] = new Entite($donnees);
					$json[]=$donnees;
				}
			}
			return ($api)?$json:$liste;
		}

		public static function checkDuplicate($idProjet,$nomEntite){
			$db=DbConnect::getDb();
			$idProjet = (int) $idProjet;
			$q=$db->query(
				"SELECT * 
				FROM Entite 
				WHERE nomEntite ='".$nomEntite."'
				AND idProjet =".$idProjet);
			$results = $q->fetch(PDO::FETCH_ASSOC);
			if($results != false){
				return new Entite($results);
			}else{
				return false;
			}
	   }
	}