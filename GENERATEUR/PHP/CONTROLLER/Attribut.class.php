<?php

	 class Attribut {

		/*****************Attributs***************** */

		Private $_idAttribut;
		Private $_nomAttribut;
		Private $_longueurAttribut;
		Private $_nullAttribut;
		Private $_LibelleLabelAttribut;
		Private $_idCategorie;
		Private $_idType;
		Private $_idCoordonnee;
		Private $_idEntite;
		/*****************Accesseurs***************** */

		Public function getIdAttribut(){ return $this->_idAttribut;}

		Public function setIdAttribut( $idAttribut){ $this->_idAttribut=$idAttribut;}

		Public function getNomAttribut(){ return $this->_nomAttribut;}

		Public function setNomAttribut( $nomAttribut){ $this->_nomAttribut=$nomAttribut;}

		Public function getLongueurAttribut(){ return $this->_longueurAttribut;}

		Public function setLongueurAttribut( $longueurAttribut){ $this->_longueurAttribut=$longueurAttribut;}

		Public function getNullAttribut(){ return $this->_nullAttribut;}

		Public function setNullAttribut( $nullAttribut){ $this->_nullAttribut=$nullAttribut;}

		Public function getLibelleLabelAttribut(){ return $this->_LibelleLabelAttribut;}

		Public function setLibelleLabelAttribut( $LibelleLabelAttribut){ $this->_LibelleLabelAttribut=$LibelleLabelAttribut;}

		Public function getIdCategorie(){ return $this->_idCategorie;}

		Public function setIdCategorie( $idCategorie){ $this->_idCategorie=$idCategorie;}

		Public function getIdType(){ return $this->_idType;}

		Public function setIdType( $idType){ $this->_idType=$idType;}

		Public function getIdCoordonnee(){ return $this->_idCoordonnee;}

		Public function setIdCoordonnee( $idCoordonnee){ $this->_idCoordonnee=$idCoordonnee;}

		Public function getIdEntite(){ return $this->_idEntite;}

		Public function setIdEntite( $idEntite){ $this->_idEntite=$idEntite;}

		/*****************Constructeur***************** */

		Public function __construct(array $options = []){ (!empty($options)) ? $this->hydrate($options) :""; }

		public function hydrate($data){
			foreach ($data as $key => $value){
				$methode = "set" . ucfirst($key);
				(is_callable([$this, $methode])) ? $this->$methode($value) : "" ;
			}
		}

		
	}