<?php

    class Gestionnaire{

        /**
         * GetListByFiltre
         *
         * @param [type] $table
         * @param [type] $lesColonnes (liste de colonne OU *)
         * @param [type] $filtre
         * @param [type] $api
         * @return void
         */
        
        
         public static function getListByFiltre($table,$lesColonnes,$filtre,$api){
            $laCondition="";
            $operateur="";
            $i=0;

            // Boucle sur les filtres
            foreach ($filtre as $key => $value) {
                $operateur=($i==0)?" WHERE ":" AND ";
                $laCondition.=$operateur.$key;

                // Si c'est un array 
                if(is_array($value)){
                    // Boucle sur le tableau pour affichage dans le IN
                    $in="";
                    foreach ($value as $key ) {
                        $in.="'".$key."',";
                    }
                    // for ($j=0; $j <count($value) ; $j++) { 
                    //     $in.="'".$value[$j]."',";
                    // }
                    $laCondition.=" IN (".substr($in,0,-1).") ";
                // Valeur toute simple
                }else{
                    $laCondition.="='".$value."'";
                }

                $i++;
            }
            
            // Transformation du tableau de colonne en liste
            $lesColonnes = (is_array($lesColonnes))?implode(",",$lesColonnes):$lesColonnes;

            $db=DBConnect::getDb();
            $liste = [];
            $q = $db->query("SELECT ".$lesColonnes." FROM ".$table." " .$laCondition);
            while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
                if($donnees != false){
                    $liste[]=($api)?$donnees:new $table($donnees);
                }
            }
            return $liste;
        }

        public static function creerSelect($table,$idtable,$info,$colonne,$classCSS,$filtre,$disabled,$api){
            Gestionnaire::getListByFiltre($table,$colonne,$filtre,$api);
            echo'<select class="borderRad8 borderBlack1" name='.$idtable. ' class='.$classCSS.' '.$disabled." >";
                
            echo'</select>';
        }

    }