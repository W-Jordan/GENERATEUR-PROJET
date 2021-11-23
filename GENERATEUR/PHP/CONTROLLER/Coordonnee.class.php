<?php

	 class Coordonnee {

		/*****************Attributs***************** */

		Private $_idCoordonnee;
		Private $_xCoordonnee;
		Private $_yCoordonnee;
		/*****************Accesseurs***************** */

		Public function getIdCoordonnee(){ return $this->_idCoordonnee;}

		Public function setIdCoordonnee( $idCoordonnee){ $this->_idCoordonnee=$idCoordonnee;}

		Public function getXCoordonnee(){ return $this->_xCoordonnee;}

		Public function setXCoordonnee( $xCoordonnee){ $this->_xCoordonnee=$xCoordonnee;}

		Public function getYCoordonnee(){ return $this->_yCoordonnee;}

		Public function setYCoordonnee( $yCoordonnee){ $this->_yCoordonnee=$yCoordonnee;}

		/*****************Constructeur***************** */

		Public function __construct(array $options = []){ (!empty($options)) ? $this->hydrate($options) :""; }

		public function hydrate($data){
			foreach ($data as $key => $value){
				$methode = "set" . ucfirst($key);
				(is_callable([$this, $methode])) ? $this->$methode($value) : "" ;
			}
		}

		
	}