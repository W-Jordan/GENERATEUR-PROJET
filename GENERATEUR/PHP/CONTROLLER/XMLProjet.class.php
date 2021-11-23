<?php   
    class XMLProjet {
    
        /*****************Attributs***************** */
        private $_nom;
        private $_listeEntite=[];
        private $_listeCardinalite=[];
        private $_repertoireSource;
        private $_userDBDev;
        private $_userDBUser;

        /*****************Accesseurs***************** */
        public function getNom(){return $this->_nom;}
        public function setNom(String $nom){$this->_nom=$nom;}

        public function ajouteEntite(XMLEntite $entite){$this->_listeEntite[]=$entite;}
        public function getListeEntite(){return $this->_listeEntite;}
        public function setListeEntite(Array $liste){$this->_listeEntite=$liste;}

        public function ajouteCardinalite(XMLCardinalite $cardinalite){$this->_listeCardinalite[]=$cardinalite;}
        public function getListeCardinalite(){return $this->_listeCardinalite;}
        public function setListeCardinalite(Array $liste){$this->_listeCardinalite=$liste;}

        public function getRepertoireSource(){return $this->_repertoireSource;}
        public function setRepertoireSource(String $repertoireSource){$this->_repertoireSource=$repertoireSource;}

        public function getUserDBDev(){return $this->_userDBDev;}
        public function setUserDBDev($userDBDev){$this->_userDBDev = $userDBDev;}

        public function getUserDBUser(){return $this->_userDBUser;}
        public function setUserDBUser($userDBUser){$this->_userDBUser = $userDBUser;}
        

        /*****************Constructeur***************** */
        
        public function __construct(array $options = []){
            if (!empty($options)) {$this->hydrate($options);}
        }

        public function hydrate($data){
            foreach ($data as $key => $value){
                $methode = "set" . ucfirst($key);
                if (is_callable(([$this, $methode]))){$this->$methode($value);}
            }
        }
        
    /**********************************************************************************************************
    ****************************************  STRUCTURE  ******************************************************
    **********************************************************************************************************/
        
        /**
         * creation des repertoires du Projet et la securite (index.php)
         *
         * @param String $repertoireProjet
         * @return void
         */
        public function creationRepertoireProjet(){
            // CREATION DES DIFFERENTS DOSSIERS DU PROJET WEB
            if (file_exists($this->getRepertoireSource())) {
                self::supprimeTout($this->getRepertoireSource());
            }
            mkdir($this->getRepertoireSource(), 0777, true);
            $tabRepertoire= ["IMG","DOC","HTML","CSS","JAVASCRIPT","SQL","PHP","PHP/MODEL","PHP/MODEL/TESTMANAGER","PHP/VIEW","PHP/CONTROLLER"];
            foreach ($tabRepertoire as $key) {
                mkdir($this->getRepertoireSource().'/'.$key, 0777, true);
                // inserer securite (index.php) dans les repertoires
                    file_put_contents(
                    $this->getRepertoireSource().'/'.$key.'/index.php','<h1>ERREUR404</h1>'
                    );
            }
        }

        /**
         * permet de supprimer un répertoire (avec sous repertoire)
         *
         * @param String $path repertoire a supprimer
         * @return void
         */
        public static function supprimeTout (String $path) {
            foreach ( new DirectoryIterator($path) as $item ){
                ($item->isFile())?unlink($item->getRealPath()):"";
                (!$item->isDot() && $item->isDir())?self::supprimeTout($item->getRealPath()):"";
            }
            rmdir($path);
        }

    /**********************************************************************************************************
    ****************************************  XML  ************************************************************
    **********************************************************************************************************/
        /**
         * permet de traiter le XML pour générer les Entites
         *
         * @param [type] $xml
         * @return void
         */
        public function traiterXML($xml){
            $this->parcourirEntitesXML($xml);
            $this->recupererCardinalitesXML($xml);
            $this->parcourirCardinalites($xml);
        }
        /**
         * parcours les entites du fichier XML pour instancier des classes Entites
         *
         * @param [type] $xml
         * @return void
         */
        public function parcourirEntitesXML($xml){

            // Parcourir les Entites
            foreach ($xml->MCD->entitiesList->entite as $entite){
                $objEntite = new XMLEntite(["name"=>$entite->attributes()->name]);
                // Parcourir les Attributs de l'Entite
                $tabAttribut=[];
                foreach ($entite->attribut as $attribut){
                    foreach ($attribut->attributes() as $a=>$b) {
                        $tabAttribut[$a]=$b;
                    }
                    $objAttribut=new XMLAttribut($tabAttribut);
                    // Creation de l'objet Attribut et l'ajoute à la liste d'Attribut de l'objet Entite
                    if($objAttribut->getKey()=="PRIMARY KEY"){
                        $objAttribut->setClasseLiee($objEntite->getName());
                    }
                    $objEntite->addAttribut($objAttribut);
                }
                // Ajout de l'Entite à la liste des Entites du Projet
                $this->ajouteEntite($objEntite);
            }
        }

        /**
         * parcours les cardinalites du fichier XML pour comparaison ultérieure
         *
         * @param [type] $xml
         * @return void
         */
        public function recupererCardinalitesXML($xml){
            // Parcourir les Relations
            foreach ($xml->MCD->LinkList->link as $link) {
                // Parcourir les Attributs de link
                foreach ($link->attributes() as $a=>$b){
                    if($a=="card"||$a=="elem1"||$a=="elem2"){
                        ${$a}=strval($b);
                    }
                }
                $this->ajouteCardinalite(
                    // ! VEIRIFER VARDUMP ELEM1 2 CARD
                    new XMLCardinalite(["elem2"=>$elem2,"elem1"=>$elem1,"card"=>$card])
                );
            }
            //Trier les Cardinalites
            $tabCar = $this->getListeCardinalite();
            usort($tabCar,array('XMLCardinalite','trierParElem2'));
            
            $this->setListeCardinalite($tabCar);
        }

        /**
         * parcours les cardinalites de l'objet afin de définir des actions (exemple n.n ->création entité)
         *
         * @param [type] $xml
         * @return void
         */
        public function parcourirCardinalites($xml){
            
            $listeRelation=$this->traiterCardinalitesTernaires($xml);
           
            // Parcourir les Relations 2par2
                for ($i=0; $i < count($listeRelation) ; $i+=2) { 
                    // Premiere relation
                    $objCardinalite1 = new XMLCardinalite([
                                    "elem1"=>$listeRelation[$i]->getElem1(),
                                    "elem2"=>$listeRelation[$i]->getElem2(),
                                    "card"=>$listeRelation[$i]->getCard()]);
                    // Deuxieme relation
                    $objCardinalite2 = new XMLCardinalite([
                                    "elem1"=>$listeRelation[$i+1]->getElem1(),
                                    "elem2"=>$listeRelation[$i+1]->getElem2(),
                                    "card"=>$listeRelation[$i+1]->getCard()]);

                    // Recupere l'objet Entite en fonction de la premiere relation
                    $objEntite1 = $this->rechercheEntite($objCardinalite1->getElem1());
                    // Recupere la cardinalite(String)
                    $card1 = $objCardinalite1->getCard();
                    
                    // Recupere l'objet Entite en fonction de la deuxieme relation
                    $objEntite2 = $this->rechercheEntite($objCardinalite2->getElem1());
                    // Recupere la cardinalite(String)
                    $card2 = $objCardinalite2->getCard();
                    
                    // Verifie si une des deux Entites est NULL
                    if(is_null($objEntite1) || is_null($objEntite2)){
                        echo"Impossible de comparer la relation ".$objCardinalite1->getElem2()."\n"; 
                        die(); 
                        // ! Quitter le programme
                    }
                    // Selon les cardinalites
                    // Cas 1,1-1,1
                    if(($card1=="1,1") && ($card2=="1,1")){ 
                        echo "Erreur dans le MCD\n";
                        die(); // ! Quitter le programme
                    
                    // Cas [0,1],1 -> [0,1],1 // ! A VERIFIER
                    }elseif((substr($card1,2,1)=="1" && substr($card2,2,1)=="1")){
                        // Determiner l'Objet Entite va 'absorber' la clé etrangere
                        $objResult = XMLCardinalite::cardinaliteN1_11($objEntite1,$objCardinalite1,$objEntite2,$objCardinalite2);
                        // Remplace l'Objet Entite modifié
                        array_replace($this->getListeEntite(),array($this->rechercheIndexEntite($objResult->getName())=>$objResult));  
                
                    // Cas [0,1],n -> [0,1],n
                    }elseif(substr($card1,2,1)=="n" && substr($card2,2,1)=="n"){

                        // Creation de l'entite avec les Attributs (ajoute dans le tableau)
                        $tabCardinaliteNN=["fichierXML"=>$xml,
                                    "nomTable"=>"Gestion_".$objCardinalite1->getElem1()."_".$objCardinalite2->getElem1(),
                                    "nomPK"=>"idGestion".$objEntite1->getName().$objEntite2->getName(),
                                    "nomFK1"=>strval($objEntite1->getListeAttribut()[0]->getName()),
                                    "isnullFK1"=>$objCardinalite1->testPremiereCardinalite(),
                                    "nomFK2"=>strval($objEntite2->getListeAttribut()[0]->getName()),
                                    "isnullFK2"=>$objCardinalite2->testPremiereCardinalite(),
                                    "nomEntite1"=>$objEntite1->getName(),
                                    "nomEntite2"=>$objEntite2->getName(),
                                    "nomRelation"=>$objCardinalite1->getElem2()
                                    ];
                        // Ajoute l'Entite en retour de methode dans l'objet Projet
                        $this->ajouteEntite(XMLCardinalite::cardinaliteNN($tabCardinaliteNN));

                    //cas [0,1],n -> [0,1],1
                    }else{
                        // Determiner l'Objet Entite va 'absorber' la clé etrangere
                        $objResult = XMLCardinalite::cardinaliteN1_11($objEntite1,$objCardinalite1,$objEntite2,$objCardinalite2);
                        // Remplace l'Objet Entite modifié
                        array_replace($this->getListeEntite(),array($this->rechercheIndexEntite($objResult->getName())=>$objResult));  
                    }
                }
            
        }

        /**
         * parcours les cardinalites du fichier XML pour traitement des Ternaires
         *
         * @param [type] $xml
         * @return Array tableau des Ternaires
         */
        public function traiterCardinalitesTernaires($xml){

            // Recherche et traite les ternaires
            $listeRelation=[];
            $tabTernaire =[];
        
            foreach ($this->getListeCardinalite() as $obj){
                array_push($listeRelation,$obj->getElem2());
            }
            foreach (array_count_values($listeRelation) as $value => $count) {
                if($count>2){array_push($tabTernaire,$value);}
            }

            // Boucle sur les ternaires
            foreach ($tabTernaire as $value) {
                
                $tabLaTernaire=[];
                // Parcourir uniquement les Relations Ternaires
                for ($i=0; $i < count($this->getListeCardinalite()) ; $i++) {
                    if($this->getListeCardinalite()[$i]->getElem2()==$value){

                        // la relation
                        $objCardinalite = new XMLCardinalite([
                            "elem1"=>$this->getListeCardinalite()[$i]->getElem1(),
                            "elem2"=>$this->getListeCardinalite()[$i]->getElem2(),
                            "card"=>$this->getListeCardinalite()[$i]->getCard()]);
        
                        // Recupere l'objet Entite en fonction de la relation
                        $objEntite = $this->rechercheEntite($objCardinalite->getElem1());
                        
                        // Recupere la cardinalite(String)
                        $card = $objCardinalite->getCard();

                        // Test l'existence de la cardinalité N dans la ternaire
                        if(substr($card,2,1)=="n"){
                            array_push($tabLaTernaire,$objEntite);
                            array_push($tabLaTernaire,$card);
                        }else{
                            echo "Erreur dans le MCD\nTernaire sans cardinalité 'N' !!";
                            die();
                        }
                    }
                }
                // Traite la ternaire
                $this->ajouteEntite(XMLCardinalite::cardinaliteTernaire($xml,$value, $tabLaTernaire));
            }
            $listeRelation=[];
            // Parcourir les Relations
            for ($i=0; $i < count($this->getListeCardinalite()) ; $i++) {
                // Boucle sur les ternaires
                $bool = false;
                foreach ($tabTernaire as $value) {
                    if($this->getListeCardinalite()[$i]->getElem2()==$value){
                        $bool=true;
                    }
                }
            // Si ce n'est pas une des ternaires -> Ajoute à la liste
                if(!$bool){array_push($listeRelation,$this->getListeCardinalite()[$i]);}
            }
            return $listeRelation;
        }

        /**
         * retourne un objet Entité en fonction de son nom
         *
         * @param String $nameEntitie
         * @return XMLEntite
         */
        public function rechercheEntite(String $nameEntitie){
            foreach ($this->getListeEntite() as $entitie) {
                if($entitie->getName()==ucfirst($nameEntitie)){
                    return $entitie;
                }
            }
            return NULL;
        }

        /**
         * retourne l'index de liste des Entites en fonction de son nom
         *
         * @param String $nameEntitie
         * @return int
         */
        public function rechercheIndexEntite(String $nameEntitie){
            for($i=0;$i<count($this->getListeEntite());$i++) {
                if($this->getListeEntite()[$i]->getName()==$nameEntitie){
                    return $i;
                }
            }
            return NULL;
        }


    /**********************************************************************************************************
    ****************************************  SQL  ************************************************************
    **********************************************************************************************************/

        /**
         * genere le script de Creation des Tables du Projet
         *
         * @return String
         */
        public function scriptSQLCreateTable(){
            $codeSQL = "DROP DATABASE IF EXISTS ".$this->getNom()." ;\n";
            $codeSQL .= "CREATE DATABASE IF NOT EXISTS ".$this->getNom()." DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;\n";
            $codeSQL .= "USE ".$this->getNom().";\n";

            // ! CREER DEUX USERS
            // ! GRANT LES USERS

            $codeFK="";
            foreach ($this->getListeEntite() as $entite) {
                // Genere le script SQL
                $codeSQL.=$entite->scriptSQL()."\n";
                // Genere Alter Table FK
                $codeFK.=$entite->scriptSQLForeignKey();
            }
            return $codeSQL."\n".$codeFK;
        }

        /**
         * execute le script SQL de création de table du projet
         *
         * @return bool operation reussie ou pas
         */
        public function executerScriptCreateTable(){
            $db = DbConnect::getDb();
            try{
                $q = $db->exec($this->scriptSQLCreateTable());
            }catch(Exception $e){
                die('Erreur create Table: '. $e->getMessage());
            }
            return true;
        }

        /**
         * genere le script SQL de création des Vues du Projet
         *
         * @return String
         */
        public function scriptSQLCreateView(){
            // Boucle sur les Entites du Projet
            $codeSQL="USE ".$this->getNom().";\n";
            foreach ($this->getListeEntite() as $entite) {
                // Generer le script SQL Create Vue
                // $codeSQL.=$entite->scriptSQLCreateVue()."\n";
                
                // Si Entite créé par table associative
                if($entite->getRelationAssociatif()){
                    // Creation d'une vue de table associative
                    $codeSQL.=$entite->scriptSQLCreateVueAssociative()."\n";
                }
            }

            return $codeSQL;
        }
        

        /**
         * execute le script SQL de création de VIEW du projet
         *
         * @return bool operation reussie ou pas
         */
        public function executerScriptCreateView(){
            $db = DbConnect::getDb();
            try{
                $q = $db->exec($this->scriptSQLCreateView());
            }catch(Exception $e){
                die('Erreur create View: '. $e->getMessage());
            }
            return true;
        }

        /**
         * genere le script SQL de création des droits SQL du Projet
         *
         * @return String
         */
        public function scriptSQLGRANT(){
            // Creation des deux Users
            $codeSQL="CREATE USER ".$this->getUserDBUser()."@'localhost' IDENTIFIED BY 'password';";
            $codeSQL.="CREATE USER ".$this->getUserDBDev()."@'localhost' IDENTIFIED BY 'password';";

            $codeSQL.="GRANT SELECT, INSERT, UPDATE, DELETE ON ".$this->getNom()." .* TO ".$this->getUserDBUser()."@localhost;";
            $codeSQL.="GRANT ALL PRIVILEGES ON ".$this->getNom()." .* TO ".$this->getUserDBDev()."@localhost;";
            return $codeSQL;
        }

        /**
         * execute le script SQL de Création de droit (GRANT) du projet
         *
         * @return bool operation reussie ou pas
         */
        public function executerScriptGrant(){
            
            $db = DbConnect::getDb();
            try{
                $q = $db->exec($this->scriptSQLGRANT());
            }catch(Exception $e){
                die('Erreur Grant: '. $e->getMessage());
            }
            return true;
        }

    /**********************************************************************************************************
    ****************************************  PHP  ************************************************************
    **********************************************************************************************************/
        
        /**
         * Genere le fichier/code PHP des class
         *
         * @return void
         */
        public function genererClassesPHP(){
            // Creation des fichiers class.php
            foreach ($this->getListeEntite() as $classe) {
                // enregistre le fichier php de la classe dans le repertoire Class
                file_put_contents($this->getRepertoireSource()."/PHP/CONTROLLER/".$classe->getName().".class.php",
                                    $classe->codePHPClasse()
                                );
            }
            // enregistre le fichier PHP DBConnect
                file_put_contents($this->getRepertoireSource()."/PHP/CONTROLLER/DBConnect.class.php",
                                    $this->codeDBConnectPHP()
                                );

            // Enregistrer le fichier PHP de manager automatiquement


        }

        /**
         * genere le code PHP du DBConnect
         *
         * @return String
         */
        public function codeDBConnectPHP(){
            return
                "<?php\n".
                "\tclass DBConnect {\n".
                    "\t\t".'private static $db;'.
                    "\t\t".'public static function getDb() {return DBConnect::$db;}'."\n\n".
                    "\t\t".'public static function init() {'."\n".
                    "\t\t\ttry {\n".
                    "\t\t\t\t".'self::$db = new PDO'.
                                            "('mysql:host='.Parametre::getHost(). ';port=' . Parametre::getPort() .
                                            ';dbname=' . Parametre::getDbname() . ';charset=utf8;', Parametre::getLogin(),
                                            Parametre::getPwd());\n".
                    "\t\t\t".'} catch ( Exception $e ) {'."\n".
                    "\t\t\t\tdie ( 'Erreur : ' .".' $e->getMessage () );'."\n".
                    "\t\t\t}\n".
                    "\t\t}\n".
                "\t}";
		}

        /**
         * Genere le fichier/code PHP des Manager
         *
         * @return void
         */
        public function genererManagerPHP(){
            // Pour chaque Entite
            foreach ($this->getListeEntite() as $classe) {
                // enregistre le fichier Manager php de la classe dans le repertoire MODEL
                file_put_contents($this->getRepertoireSource()."/PHP/MODEL/".$classe->getName()."Manager.class.php", 
                                    $classe->codePHPManager()
                                );
                // file_put_contents($this->getRepertoireSource()."/PHP/MODEL/TestManager/TestManager.class.php", 
                //                     $classe->codePHPTestManager()
                //                 );
            }

        }

        /**
         * Genere le fichier/code de l'Outils.php
         *
         * @return void
         */
        public function genererOutils(){
            $codeOutils=
            '<?php'."\n". 
                "\t".'function chargerPage($tab){'."\n". 
                
                "\t\t".'$fichier = $tab[1];'."\n".
                "\t\t".'$titre = $tab[\'titre\'];'."\n". 
                "\t\t".'$logger = $tab[\'logger\'];'."\n".
                "\t\t".'$roles = $tab[\'roles\'];'."\n".

                "\t\t".'include \'PHP/VIEW/header.php\';'."\n".
                
                "\t\t".'if (($logger && !isset($_SESSION[\'utilisateur\'])) || (isset($_SESSION[\'utilisateur\']) && !in_array($_SESSION[\'utilisateur\']->getRole(), $roles))){'."\n".
                    "\t\t\t".'$fichier = "Accueil";'."\n".
                "\t\t".'}'."\n".
                "\t\t".'include \'PHP/VIEW/\'.$fichier . \'.php\';'."\n".
                "\t\t".'include "PHP/VIEW/footer.php";'."\n". 
                "\t".'}'."\n\n". 
                
                "\t".'function crypter($mdp){return md5(md5($mdp).strlen($mdp));}'."\n\n". 
    
                "\t".'function ChargerClasse($classe){'."\n". 
                "\t\t".'if (file_exists("PHP/CONTROLLER/" . $classe . ".class.php")){'."\n". 
                "\t\t\t".'require "PHP/CONTROLLER/" . $classe . ".class.php";'."\n". 
                "\t\t".'}'."\n". 
                "\t\t".'if (file_exists("PHP/MODEL/" . $classe . ".class.php")){'."\n". 
                "\t\t\t".'require "PHP/MODEL/" . $classe . ".class.php";'."\n". 
                "\t\t".'}'."\n". 
                "\t".'}'."\n\n". 
    
                "\t".'/*function texte($codetexte){'."\n". 
                "\t\t".'global $lang;'."\n". 
                "\t\t".'return TexteManager::findByCodes($lang, $codetexte);'."\n". 
                "\t".'}*/'."\n";
            
            // Creer le fichier Outils.php
            file_put_contents($this->getRepertoireSource()."/PHP/Outils.php",$codeOutils);
        }

        /**
         * Genere le fichier/code de Parametre.class.php et Parametre.ini
         *
         * @return void
         */
        public function genererParametre(){

            $codeParametre=
            '<?php'."\n". 
            "\t".'class Parametre{'."\n". 
            "\t\t".'private static $host;'."\n". 
            "\t\t".'private static $port;'."\n". 
            "\t\t".'private static $dbname;'."\n". 
            "\t\t".'private static $login;'."\n". 
            "\t\t".'private static $pwd;'."\n\n". 

            "\t\t".'public static function getHost(){return self::$host;}'."\n". 
            "\t\t".'public static function getPort(){return self::$port;}'."\n". 
            "\t\t".'public static function getDbname(){return self::$dbname;}'."\n". 
            "\t\t".'public static function getLogin(){return self::$login;}'."\n". 
            "\t\t".'public static function getPwd(){return self::$pwd;}'."\n\n". 

            "\t\t".'public static function init(){'."\n". 
            "\t\t\t".'// Ouvrir le JSON'."\n".
			"\t\t\t".'if (file_exists("Parametre.json")){'."\n".
            "\t\t\t\t".'$fichierJSON = file_get_contents("Parametre.json");'."\n".
			"\t\t\t\t".'$fichierJSON = json_decode($fichierJSON, true);'."\n".

			"\t\t\t\t".'self::$host = $fichierJSON["Host"];'."\n".
			"\t\t\t\t".'self::$port = $fichierJSON["Port"];'."\n".
			"\t\t\t\t".'self::$dbname = $fichierJSON["DBName"];'."\n".
			"\t\t\t\t".'self::$login = $fichierJSON["Login"];'."\n".
			"\t\t\t\t".'self::$pwd = $fichierJSON["Pwd"];'."\n".
			"\t\t\t".'}else{'."\n".
            "\t\t\t\t".'var_dump("Le fichier Parametre est introuvable !!");'."\n".
            "\t\t\t".'}'."\n".
            "\t\t".'}'."\n".
            "\t".'}'."\n";

            // Creer le fichier parametre.php
            file_put_contents(
                $this->getRepertoireSource()."/PHP/CONTROLLER/Parametre.class.php",
                $codeParametre
            );

            // Creer le fichier parametre.json
            $codeJSON =
            "{"."\n".
            "\t\t".'"Host":"localhost",'."\n".
            "\t\t".'"Port":"8888",'."\n".
            "\t\t".'"DBName":"'.$this->getNom().'",'."\n".
            "\t\t".'"Login":"root",'."\n".
            "\t\t".'"Pwd":"root"'."\n".
            "}";
            file_put_contents(
                $this->getRepertoireSource()."/Parametre.json",
                $codeJSON
            );
        }

        /**
         * Genere le fichier index.php Principal
         *
         * @return void
         */
        public function genereIndexPHP(){
            $codeIndex=
            "\t".'<?php'."\n". 
            "\t\t".'include \'./PHP/Outils.php\';'."\n". 
            "\t\t".'spl_autoload_register(\'ChargerClasse\');'."\n\n". 
            "\t\t".'session_start();'."\n\n". 
            "\t\t".'// on recupere la langue de l\'URL;'.
            "\t\t".'if (isset($_GET[\'lang\'])){ $_SESSION[\'lang\'] = $_GET[\'lang\'];}'."\n". 
            "\t\t".'$lang = isset($_SESSION[\'lang\']) ? $_SESSION[\'lang\'] : \'FR\';'."\n\n". 

            "\t\t".'Parametre::init();'."\n". 
            "\t\t".'$db = DBConnect::init();'."\n\n". 

            "\t\t".'$action=(isset($_GET[\'action\']))?$_GET[\'action\']:"";'."\n\n". 
            "\t\t".'$routes=['."\n".
                    "\t\t\t".'\'default\'=>[\'page\'=>\'Accueil\',\'logger\'=>false,\'roles\'=>[0]],'."\n". 
                    "\t\t\t".'\'404\'=>[\'page\'=>\'404\',\'logger\'=>false,\'roles\'=>[0]],'."\n". 

                    "\t\t\t".'\'liste\'=>[\'page\'=>\'listeJoueurs\',\'logger\'=>false,\'roles\'=>[0]],'."\n". 
                    
                    "\t\t\t".'\'formConnexion\'=>[\'page\'=>\'formConnexion\',\'logger\'=>false,\'roles\'=>[0]],'."\n". 
                    "\t\t\t".'\'form\'=>[\'page\'=>\'form\',\'logger\'=>false,\'roles\'=>[0]],'."\n". 

                    "\t\t\t".'\'action\'=>[\'page\'=>\'action\',\'action\'=>$action,\'table\'=>$table,\'logger\'=>false,\'roles\'=>[0]],'."\n". 
                    "\t\t\t".'\'actionConnexion\'=>[\'page\'=>\'actionConnexion\',\'action\'=>$action,\'logger\'=>false,\'roles\'=>[0]],'."\n". 
                    "\t\t\t".'\'actionDeconnexion\'=>[\'page\'=>\'actionDeconnexion\',\'action\'=>$action,\'logger\'=>false,\'roles\'=>[0]],'."\n". 
            "\t\t".'];'."\n". 

            "\t\t".'if (isset($_GET[\'page\'])){'."\n". 
                "\t\t\t".'if(isset($routes[$_GET[\'page\']])){'."\n". 
                    "\t\t\t\t".'chargerPage($routes[$_GET[\'page\']]);'."\n". 
                "\t\t\t".'}else{'."\n". 
                    "\t\t\t\t".'chargerPage($routes[\'404\']);'."\n". 
                "\t\t\t".'}'."\n". 
            "\t\t".'}else{'."\n". 
                "\t\t\t\t".'chargerPage($routes[\'default\']);'."\n". 
            "\t\t".'}';

            // Creer le fichier index.php
            file_put_contents($this->getRepertoireSource()."/index.php",$codeIndex);
        }

        /**
         * Genere le fichier 404.php
         *
         * @return void
         */
        public function genere404PHP(){
            
            // Creer le fichier index.php
            file_put_contents(
                        $this->getRepertoireSource()."/PHP/VIEW/404.php",
                        "<?php\n\techo'<h1>ERREUR 404</h1>';"
            );

        }

        /**
         * Genere le fichier action.php
         *
         * @return void
         */
        public function genereActionPHP(){
            $codeAction=
            '<?php'."\n".
            "\t".'$table = $_GET[\'table\'];'."\n".
            "\t".'$action = $_GET[\'action\'];'."\n\n".
            "\t".'$obj = new $table($_POST);'."\n\n".
            "\t".'$table .="Manager";'."\n".
            "\t".'if(is_callable($table::$action($obj))){$table::$action($obj);}'."\n\n".
            "\t".'header("location:index.php");';

            // Creer le fichier action.php
            file_put_contents($this->getRepertoireSource()."/PHP/VIEW/action.php",$codeAction);
        }

       
        /**
         * Genere le fichier Gestionnaire.php
         *
         * @return void
         */
        // ! PREVOIR L'HERITAGE DE GESTIONNAIRE DANS TOUTES LES CLASS PHP
        public function GestionnairePHP(){
            $codeGestionnaire=
            '<?php';
           
            // Creer le fichier action.php
            file_put_contents(
                $this->getRepertoireSource()."/PHP/MODEL/Gestionnaire.php",
                $codeGestionnaire
            );
        }

    }