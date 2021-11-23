<div class="container flexRow">

    <div class="exterieur flex1"></div>
    
    <div class="page flex2">
        <div><h1>Générer un Projet Web à partir d'une Database</h1></div>
        <form action='VIEW/action.php?mode="db" method="post'>
            <div class="flexWrap">
                
                <div class="MYSQLItem">
                        <div class="alignDroite"><label for=hostName>Hostname : </label></div>
                        <div><input class="borderRad8" name=hostName type="text" value='localhost' placeholder="Hostname" selected required /></div>
                </div>
                <div class="MYSQLItem">
                        <div class="alignDroite"><label for=port>Port : </label></div>
                        <div><input class="borderRad8" name=port type="text"value='' placeholder="Port de la BDD" required /></div>
                </div>
                <div class="MYSQLItem">
                        <div class="alignDroite"><label for=dbName>DBName : </label></div>
                        <div><input class="borderRad8" name=dbName type="text" value='' placeholder="Nom de la BDD" required/> </div>
                </div>
                <div class="MYSQLItem">
                        <div class="alignDroite"><label for=userName>Username : </label></div>
                        <div><input class="borderRad8" name=UserName type="text" value='root' placeholder="Nom de l'utilisateur BDD" required/></div>
                </div>

                <div class="MYSQLItem">
                        <div class="alignDroite"><label for=mdp>Mot de passe : </label> </div>
                        <div><input class="borderRad8" name=mdp type="password"value='' placeholder="Mot de passe Username" required/></div>
                </div>

                <div class="MYSQLItem">
                </div> 


                <div class="MYSQLItem"> 
                    <button type='submit'>Valider</button>
                </div>
                            
            </div>
        </form>
    </div>
   
    
    <div class="exterieur flex1"></div>

</div>