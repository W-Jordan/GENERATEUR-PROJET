<?php
    class XMLEntite{

    /*****************Attributs***************** */
    private $_name;
    private $_listeAttribut;
    private $_relationAssociatif=false;

    /*****************Accesseurs***************** */

    public function getName(){return $this->_name;}
    public function setName(String $name){$this->_name = ucfirst($name);}

    public function getListeAttribut(){return $this->_listeAttribut;}
    public function setListeAttribut($listeAttribut){$this->_listeAttribut = $listeAttribut;}
    public function addAttribut(XMLAttribut $attribut){$this->_listeAttribut[]=$attribut;} 

    public function getRelationAssociatif(){return $this->_relationAssociatif;}
    public function setRelationAssociatif(Bool $bool){$this->_relationAssociatif = $bool;}

    /*****************Constructeur***************** */

    public function __construct(array $options = []){if(!empty($options)) {$this->hydrate($options);}}

    public function hydrate($data){
        foreach ($data as $key => $value){
            $methode = "set" . ucfirst($key);
            if (is_callable(([$this, $methode]))){$this->$methode($value);}
        }
    }

    /*****************Autres Méthodes***************** */

    /**
     * retourne un tableau des NOMS des attributs (utile pour les managers)
     *
     * @param String $caractereAvant permet de parametrer le tableau des noms attrributs (utile pour le Manager)
     * @param bool permet de determiner s'il faut afficher la PK
     * @return array tableau des noms d'attributs formatés avec le parametre d'entrée
     */
    public function getTableauAttributNom(String $caractereAvant,bool $boolAfficherPK){
        $listeNomAttribut=[];
        foreach ($this->getListeAttribut() as $attribut) {
            $listeNomAttribut[]=$caractereAvant.$attribut->getName();
            if(!$boolAfficherPK && $attribut->getKey()=="PRIMARY KEY"){
                array_splice($listeNomAttribut, -1);
            }
        }
        return $listeNomAttribut;
    }

/**********************************************************************************************************
****************************************  SQL  ************************************************************
**********************************************************************************************************/

    /**
     * permet de generer automatiquement le code SQL de Creation de la table
     *
     * @return String
     */
    public function scriptSQL(){
        $codeSQL="\n#----------------------------------------------------------------".
                    "\n# table ".$this->getName().
                    "\n#----------------------------------------------------------------\n\n";
        $codeSQL.="CREATE TABLE ".$this->getName()."(";
        // Chaque attribut incrémente le script SQL
        foreach ($this->getListeAttribut() as $attribut){
                $codeSQL.="\n\t".$attribut->scriptSQL();
            }
            $codeSQL=substr($codeSQL, 0, -1); // Enleve la derniere virgule du code SQL
        return $codeSQL."\n)ENGINE=InnoDB CHARSET = UTF8 COLLATE utf8_general_ci;";
    }

    /**
     * permet de generer automatiquement le code SQL de déclaration des clés etrangeres
     *
     * @return String
     */
    public function scriptSQLForeignKey(){
        $codeSQL="";
        foreach ($this->getListeAttribut() as $attribut){
            if($attribut->getKey()=="FOREIGN KEY"){
                 $codeSQL.="ALTER TABLE ".$this->getName()." ADD CONSTRAINT FK_".$this->getName()."_".$attribut->getClasseLiee()." FOREIGN KEY (".$attribut->getName().") REFERENCES ".$attribut->getClasseLiee()."(".$attribut->getName().");\n"; 
            }
        }
        return $codeSQL;
    }

    /**
     * permet de generer automatiquement le code de la Vue SQL
     *
     * @return String
     */
    public function scriptSQLCreateVue(){
        $codeSQL = "DROP VIEW IF EXISTS v_".$this->getName().";\nCREATE VIEW v_".$this->getName() ." AS (\nSELECT ";
        foreach ($this->getListeAttribut() as $attribut) {
            $codeSQL.=$this->getName().".".$attribut->getName().",";
        }
        // Enleve la derniere virgule et rajoute le FROM [table]
        return substr($codeSQL,0,-1)."\nFROM ".$this->getName().");";
    }

    /**
     * permet de generer automatiquement le code de la Vue SQL pour la Table Associative
     *
     * @param XMLProjet $projet
     * @return String
     */
    public function scriptSQLCreateVueAssociative(){
        
        $tabEntiteFK=[];
        // Boucle sur les Attributs afin de determiner les FK et leurs Entites(tables)
        // Tableau associatif avec nomId => nom de l'Entite
        foreach ($this->getListeAttribut() as $attribut) {
            if($attribut->getKey()=="FOREIGN KEY"){
                $tabEntiteFK[strval($attribut->getName())]=strval($attribut->getClasseLiee());
            }
        }

        // affiche les champs sans les FK
        $codeSQLSELECT="\nSELECT ".$this->getListeAttributAffichageSelect(true);
        $codeSQLFROM="\nFROM ".$this->getName();
        $codeSQLJointure="";
        // Boucle sur les Entites FK
            // Rajoute les attributs des tables associées sans FK
            // Rajoute les noms des tables au FROM
            // Effectue la jointure entre le/les tables
        $i=0;
        foreach ($tabEntiteFK as $foreignKey => $entite) {
            $codeSQLFROM.=",".$entite;
            $motCle = ($i==0)?"\nWHERE ":"\nAND ";
            $codeSQLJointure.=$motCle." ".$this->getName().".".$foreignKey."=".$entite.
                                ".".$foreignKey;
            $i++;
        }
        return "DROP VIEW IF EXISTS v_".$this->getName().
                ";\nCREATE VIEW v_".$this->getName()." AS ( ".
                    $codeSQLSELECT.
                    $codeSQLFROM.
                    $codeSQLJointure.
                ");";
    }

    /**
     * permet de sortir les champs de la table en vue d'un SELECT
     *
     * @param boolean $boolInclureFK (afficher la FK ou pas)
     * @return String
     */
    public function getListeAttributAffichageSelect(bool $boolInclureFK){
        $listeAttribut=[];
        foreach ($this->getListeAttribut() as $attribut){
            // Si l'attribut est une FK et qu'il faut l'inclure
            if($attribut->getKey()=="FOREIGN KEY"){
                if($boolInclureFK){
                    $listeAttribut[]=$this->getName().".".$attribut->getName();
                }
            }else{
                $listeAttribut[]=$this->getName().".".$attribut->getName();
            }
        }
        return implode(",",$listeAttribut);
    }

/**********************************************************************************************************
****************************************  PHP  ************************************************************
**********************************************************************************************************/

    /**
     * permet de generer automatiquement le code de la table(entite)
     *
     * @return String
     */
    public function codePHPClasse(){
        $code="<?php\n";
        $code.= "\n\t class ".$this->getName()." {\n";
        // $code.= "\n\t class ".$this->getName()." extends Gestionnaire {\n";
        
        // code pour ajouter les tableaux libelles...
        $listeNomAttribut=[];
        $listeTypeAttribut=[];
        $listeRequiredAttribut=[];
        $listeClassAttribut=[];
        $listeLabelAttribut=[];

        foreach ($this->getListeAttribut() as $attribut) {
            // Nom de l'attribut
            $listeNomAttribut[]='"'.$attribut->getName().'"';
            // Type pour HTML
            $listeTypeAttribut[]='"'.$attribut->getTypeToHTML().'"';
            // REQUIRED si possibilite de null
            $listeRequiredAttribut[]=($attribut->getIsnull())?"\"REQUIRED\"":"";
            // Classe associé à l'attribut
            $listeClassAttribut[]='"'.$attribut->getClasseLiee().'"';
            // Libellé associé à l'attribut
            $listeLabelAttribut[]='"'.$attribut->getComment().'"';
        }
        
        $code.="\n\t\t".'private static $listeNomAttribut = ["'.$this->getName().'",'.implode(",",$listeNomAttribut).'];';
        $code.="\n\t\t".'private static $listeTypeAttribut = ["",'.implode(",",$listeTypeAttribut).'];';
        $code.="\n\t\t".'private static $listeRequiredAttribut = ["",'.implode(",",$listeRequiredAttribut).'];';
        $code.="\n\t\t".'private static $listeClassAttribut = ["",'.implode(",",$listeClassAttribut).'];';
        $code.="\n\t\t".'private static $listeLabelAttribut = ["",'.implode(",",$listeLabelAttribut).'];';
        $code.="\n\t\t".'private static $nbColonne= 5;'."\n"; //par defaut

        $code.=$this->codePHPClasseAttribut();
        $code.=$this->codePHPClasseGetSet();
        $code.=$this->codePHPClasseConstructHydrate();
        $code.=$this->codePHPClasseAutreMethode();
        return $code;
    }

    /**
     * permet de generer automatiquement le code PHP de la déclaration des attributs
     *
     * @return String
     */
    private function codePHPClasseAttribut(){
        // Inserer declaration attribut 
        $msg="\n\t\t/*****************Attributs***************** */\n";
        foreach($this->getListeAttribut() as $attribut){
            $msg.="\n\t\tPrivate ".'$_'.$attribut->getName().";";
        }
        return $msg;
    }

    /**
     * permet de generer automatiquement le code PHP des méthodes Get/Set
     *
     * @return String
     */
    private function  codePHPClasseGetSet(){
        $msg="\n\n\t\t/*****************Accesseurs***************** */\n";
        // Inserer getter Setter Attribut
        foreach($this->getListeAttribut() as $attribut){
            $nomAttribut=strval($attribut->getName());
            // Getter
            $msg.="\n\t\tPublic function get".ucfirst($nomAttribut).'(){ return $this->_'.$nomAttribut.";}\n";
             // Setter
            $msg.="\t\tPublic function set".ucfirst($nomAttribut).
                    "(".$attribut->getTypeToPHP()." $".$nomAttribut.'){ $this->_'.$nomAttribut."=$$nomAttribut;}\n";
        }
        return $msg;
    }

    /**
     * permet de generer automatiquement le code PHP du construct et Hydrate
     *
     * @return String
     */
    private function codePHPClasseConstructHydrate(){
        //Fonction construct/hydrate
        return"\n\t\t/*****************Constructeur***************** */\n".
                "\n\t\t".'Public function __construct(array $options = []){ (!empty($options)) ? $this->hydrate($options) :""; }'."\n".
                "\n\t\t".'public function hydrate($data){'.
                "\n\t\t\t".'foreach ($data as $key => $value){'.
                "\n\t\t\t\t".'$methode = "set" . ucfirst($key);'.
                "\n\t\t\t\t".'(is_callable([$this, $methode])) ? $this->$methode($value) : "" ;'.
                "\n\t\t\t}".
                "\n\t\t}\n";

    }

    /**
     * permet de generer automatiquement le code PHP des autres methodes (equalsto, compareTo...)
     *
     * @return String
     */
    private function codePHPClasseAutreMethode(){
        // return "\n\t\t/*****************Autres Méthodes***************** */".
        //         "\n\t\t".'public function toString(){ return ;'."}\n".
        //         "\n\t\t".'/**'.
        //         "\n\t\t".'* Renvoi vrai si l\'objet en paramètre est égal à l\'objet appelant'.
        //         "\n\t\t".'*'.
        //         "\n\t\t".'* @param [type] $obj'.
        //         "\n\t\t* @return bool".
        //         "\n\t\t*/\n".
        //         "\n\t\t".'public function equalsTo($obj){'.
        //         "\n\t\t\treturn true;".
        //         "\n\t\t}\n".
        //         "\n\t\t".'/**'.
        //         "\n\t\t".'* Compare 2 objets'.
        //         "\n\t\t".'* Renvoi 1 si le 1er est >'.
        //         "\n\t\t".'*        0 si ils sont égaux'.
        //         "\n\t\t".'*        -1 si le 1er est <'.
        //         "\n\t\t".'*'.
        //         "\n\t\t".'* @param [type] $$obj1'.
        //         "\n\t\t".'* @param [type] $$obj2'.
        //         "\n\t\t".'* @return int'.
        //         "\n\t\t*/".
        //         "\n\t\t".'public static function compareTo($obj1, $obj2){'.
        //         "\n\t\t\treturn 0;".
        //         "\n\t\t}\n".
                return "\n\t\t\n\t}";
    }

    /**
     * appel des fonctions pour generer le code du Manager
     *
     * @return String code PHP du Manager
     */
    public function codePHPManager(){
        $code='<?php'."\n\n\t\tclass ".$this->getName()."Manager {\n";

        $code.=$this->codePHPManagerAdd();
        $code.=$this->codePHPManagerUpdate();
        $code.=$this->codePHPManagerDelete();
        $code.=$this->codePHPManagerFindById();
        return $code.$this->codePHPManagerGetList()."\t}";
    }

    /**
     * genere le code PHP du Manager (Methode Add)
     *
     * @return String code PHP
     */
    private function codePHPManagerAdd(){
        // ne pas inclure la PK si elle est auto-increment
        $code= "\t\t".'public static function add('.$this->getName().' $obj)'."{\n".
            "\t\t\t".'$db=DbConnect::getDb();'."\n".
            "\t\t\t".'$q=$db->prepare("INSERT INTO '.$this->getName().' ('
                                    .implode(',',$this->getTableauAttributNom('',false)).') VALUES ('
                                    .implode(',',$this->getTableauAttributNom(':',false)).')");'."\n";
        // Ne pas afficher la PK
        $i=0;
        foreach ($this->getListeAttribut() as $attribut){
            if($i!=0){
                $code.="\t\t\t".'$q->bindValue(":'.$attribut->getName().'", $obj->get'.ucfirst($attribut->getName()).'());'."\n";
            }
            $i++;
        }
        return $code.="\t\t\t".'$q->execute();'."\n".
                "\t\t}\n\n";
    }
    
    /**
     * genere le code PHP du Manager (Methode Update)
     *
     * @return String code PHP
     */
    private function codePHPManagerUpdate(){                
        $code= "\t\t".'public static function update('.$this->getName().' $obj)'."{\n ".
                "\t\t\t".'$db=DbConnect::getDb();'."\n".
                "\t\t\t".'$q=$db->prepare("UPDATE '.$this->getName().' SET ';
                $set="";
                $bindValue="";
                
                foreach ($this->getListeAttribut() as $attribut) {
                    // generer les lignes du SET de l'UPDATE
                    if($attribut->getKey()!="PRIMARY KEY"){
                        $set.=$attribut->getName().'=:'.$attribut->getName().',';
                    }
                    // Genere les binValue
                    $bindValue.="\t\t\t".'$q->bindValue(":'.$attribut->getName().'", $obj->get'.ucfirst($attribut->getName()).'());'."\n";

                }
                //enleve la derniere virgule pour ajouter un ;
                $set= substr($set, 0, -1).';';
        return $code.$set.' WHERE '.$this->getListeAttribut()[0]->getName().'=:'.$this->getListeAttribut()[0]->getName().'");'."\n".
                $bindValue."\t\t\t".'$q->execute();'."\n".
                "\t\t}\n";
    }
    
    /**
     * genere le code PHP du Manager (Methode Delete)
     *
     * @return String code PHP
     */
    private function codePHPManagerDelete(){                
        return "\t\t".'public static function delete('.$this->getName().' $obj)'."{\n ".
            "\t\t\t".'$db=DbConnect::getDb();'."\n".
            "\t\t\t".'$db->exec(
                "DELETE FROM '.$this->getName().
                ' WHERE '.$this->getListeAttribut()[0]->getName().'=" .$obj->get'.ucfirst($this->getListeAttribut()[0]->getName()).'());'."\n".
            "\t\t}\n";
    }

    /**
     * genere le code PHP du Manager (Methode FindById)
     *
     * @return String code PHP
     */
    private function codePHPManagerFindById(){                
        return "\t\t".'public static function findById($id)'."{\n ".
                "\t\t\t".'$db=DbConnect::getDb();'."\n".
                "\t\t\t".'$id = (int) $id;'."\n".
                "\t\t\t".'$q=$db->query("SELECT * FROM '.$this->getName().' WHERE '.$this->getListeAttribut()[0]->getName().' =".$id);'."\n".
                "\t\t\t".'$results = $q->fetch(PDO::FETCH_ASSOC);'."\n".
                "\t\t\t".'if($results != false)'."{\n".
                "\t\t\t\t".'return new '.$this->getName().'($results);'."\n".
                "\t\t\t"."}else{\n".
                "\t\t\t\t".'return false;'."\n".
                "\t\t\t".'}'."\n".
                "\t\t".'}'."\n";
    }

    /**
     * genere le code PHP du Manager (Methode GetList)
     *
     * @return String code PHP
     */
    private function codePHPManagerGetList(){                
        return "\t\t".'public static function getList()'."{\n ".
                "\t\t\t".'$db=DbConnect::getDb();'."\n".
                "\t\t\t".'$liste = [];'."\n".
                "\t\t\t".'$q = $db->query("SELECT * FROM '.$this->getName().'");'."\n".
                "\t\t\t".'while($donnees = $q->fetch(PDO::FETCH_ASSOC)){'."\n".
                "\t\t\t\t".'if($donnees != false){'."\n".
                "\t\t\t\t\t".'$liste[] = new '.$this->getName().'($donnees);'."\n".
                "\t\t\t\t".'}'."\n".
                "\t\t\t".'}'."\n".
                "\t\t\t".'return $liste;'."\n".
                "\t\t".'}'."\n";
    }

    /**
     * genere le code PHP du TestManager
     *
     * @return String code PHP
     */
    public function codePHPTestManager(){                
        $code= '<?php'."\n\n".
        '//Test '.$this->getName().'Manager'."\n\n".
        'echo "recherche id = 1" . "<br>";'."\n".
        '$obj ='.$this->getName().'Manager::findById(1);'."\n".
        'var_dump($obj);'."\n".
        'echo $obj->toString();'."\n\n".

        'echo "ajout de l\'objet". "<br>";'."\n".
        '$newObj = new '.$this->getName().'([';
        for($i=1;$i<count($this->getListeAttribut());$i++){
            $code.='"'.$this->getListeAttribut()[$i]->getName().'" => "([value '.$i.'])",';
        }
        // enleve la derniere virgule pour ajouter un ;
        $code= substr($code, 0, -1).']);';

        return $code.'var_dump('.$this->getName().'Manager::add($newObj));'."\n\n".
        
        'echo "Liste des objets" . "<br>";'."\n". 
        '$array = '.$this->getName().'Manager::getList();'."\n".
        'foreach ($array as $unObj)'."\n".
        '{'."\n".
        "\t".'echo $unObj->toString() . "<br><br>";'."\n".
        '}'."\n\n".

        'echo "on met à jour l\'id 1" . "<br>";'."\n". 
        '$obj->set'.$this->getListeAttribut()[1]->getName().'("[(Value)]");'."\n". 
        $this->getName().'Manager::update($obj);'."\n".
        '$objUpdated = '.$this->getName().'Manager::findById(1);'."\n".

        'echo "Liste des objets" . "<br>";'."\n". 
        '$array = '.$this->getName().'Manager::getList();'."\n".
        'foreach ($array as $unObj)'."\n".
        '{'."\n".
        "\t".'echo $unObj->toString() . "<br><br>";'."\n".
        '}'."\n\n".

        'echo "on supprime un objet". "<br>";'."\n".
        '$obj = '.$this->getName().'Manager::findById(1);'."\n".
        $this->getName().'Manager::delete($obj);'."\n".
        'echo "Liste des objets" . "<br>";'."\n". 
        '$array = '.$this->getName().'Manager::getList();'."\n".
        'foreach ($array as $unObj)'."\n".
        '{'."\n".
        "\t".'echo $unObj->toString() . "<br><br>";'."\n".
        '}'."\n\n".
        '?>';
    }

}