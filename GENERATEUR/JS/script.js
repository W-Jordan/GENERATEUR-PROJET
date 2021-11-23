  var MCD = document.getElementById("MCD");
  var MCDOutils = document.getElementById("MCDOutils");
  var grid = document.getElementsByClassName('grid')[0];
  var formEntite = document.getElementById("formEntite");
  var logoGen = document.getElementsByClassName('logoGen')[0];
  var selectProjet = document.getElementsByName("idProjet")[0];

  document.getElementsByClassName('ajoutEntite')[0].addEventListener('click',function(){
    afficherFormulaireEntiteRelation('Entite');
  });
  document.getElementsByClassName('ajoutRelation')[0].addEventListener('click',function(){
    afficherFormulaireEntiteRelation('Relation');
  });

  document.getElementById('ajouterAttribut').addEventListener('click',ajouterAttributListe);
  document.getElementById('quitterEntiteRelation').addEventListener('click',quitterEntiteRelation);
  window.addEventListener('load',init);
  selectProjet.addEventListener("change", changeProjet);
  // selectProjet.addEventListener("focus", changeProjet);
  selectProjet.addEventListener("focus", function(){
    // alert('ici');
  });

  // Requete des Entites pour un Projet
  const reqListeEntite = new XMLHttpRequest();
  reqListeEntite.onreadystatechange = function (event) {
      if (this.readyState === XMLHttpRequest.DONE) {
          if (this.status === 200) {
              console.log("Réponse reçue: %s", this.responseText);
              let reponse = JSON.parse(this.responseText);
              
              let pourcentage = 50;
              // Vide les Entites pour mettre a jour
              viderEntiteDuMCD();
              for (let entite of reponse) { 
                console.log(entite);

                // Recuperer les Entites
                let monEntite = creerDivEntiteJson(entite);
                MCD.appendChild(monEntite);
                pourcentage+=10;
                monEntite.style.left = pourcentage+"%";

                // Actualise la Page avec la nouvelleEntite
                // changeProjet();
              }
          } else {
              console.log("Status de la réponse: %d (%s)", this.status, this.statusText);
          }
      }
  };


//#region Logo animation
  
  logoGen.addEventListener('mouseover',function(){faireMuscu(true);});
  logoGen.addEventListener('mouseleave',function(){faireMuscu(false);});

  function faireMuscu(bool){
    if(bool){
      logoGen.src='./IMG/logoGenerateur.svg';
      logoGen.alt='logo Generateur';
    }else{
      logoGen.src='./IMG/logoGenerateur2.svg';
      logoGen.alt='logo Generateur2';
    }
  }

//#endregion

//#region MCD

  function viderEntiteDuMCD(){
    while (MCD.hasChildNodes()) {
      MCD.removeChild(MCD.lastChild);
    }
  }

  function afficherFormulaireEntiteRelation(type){
    formEntite.style.display="block";
    document.getElementsByName('entiteRelation')[0].value=type;
    let entiteIdProjet = document.getElementsByName('entiteIdProjet')[0];
    entiteIdProjet.value = document.getElementsByName('idProjet')[0].value
  } 

  function creerDivEntiteJson(jsonEntite) {
    var templateEntite = document.getElementById("ent");
    var monEntite = templateEntite.content.cloneNode(true);
    MCDOutils.appendChild(monEntite);
    monEntite = document.getElementById("entite1");
    monEntite.id = "entite" + jsonEntite.idEntite;
    monEntite.addEventListener("dragstart", drag);

    // Gestion du Nom Entite
    var nomEntite = monEntite.getElementsByClassName("nomEntite")[0];
    nomEntite.textContent = jsonEntite.nomEntite;
    
    // Listener sur les FA
    var lesFA = document.getElementsByTagName("i");
    for (let FA of lesFA) {
      FA.addEventListener("click", gestionAwesome);
    }
    return monEntite;
  }

  function changeProjet() {
    if(selectProjet.value!="defaut"){
        // je lance une requete Ajax
        reqListeEntite.open('POST', 'index.php?page=listeAPI', true);
        reqListeEntite.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        reqListeEntite.send("idProjet=" + selectProjet.value);
    }else{
      viderEntiteDuMCD();
    }
  }

