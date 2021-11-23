<?php

	 class Type {

		/*****************Attributs***************** */

		Private $_idType;
		Private $_libelleType;
		/*****************Accesseurs***************** */

		Public function getIdType(){ return $this->_idType;}
		Public function setIdType( $idType){ $this->_idType=$idType;}

		Public function getLibelleType(){ return $this->_libelleType;}
		Public function setLibelleType( $libelleType){ $this->_libelleType=$libelleType;}

		/*****************Constructeur***************** */

		Public function __construct(array $options = []){ (!empty($options)) ? $this->hydrate($options) :""; }

		public function hydrate($data){
			foreach ($data as $key => $value){
				$methode = "set" . ucfirst($key);
				(is_callable([$this, $methode])) ? $this->$methode($value) : "" ;
			}
		}

		
	}