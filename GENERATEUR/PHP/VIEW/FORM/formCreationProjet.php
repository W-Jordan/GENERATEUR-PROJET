
<div class="container flexRow">

    <div class="exterieur flex1"></div>
    
        <div class="page flex2">
            <div><h1>Générer un Projet Web à partir des Classes PHP</h1></div>
            <form action='actionMCD.php?mode=creationProjet' method='post'>
                <div class="flexWrap">
                    
                    <div>

                        <div>
                            <div><label for=nom>Nom du Projet : </label></div>
                            <div><input class="borderRad8" name=nom type="text" value='' placeholder="Nom du Projet" required /></div>
                        </div>

                        <div class="flexRow">
                            <div><label for='repertoireSource' hidden>Repertoire Source : </label></div>
                            <div><input class="borderRad8" name=repertoireSource type="text" value='./GENERATION/monProjet' hidden/></div>
                        </div>

                        <div class="flexRow">
                            <div><label for='userDBUser'>Nom User BD : </label></div>
                            <div><input class="borderRad8" name=userDBUser type="text" value=''/></div>
                        </div>

                        <div class="flexRow">
                            <div><label for='userDBDev'>Nom Dev BD : </label></div>
                            <div><input class="borderRad8" name=userDBDev type="text" value=''/></div>
                        </div>
                        
                    </div>

                    <div class="flexRow"> 
                        <button type='submit'>Valider</button>
                    </div>
    
                </div>
            </form>
        </div>
    
    <div class="exterieur flex1"></div>

</div>