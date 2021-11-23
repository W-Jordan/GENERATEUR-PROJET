<?php

	 class Projet {

		/*****************Attributs***************** */

		Private $_idProjet;
		Private $_nomProjet;
		/*****************Accesseurs***************** */

		Public function getIdProjet(){ return $this->_idProjet;}
		Public function setIdProjet( $idProjet){ $this->_idProjet=$idProjet;}

		Public function getNomProjet(){ return $this->_nomProjet;}
		Public function setNomProjet( $nomProjet){ $this->_nomProjet=$nomProjet;}


		/*****************Constructeur***************** */

		Public function __construct(array $options = []){ (!empty($options)) ? $this->hydrate($options) :""; }

		public function hydrate($data){
			foreach ($data as $key => $value){
				$methode = "set" . ucfirst($key);
				(is_callable([$this, $methode])) ? $this->$methode($value) : "" ;
			}
		}

		
	}