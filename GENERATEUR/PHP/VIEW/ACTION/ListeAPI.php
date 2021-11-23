<?php

$id = $_POST['idProjet'];
echo json_encode(EntiteManager::getListByIdProjet($id,true));
?>