<?php

	 class TypeCardinalite {

		/*****************Attributs***************** */

		Private $_idTypeCardinalite;
		Private $_libelleTypeCardinalite;
		/*****************Accesseurs***************** */

		Public function getIdTypeCardinalite(){ return $this->_idTypeCardinalite;}

		Public function setIdTypeCardinalite( $idTypeCardinalite){ $this->_idTypeCardinalite=$idTypeCardinalite;}

		Public function getLibelleTypeCardinalite(){ return $this->_libelleTypeCardinalite;}

		Public function setLibelleTypeCardinalite( $libelleTypeCardinalite){ $this->_libelleTypeCardinalite=$libelleTypeCardinalite;}

		/*****************Constructeur***************** */

		Public function __construct(array $options = []){ (!empty($options)) ? $this->hydrate($options) :""; }

		public function hydrate($data){
			foreach ($data as $key => $value){
				$methode = "set" . ucfirst($key);
				(is_callable([$this, $methode])) ? $this->$methode($value) : "" ;
			}
		}

		
	}