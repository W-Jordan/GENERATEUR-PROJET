<?php

	 class XMLCardinalite {

		/*****************Attributs***************** */

		Private $_elem1;
		Private $_elem2;
		Private $_card;

		/*****************Accesseurs***************** */

		Public function getElem1(){ return $this->_elem1;}
		Public function setElem1($elem1){ $this->_elem1=$elem1;}

        Public function getElem2(){ return $this->_elem2;}
		Public function setElem2($elem2){ $this->_elem2=$elem2;}

        Public function getCard(){ return $this->_card;}
		Public function setCard($card){ $this->_card=$card;}

		
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
		 * trie deux Objets Cardinalites en fonction de leurs cardinalites(String)
		 *
		 * @param XMLCardinalite $obj1
		 * @param XMLCardinalite $obj2
		 * @return int
		 */
    	public static function trierParElem2(XMLCardinalite $obj1,XMLCardinalite $obj2){
        	if ($obj1->getElem2() === $obj2->getElem2()) {
                return 0;
            }elseif ($obj1->getElem2() < $obj2->getElem2()) {
                return -1;
            }else{
                return 1;
            }
        }

		/**
		 * teste le premier caractere de cardinalites pour déterminer le isnull dans SQL
		 *
		 * @return String
		 */
		public function testPremiereCardinalite(){
			switch($this->getCard()[0]){
				case "0":
					return "true";
				case "1":
					return "false";
				default:
					break;
			}
		}

		/**
		 * determine les points de cardinalites afin de les comparer 
		 * 		(pour determiner qui prend le dessus sur l'autre)
		 *
		 * @return int
		 */
		public function pointCardinalite(){
			switch($this->getCard()){
				case"1,1":return 4;
				case"0,1":return 3;
				case"1,n":return 2;
				case"0,n":return 1;
				default:return 0;
			}
		}

		/**
		 * genere l'entite issue de la relation (Table Associative)
		 *
		 * @param array $tab
		 * @return XMLEntite
		 */
		public static function cardinaliteNN(array $tab){

			// Creation de l'entite (table Associative)
			$objEntite = new XMLEntite(["name"=>$tab['nomTable'],"relationAssociatif"=>true]);

			// Clé primaire
			$objEntite->addAttribut(new XMLAttribut(["name"=>$tab['nomPK'],"type"=>"Auto_increment","key"=>"PRIMARY KEY",
												"isnull"=>"false","classeLiee"=>$objEntite->getName()]));

			// ATTRIBUT D'UNE RELATION
			foreach ($tab['fichierXML']->MCD->entitiesList->relation as $relation){
				
				// recherche la relation dans le fichier XML
				if(strval($relation->attributes()->name)==strval($tab['nomRelation'])){
					
					// Pour chaque Attribut de la relation
					// Prépare un tableau associatif pour le construct de l'objet Attribut
					$tabAttribut=[];
					foreach ($relation->attribut as $attribut){
						foreach ($attribut->attributes() as $a=>$b) {
							$tabAttribut[$a]=$b;
						}
						// Creation de l'objet Attribut et l'ajoute à l'objet Entite
						$objEntite->addAttribut(new XMLAttribut($tabAttribut));
					}
				}
			}
			
			$objEntite->addAttribut(new XMLAttribut(["name"=>$tab['nomFK1'],"type"=>"Int","key"=>"FOREIGN KEY","isnull"=>$tab['isnullFK1'],"classeLiee"=>$tab['nomEntite1']]));
			$objEntite->addAttribut(new XMLAttribut(["name"=>$tab['nomFK2'],"type"=>"Int","key"=>"FOREIGN KEY","isnull"=>$tab['isnullFK2'],"classeLiee"=>$tab['nomEntite2']]));
			return $objEntite;
		}

		/**
		 * genere l'entite issue de la relation TERNAIRE
		 *
		 * @param array $tab
		 * @return XMLEntite
		 */
		public static function cardinaliteTernaire($xml,String $nomRelation ,array $tabTernaire){

			// Creation de l'entite (table Associative)
			$objEntite = new XMLEntite(["name"=>$nomRelation,"relationAssociatif"=>true]);

			// Clé primaire
			$objEntite->addAttribut(new XMLAttribut(["name"=>"id".ucfirst($nomRelation),"type"=>"Auto_increment","key"=>"PRIMARY KEY",
												"isnull"=>"false","classeLiee"=>ucfirst($nomRelation)]));

			// Boucle sur les Ternaires
			for ($i=0; $i < count($tabTernaire) ; $i+=2) {
				foreach ($tabTernaire[$i]->getListeAttribut() as $attribut) {
				
					if($attribut->getKey()=="PRIMARY KEY"){
						// SI 0,n alors NUL sinon NOT NULL
						if(substr($tabTernaire[$i+1],1,1)=="0"){
							$notNull = "true";
						}else{
							$notNull = "false";
						}
						// Ajout des Clés Etrangeres
						$objEntite->addAttribut(new XMLAttribut(["name"=>$attribut->getName(),"type"=>"INT","key"=>"FOREIGN KEY",
												"isnull"=>$notNull,"classeLiee"=>ucfirst($tabTernaire[$i]->getName())]));
					}
				}
			}
			
			// ATTRIBUTS D'UNE RELATION
			foreach ($xml->MCD->entitiesList->relation as $relation){
				// recherche la relation dans le fichier XML
				if(strval($relation->attributes()->name)==$nomRelation){
					
					// Pour chaque Attribut de la relation
					// Prépare un tableau associatif pour le construct de l'objet Attribut
					$tabAttribut=[];
					foreach ($relation->attribut as $attribut){
						foreach ($attribut->attributes() as $a=>$b) {
							$tabAttribut[$a]=$b;
						}
						// Creation de l'objet Attribut et l'ajoute à l'objet Entite
						$objEntite->addAttribut(new XMLAttribut($tabAttribut));
					}
				}
			}
			return $objEntite;
		}

		/**
		 * Determine qui de la premiere/deuxieme Entite va recuperer la clé primaire de l'autre
		 * Ajoute la clé Etrangere à l'objet Entite 
		 *
		 * @param XMLEntite $entite1
		 * @param XMLCardinalite $card1
		 * @param XMLEntite $entite2
		 * @param XMLCardinalite $card2
		 * @return XMLEntite
		 */
		public static function cardinaliteN1_11(XMLEntite $entite1,XMLCardinalite $card1,XMLEntite $entite2,XMLCardinalite $card2){
			// Compare les points donnés aux cardinalites (permet la gestion des foreignKey)
			if($card1->pointCardinalite()>$card2->pointCardinalite()){
				// Parcours les Entites
				$objEntitieRecupere = $entite1;
				$objEntitieDonne = $entite2;
				$cardRecupere = $card1;
			}else{
				$objEntitieRecupere = $entite2;
				$objEntitieDonne = $entite1;
				$cardRecupere = $card2;
			}
			$objEntitieRecupere->addAttribut(new XMLAttribut([
				"name"=> $objEntitieDonne->getListeAttribut()[0]->getName(),
				"type"=>"Int",
				"key"=>"FOREIGN KEY",
				"isnull"=>$cardRecupere->testPremiereCardinalite(),
				"FK"=>$objEntitieDonne->getName(),
				"classeLiee"=>$objEntitieDonne->getName()
				]));
			return $objEntitieRecupere;
		}

	 }