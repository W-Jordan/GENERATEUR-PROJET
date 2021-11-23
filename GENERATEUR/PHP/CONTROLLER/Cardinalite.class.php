<?php

	 class Cardinalite {

		/*****************Attributs***************** */

		Private $_idCardinalite;
		Private $_libelleCardinalite;
		Private $_idEntite;
		Private $_idTypeCardinalite;
		Private $_idRelation;
		/*****************Accesseurs***************** */

		Public function getIdCardinalite(){ return $this->_idCardinalite;}

		Public function setIdCardinalite( $idCardinalite){ $this->_idCardinalite=$idCardinalite;}

		Public function getLibelleCardinalite(){ return $this->_libelleCardinalite;}

		Public function setLibelleCardinalite( $libelleCardinalite){ $this->_libelleCardinalite=$libelleCardinalite;}

		Public function getIdEntite(){ return $this->_idEntite;}

		Public function setIdEntite( $idEntite){ $this->_idEntite=$idEntite;}

		Public function getIdTypeCardinalite(){ return $this->_idTypeCardinalite;}

		Public function setIdTypeCardinalite( $idTypeCardinalite){ $this->_idTypeCardinalite=$idTypeCardinalite;}

		Public function getIdRelation(){ return $this->_idRelation;}

		Public function setIdRelation( $idRelation){ $this->_idRelation=$idRelation;}

		/*****************Constructeur***************** */

		Public function __construct(array $options = []){ (!empty($options)) ? $this->hydrate($options) :""; }

		public function hydrate($data){
			foreach ($data as $key => $value){
				$methode = "set" . ucfirst($key);
				(is_callable([$this, $methode])) ? $this->$methode($value) : "" ;
			}
		}

		
	}