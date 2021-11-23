<?php

	 class Entite {

		/*****************Attributs***************** */

		Private $_idEntite;
		Private $_nomEntite;
		Private $_idRelation;
		Private $_idCoordonnee;
		Private $_idProjet;
		/*****************Accesseurs***************** */

		Public function getIdEntite(){ return $this->_idEntite;}
		Public function setIdEntite( $idEntite){ $this->_idEntite=$idEntite;}

		Public function getNomEntite(){ return $this->_nomEntite;}
		Public function setNomEntite( $nomEntite){ $this->_nomEntite=$nomEntite;}

		Public function getIdRelation(){ return $this->_idRelation;}
		Public function setIdRelation( $idRelation){ $this->_idRelation=$idRelation;}

		Public function getIdCoordonnee(){ return $this->_idCoordonnee;}
		Public function setIdCoordonnee( $idCoordonnee){ $this->_idCoordonnee=$idCoordonnee;}

		Public function getIdProjet(){ return $this->_idProjet;}
		Public function setIdProjet($idProjet){ $this->_idProjet=$idProjet;}

		/*****************Constructeur***************** */

		Public function __construct(array $options = []){ (!empty($options)) ? $this->hydrate($options) :""; }

		public function hydrate($data){
			foreach ($data as $key => $value){
				$methode = "set" . ucfirst($key);
				(is_callable([$this, $methode])) ? $this->$methode($value) : "" ;
			}
		}

		
	}