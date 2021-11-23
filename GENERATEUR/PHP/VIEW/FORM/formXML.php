
<div class="container flexRow">

    <div class="exterieur flex1"></div>
    
    <div class="page flex2">
        <div><h1>Générer un Projet Web à partir d'un fichier XML</h1></div>
        <form action='index.php?page=actionXML' method='POST' enctype='multipart/form-data'>
            <div class="flexWrap">
                
                <div class="flexRow">
                        <div class="alignDroite"><label for=ficXML>Fichier XML : </label></div>
                        <div><input class="borderRad8" type="file" name="ficXML" accept=".xml" placeholder="fichier XML" selected required /></div>
                </div>

                <div class="flexRow"> 
                    <button type='submit'>Valider</button>
                </div>
                            
            </div>
        </form>
    </div>
   
    
    <div class="exterieur flex1"></div>

</div>