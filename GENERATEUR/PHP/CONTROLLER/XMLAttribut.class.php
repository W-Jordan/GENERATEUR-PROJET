<?php

	 class XMLAttribut {

		/*****************Attributs***************** */

		Private $_name="";
		Private $_type="";
		Private $_size1="";
		Private $_size2="";
		Private $_key="";
		Private $_isnull=TRUE;
		Private $_comment="";
		Private $_classeLiee="";

		
		/*****************Accesseurs***************** */

		Public function getName(){ return $this->_name;}
		Public function setName($name){ $this->_name=$name;}

		Public function getType(){ return $this->_type;}
		Public function setType($type){ $this->_type=$type;}

		Public function getSize1(){ return $this->_size1;}
		Public function setSize1($size1){ $this->_size1=$size1;}

		Public function getSize2(){ return $this->_size2;}
		Public function setSize2($size2){ $this->_size2=$size2;}

		Public function getKey(){ return $this->_key;}
		Public function setKey($key){ $this->_key=$key;}

		Public function getIsnull(){ return $this->_isnull;}
		Public function setIsnull($isnull){ $this->_isnull=$isnull;}

		Public function getComment(){ return $this->_comment;}
		Public function setComment($comment){ $this->_comment=$comment;}

		Public function getClasseLiee(){ return $this->_classeLiee;}
		Public function setClasseLiee($classeLiee){ $this->_classeLiee=$classeLiee;}

		/*****************Constructeur***************** */

		Public function __construct(array $options = []){ (!empty($options)) ? $this->hydrate($options) :""; }

		public function hydrate($data){
			foreach ($data as $key => $value){
				$methode = "set" . ucfirst($key);
				(is_callable([$this, $methode])) ? $this->$methode($value) : "" ;
			}
		}

		/*****************Autres Méthodes***************** */

		/**
		 * genere le script SQL de l'attribut (objet lui meme)
		 *
		 * @return String
		 */
		public function scriptSQL(){

			// Affiche proprement le type (avec/sans sa taille : exemple de varchar(50) )
			$type=(strlen($this->getSize1())!=0) ? $this->getType()."(".$this->getSize1().")":$this->getType();

			// Affiche proprement le type si c'est Auto_increment
			if($this->getType()=="Auto_increment"){
				$type="INT ".strtoupper($this->getType());//." NOT NULL";
			}

			// Clé primaire
			$key= ($this->getKey()=="PRIMARY KEY")? $this->getKey():"";

			// gestion de la nullite
			$isnull = ($this->getIsnull()=="false")? "NOT NULL":"NULL";

			return $this->getName(). " ". $type." ".$this->getSize2()." ".$key." ".$isnull.",";
		}

		/**
		 * Retranscription du type de l'attribut pour génération automatique HTML
		 *
		 * @return String 
		 */
		public function getTypeToHTML(){
			if(!empty($this->getClasseLiee())&& $this->getKey()!="PRIMARY KEY"){
				return "Select";
			}else{
				switch($this->getType()){
					case "Varchar";
					case "Char":
						return "Text";
						break;
					case "Date":
						return "Date";
						break;
					case "Int";
					case "Float":
						return "Number";
						break;
					default:
						return "";
						break;
				}
			}
		}
		/**
		 * Retranscription du type de l'attribut pour génération automatique HTML
		 *
		 * @return String 
		 */
		public function getTypeToPHP(){
			
			switch($this->getType()){
				case "Varchar";
				case "Char":
					return "String";
					break;
				case "Int":
					return "Int";
					break;
				case "Float":
					return "Float";
					break;
				case "Bool":
					return "Bool";
					break;
				default:
					return "";
					break;
			}
		}
	}