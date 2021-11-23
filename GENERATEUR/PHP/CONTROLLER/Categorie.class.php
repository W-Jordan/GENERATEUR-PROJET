<?php

	 class Categorie {

		/*****************Attributs***************** */

		Private $_idCategorie;
		Private $_libelleCategorie;
		/*****************Accesseurs***************** */

		Public function getIdCategorie(){ return $this->_idCategorie;}
		Public function setIdCategorie( $idCategorie){ $this->_idCategorie=$idCategorie;}

		Public function getLibelleCategorie(){ return $this->_libelleCategorie;}
		Public function setLibelleCategorie( $libelleCategorie){ $this->_libelleCategorie=$libelleCategorie;}

		/*****************Constructeur***************** */

		Public function __construct(array $options = []){ (!empty($options)) ? $this->hydrate($options) :""; }

		public function hydrate($data){
			foreach ($data as $key => $value){
				$methode = "set" . ucfirst($key);
				(is_callable([$this, $methode])) ? $this->$methode($value) : "" ;
			}
		}

		
	}