//#endregion

//#region Creation Entite

  function init(){
    // Preparation de la grid Entite/Attribut
    grid.style.gridTemplateRows="3vh";
    formEntite.style.display='none';
  }
  
  function ajouterAttributListe(){
    // Ajoute les Attributs aux grid
    let gridAttribut = document.getElementById("gridAttribut").content.cloneNode(true);

    grid.appendChild(gridAttribut);

    // Recuperer l'input null cree
    var listeInputNull = document.getElementsByClassName('inputIsNull');
    var inputNull = listeInputNull[listeInputNull.length-1]

    // Recuperer la checkbox creee
    var listCheckedNull = document.getElementsByClassName('isNull');
    var checkedNull = listCheckedNull[listCheckedNull.length-1];

    // Input caché pour isNull
    checkedNull.addEventListener('click', function(){inputNull.value=checkedNull.checked;});

    // Listener sur la fleche rouge
      // Supprimer lui meme et ses
    grid.lastElementChild.addEventListener('click',supprimerLigneAttribut);
  }

  function supprimerLigneAttribut(e){
    let laCaseRouge = e.target.parentElement;
    let i=1;
    while(i!=8){
      laCaseRouge.previousElementSibling.remove();
      i++;
    }
    laCaseRouge.remove();
  }

  function enregistrerEntiteRelation(){
    // Recuperer les Informations du formulaire
      // Verifie les 
    // Appeler la requete de creation des Entites
  }

  function quitterEntiteRelation(){
    // Supprimer les Attributs
    let lesEnfantsGrid = document.getElementsByClassName('itemGrid');
    
    // Supprimer jusqu'a ce qu'il ne reste que l'entete (8)
    while(lesEnfantsGrid.length>8){
      lesEnfantsGrid[8].remove();
    }
    grid.style.gridTemplateRows="3vh";
    // Vider le nom de l'entite
    document.getElementsByName('nomEntite')[0].value="";

    // Cache le form
    formEntite.style.display="none";
  }

//#endregion


//#region Drag/Drop

    // Evenement sur MCD
    MCD.addEventListener("dragover", dragOver);
    MCD.addEventListener("dragenter", dragEnter);
    MCD.addEventListener("dragleave", dragLeave);
    MCD.addEventListener("drop", dragDrop);

    function drag(e) {
      e.dataTransfer.setData("text", e.target.id);
    }
    function dragOver(e) {
      e.preventDefault();
    }
    function dragEnter() {
      this.className += "hovered";
    }
    function dragLeave() {
      this.className = "MCD";
    }
    function dragDrop(e) {
      e.preventDefault();
      var data = e.dataTransfer.getData("text");
      var monEntite = document.getElementById(data);
      monEntite.style.flex = "none";
      MCD.appendChild(document.getElementById(data));
      
      // creerDivEntite(compteurEntite);
    }
  //#endregion

//#region Gestion FontAwesome

  function gestionAwesome(event){
    let leFontAwesome=event.target;
    let leFrere = leFontAwesome.parentNode.parentNode.nextElementSibling;

    switch (leFontAwesome.className) {
      // Ajouter un Attribut
      case "fas fa-plus-circle":
        formGestionAttribut.style.display='flex';
        leFontAwesome.parentNode.parentNode.appendChild(creerDivAttribut());
        break;
      case "fas fa-angle-up":
        derouleEnroule("enroule",leFontAwesome,leFrere);
        break;
      case "fas fa-angle-down":
        derouleEnroule("deroule", leFontAwesome, leFrere);
        break;
      default:
        break;
    }
  }

  function derouleEnroule(action,leFA,leFrere){
    var dspl,clssFA;
    if(action=='deroule'){
      dspl='flex';
      clssFA = "fas fa-angle-up";
    }else{
      dspl='none'
      clssFA = "fas fa-angle-down";
    }
    
    while (leFrere) {
      leFrere.style.display = dspl;
      leFrere = leFrere.nextElementSibling;
    }
    leFA.className = clssFA;
  }

//#endregion

