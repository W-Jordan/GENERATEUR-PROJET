<div class="container flexRow">

    <div class="exterieur flex1"></div>
    
        <div class="page flex2">
            <div><h1><?=$titre?></h1></div>
            <form action='action.php?mode="php" method="post'>
                <div class="flexWrap">
                    
                    <div>

                        <div>
                            <div><label for=class1>Nom de la Classe : </label></div>
                            <div><input class="borderRad8" name=class1 type="text" value='' placeholder="nom de la Classe" required /></div>
                        </div>
                                        
                        <div>
                            <div><label for=class1>Les attributs de la Classe: </label></div>
                        </div>

                        <div class="flexRow">
                            <div class="alignDroite"><label for=class1>Nom de l'attribut 1 : </label></div>
                            <div><input class="borderRad8" name=att1-1 type="text" value='' placeholder="nom de l'attribut'" required /></div>
                        </div>
                        <div class="flexRow">
                            <div class="alignDroite"><label for=class1>Nom de l'attribut 2 : </label></div>
                            <div><input class="borderRad8" name=att-2 type="text" value='' placeholder="nom de l'attribut'" required /></div>
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