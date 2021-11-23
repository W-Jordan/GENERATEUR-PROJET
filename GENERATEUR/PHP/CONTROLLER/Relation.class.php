<?php

	 class Relation {

		/*****************Attributs***************** */

		Private $_idRelation;
		Private $_libelleRelation;
		Private $_idCoordonnee;
		/*****************Accesseurs***************** */

		Public function getIdRelation(){ return $this->_idRelation;}

		Public function setIdRelation( $idRelation){ $this->_idRelation=$idRelation;}

		Public function getLibelleRelation(){ return $this->_libelleRelation;}

		Public function setLibelleRelation( $libelleRelation){ $this->_libelleRelation=$libelleRelation;}

		Public function getIdCoordonnee(){ return $this->_idCoordonnee;}

		Public function setIdCoordonnee( $idCoordonnee){ $this->_idCoordonnee=$idCoordonnee;}

		/*****************Constructeur***************** */

		Public function __construct(array $options = []){ (!empty($options)) ? $this->hydrate($options) :""; }

		public function hydrate($data){
			foreach ($data as $key => $value){
				$methode = "set" . ucfirst($key);
				(is_callable([$this, $methode])) ? $this->$methode($value) : "" ;
			}
		}

		
	}