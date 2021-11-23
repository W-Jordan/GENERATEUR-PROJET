<?php

  // Selectionner un Projet
  echo'<div class="ligne">';
    echo'<div></div>';
    echo'<div>';
      echo'<div><label>Nom du Projet</label></div>';
        echo'<select class="borderRad8 borderBlack1" name="idProjet">';
              echo'<option value="defaut">--Choisissez votre Projet--</option>';
              $listeProjet = ProjetManager::getList();
              $idProjet = (isset($_GET['idProjet']))?$_GET['idProjet']:"";
              // Boucler sur les projets
              foreach ($listeProjet as $projet) {
                // var_dump($idProjet);
                $selected = ($projet->getIdProjet()==$idProjet)?" selected autofocus ":"";
                echo'<option value="'.$projet->getIdProjet().'"'.$selected.'>'.$projet->getNomProjet().'</option>';
              }
        echo'</select>';
    echo'</div>';
    echo'<div></div>';
  echo'</div>';

  // Boucler sur les Entites de la Base de donnée
  // $listeEntite = EntiteManager::getListByIdProjet(1,false);

?>

<div class="ligne formMCD">
   <!-- Entite/Relation -->
  <aside id="MCDOutils" class="colonne pad2 marg2 bord2">
    
    <!-- Bouton ajout entite -->
    <div class="ajoutEntite">
      <div>Ajouter Entité</div>
      <div><i class="far fa-plus-square"></i></div>
    </div>

    <div class="ajoutRelation">
      <div>Ajouter Relation</div>
      <div><i class="far fa-plus-square"></i></div>
    </div>

  </aside>

  <!-- MCD -->
  <div class="MCD marg2 bord2">
    <div><span id="titreMCD">Mon MCD</span></div>
    <div id="MCD"></div>
  </div>

  <!-- Le formulaire ajout entite/attribut -->
  <form id="formEntite" class="bord2" action="index.php?page=actionEntite" method="post">
    
    <!-- Type : Entite/Relation -->
    <div name="entiteRelation"></div>
    <input class="borderRad8" name="entiteIdProjet" value="" hidden>

    <!-- Nom Entite/Relation -->
    <div>
      <div class="ligne">
        <label>Nom :</label>
        <div><input class="borderRad8" type="text" name="nomEntite" placeholder="Nom entite"></div>
      </div>
    </div>

    <div class="grid">
      <div class="itemGrid">Num</div>              
      <div class="itemGrid">Nom</div>              
      <div class="itemGrid">Type</div>              
      <div class="itemGrid">Taille</div>              
      <div class="itemGrid">Contrainte</div>              
      <div class="itemGrid">Null</div>              
      <div class="itemGrid">Libelle Label</div>  
      <div class="itemGrid"><i class="fas fa-tools fas-2x"></i></div>  
    </div>

    <div class="ligne">
      <div id="ajouterAttribut"><button type="button"><i class="far fa-plus-square"></i></button></div>
    </div>

    <div>
      <div class="vert">
        <button type="submit"><i class="far fa-check-square fa-2x"></i></button>
      </div>
      <div class="rouge">
        <button type="button" id="quitterEntiteRelation"><i class="fas fa-times fa-2x"></i></button>
      </div>
    </div>
  </form>

  <!-- Template Grid Attribut -->
  <template id="gridAttribut">
          <div class="itemGrid"></div>              
          <div class="itemGrid">
            <input class="borderRad8" type="text" name="nomAttribut[]">
          </div>            
          <div class="itemGrid">
            <select class="borderRad8 borderBlack1" name="idType[]" required>

                <?php
                  // Boucler sur les types
                  $liste=[];
                  $liste=TypeManager::getList();
                  var_dump($liste);
                  foreach ($liste as $type) {
                    echo'<option value="'.$type->getIdType().'">'.$type->getLibelleType().'</option>';
                  }
                ?>


            </select>    
          </div>              
          <div class="itemGrid">
            <input class="borderRad8" type="text" name="longueurAttribut[]">
          </div>              
          <div class="itemGrid">
            <select class="borderRad8 borderBlack1" name="idCategorie[]" required>

                <?php
                  // Boucler sur les types
                  $liste=[];
                  $liste=CategorieManager::getList();
                  foreach ($liste as $categorie) {
                    var_dump($liste);
                    echo'<option value="'.$categorie->getIdCategorie().'">'.$categorie->getLibelleCategorie().'</option>';
                  }
                ?>


            </select>    
          </div>              
          <div class="itemGrid">
            <input class="borderRad8" type="hidden" name="nullAttribut[]" class="inputIsNull" value="false">
            <input class="borderRad8" type="checkbox" class="isNull">
          </div>              
          <div class="itemGrid">
            <input class="borderRad8" type="text" name="libelleLabelAttribut[]">
          </div>
          <div class="itemGrid rouge">
            <i class="fas fa-backspace fa-1x"></i>
          </div>
  </template>
  
  <!-- Template Entite -->
  <template id="ent">
    <div id="entite1" class="entite" draggable="true">
      <div class="titre ligne">
        <div class="nomEntite">Entite</div>
        <div><i class="fas fa-angle-up"></i></div>
      </div>
        <div><i class="fas fa-plus-circle"></i></div>
    </div>
  </template>

</div>