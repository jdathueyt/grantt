<?php
require_once ("./core.php");

if (!isset($_SESSION['id']))
    header("Location: index.php");

function refresh() {

    header("Location: clean.php");
}

if (isset($_GET['id']) AND $_GET['id'] != null) {
    $projetID = intval(htmlentities($_GET['id']));
    $_SESSION['id_projet'] = $projetID;
    //echo $_GET['id'] . " !!!!";
    //echo $_SESSION['id_projet'];
   //header("Location: projet.php");
} else if (isset($_SESSION['id_projet']))
    $projetID = $_SESSION['id_projet'];
else header("Location: ui.php");
//if ($_SESSION['id_projet'] == 0) header("Location: index.php");


?>
<style>

    .container {
        display: flex; /* or inline-flex */
        flex-direction: row; /*left to right in ltr; right to left in rtl*/
        flex-wrap: wrap; /*flex items will wrap onto multiple lines, from top to bottom.*/
    }
    .projects {flex: 1 auto;
        order: 0; /* default is 0 */
        background-color:#34495e;
        max-width: 200px;
        height: 180px;
        margin-right:20px;
        padding:10px;
        word-wrap: break-word;
        margin-bottom: 20px;
    }

    .newProject {flex: 1 auto;
        order: 0; /* default is 0 */
        background-color:#2980b9;
        max-width: 200px;
        height: 180px;
        margin-right:20px;
        padding:10px;
        word-wrap: break-word;
    }
    #tab {
        color: #000;
        padding: 9px 0px;
    }
    .gestPart, .gestGroup {

        float: left;
    }

    .gestPart {
        margin-right:100px;
    }
    .join {
        padding: 10px 20px;
        background-color: #9CB8A9;
        width: 500px;
        text-align:center;
    }
</style>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<!--<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">-->
<link href="./font-awesome.css" rel="stylesheet">


<body style="font-family: monospace; color:#404040; background-color:#F5FAFF;" />
<style>

</style>
<?php

if (isset($_GET['join']) AND $_GET['join'] != null) {
    $req = $db->prepare("INSERT INTO projet_avoir_participant (id_projet, id_part) VALUES (:id_projet, :id_part)");
    $req->bindParam(':id_projet',  $projetID);
    $req->bindParam(':id_part', $_SESSION['id']);
    $req->execute();
    refresh();
}
echo "<a href='./ui.php'>Retour en arrière</a>";
echo '<div class="join">';
if (PartIsIntoTheProject($_SESSION['id'], $projetID) == null) {
    echo 'Voulez vous rejoindre ce projet ? <a href="?join=1">Rejoindre</a>';
} else echo 'Vous faites déjà partie de ce projet';
?>
</div>
<br><br>
<div style="width:100%; float:left; margin-bottom:50px;">
    <a href="deconnexion.php">Deconnexion</a><br>
<div class="gestPart">
    <p><h3>Gérer les participant du projet</h3>

<?php
if (isset($_POST['addPart']) AND $_POST['addPart'] != null) {
    if (isset($_POST['nomPart']) AND $_POST['nomPart'] != null) {
            $req = $db->query("SELECT * FROM participant WHERE nom_part = '" . $_POST['nomPart'] . "'")->fetchAll();
            if (count($req) == 0) {

                $req = $db->prepare("INSERT INTO participant (nom_part, mail_part, password_part) VALUES (:nom_part, :mail_part, :password_part)");
                $req->bindParam(':nom_part', $_POST['nomPart']);
                $req->bindParam(':mail_part', $_POST['mailPart']);
                $req->bindParam(':password_part', $_POST['passwordPart']);
                $req->execute();

                $idLastInsert = $db->lastInsertId();

                $req = $db->prepare("INSERT INTO projet_avoir_participant (id_part, id_projet) VALUES (:id_part, :id_projet)");
                $req->bindParam(':id_part', $idLastInsert);
                $req->bindParam(':id_projet', $projetID);
                $req->execute();
                echo "Nouveau participant ajouté au projet<br>";
            } else echo "Ce pseudo est déjà utilisé !<br>";
    } else echo "Le nom n'est pas renseigné ! <br>";
}
?>

<form action="" method="post">
    <p><h4>Nouveau participant</h4>
    Nom * : <input type="text" name="nomPart" maxlength="50" /><br>
    Mail : <input type="text" name="mailPart" maxlength="50" /><br>
    Mot de passe : <input type="text" name="passwordPart" maxlength="50" /><br>
    <span style="font-size:10px;">* : Ces champs sont obligatoires</span><br>
    <input style="margin-top:10px; padding: 3px; font-size: 0.8rem;" type="submit" name="addPart" class="btn btn-info" value="Créer ce participant"><br>
    </p>
</form>

<p><h4>Ajouter un participant</h4>
    <?php


    if (isset($_GET['addToProject']) AND $_GET['addToProject'] != null){
        $userToAdd = htmlentities($_GET['addToProject']);
        $req = $db->prepare("INSERT INTO projet_avoir_participant (id_projet, id_part) VALUES (:id_projet, :id_part)");
        $req->bindParam(':id_projet', $projetID);
        $req->bindParam(':id_part', $userToAdd);
        $req->execute();

        refresh();
    }
    if (isset($_GET['remToProject']) AND $_GET['remToProject'] != null){
        $userToRem = htmlentities($_GET['remToProject']);
        $req = $db->prepare("DELETE FROM projet_avoir_participant  WHERE id_projet = '".$projetID."' AND id_part = '".$userToRem."'");
        $req->execute();

        refresh();
    }



    $partIntoProject = getAllParticipantsExcludeGroupFromProject($projetID);
    //var_dump($partIntoProject);
    foreach  (getAllParticipants() as $all) {
        $found = false;
        foreach  ($partIntoProject as $parts) {
            if ($all['id_part'] == $parts['id_part']) $found = true;
        }
       if ($found) echo $all['nom_part']." <a href='?remToProject=".$all['id_part']."'>
    <button type='button' style='padding: 2px; font-size: 0.8rem;' class='btn btn-outline-danger'>Retirer du projet</button></a><BR>";
       else echo $all['nom_part']." <a href='?addToProject=".$all['id_part']."'>
    <button type='button' style='padding: 2px; font-size: 0.8rem;' class='btn btn-outline-success'>Ajouter au projet</button></a><br>";
    }
    ?>
</form>
</div>


<div class="gestGroup">
<p><h3>Gérer les groupes dans le projet</h3>


<?php
if (isset($_POST['createGroupe']) AND $_POST['createGroupe'] != null){
    if (isset($_POST['nomGroupe'])){
        $req = $db->prepare("INSERT INTO groupe (nom_groupe) VALUES (:nom_groupe)");
        $req->bindParam(':nom_groupe', $_POST['nomGroupe']);
        $req->execute();

        $idLastInsert = $db->lastInsertId();

        $req = $db->prepare("INSERT INTO projet_avoir_groupe (id_groupe, id_projet) VALUES (:id_groupe, :id_projet)");
        $req->bindParam(':id_groupe', $idLastInsert);
        $req->bindParam(':id_projet', $projetID);
        $req->execute();

        echo "Groupé créé dans le projet !";
    } else echo "Group et projet group ok <br>";
}


?>

<form action="" method="post">
    <p><h4>Créer un nouveau groupe</h4>
    Nom du groupe : <input type="text" name="nomGroupe" maxlength="50" /><br>
    <input style="margin-top:10px; padding: 3px; font-size: 0.8rem;" type="submit" name="createGroupe" class="btn btn-info" value="Créer un groupe"><br>
    </p>
</form>


    <p><h4>Ajouter un participant au groupe</h4>
<?php


if (isset($_POST['addToGroup']) AND $_POST['addToGroup'] != null){
    if (isset($_POST['groupSelect']) AND isset($_POST['partSelect'])){
        if (isInGroup($_POST['partSelect'], $_POST['groupSelect'])["count(*)"] > 0) {
            echo "Le participant est déjà dans le groupe";
        }else {
            echo "Le participant a été intégré dans le groupe";
            $req = $db->prepare("INSERT INTO participant_avoir_groupe (id_part, id_groupe) VALUES (:id_part, :id_groupe)");
            $req->bindParam(':id_groupe', $_POST['groupSelect']);
            $req->bindParam(':id_part', $_POST['partSelect']);
            $req->execute();

            refresh();
        }
    }
}


?>
    <form action="" method="post">

        Participant : <select name="partSelect">
            <?php

            foreach  (getAllParticipants() as $all) {
                echo '<option value="'.$all['id_part'].'">'.$all['nom_part'].'</option>;';
            }
            ?>
        </select><br>
        Groupe : <select name="groupSelect">
            <?php

            foreach  (getAllGroups() as $all) {
                echo '<option value="'.$all['id_groupe'].'">'.$all['nom_groupe'].'</option>;';
            }
            ?>
        </select><br>
        <input type="submit" style="margin-top:10px; padding: 3px; font-size: 0.8rem;" name="addToGroup" value="Ajouter ce participant au groupe" class="btn btn-info"><br>
        </p>
    </form>

<p><h4>Ajouter un groupe</h4>
<?php



if (isset($_GET['groupAddToProject']) AND $_GET['groupAddToProject'] != null){
    $groupToAdd = htmlentities($_GET['groupAddToProject']);
    $req = $db->prepare("INSERT INTO projet_avoir_groupe (id_projet, id_groupe) VALUES (:id_projet, :id_groupe)");
    $req->bindParam(':id_projet', $projetID);
    $req->bindParam(':id_groupe', $groupToAdd);
    $req->execute();
    refresh();
}
if (isset($_GET['groupRemToProject']) AND $_GET['groupRemToProject'] != null){
    $groupToRem = htmlentities($_GET['groupRemToProject']);
    $req = $db->prepare("DELETE FROM projet_avoir_groupe  WHERE id_projet = '".$projetID."' AND id_groupe = '".$groupToRem."'");
    $req->execute();
    refresh();
}

if (isset($_GET['groupRemMember']) AND $_GET['groupRemMember'] != null AND isset($_GET['idGroup']) AND $_GET['idGroup'] != null){
    $MemToRem = htmlentities($_GET['groupRemMember']);
    $groupId = htmlentities($_GET['idGroup']);
    $req = $db->prepare("DELETE FROM participant_avoir_groupe WHERE id_groupe = '".$groupId."' AND id_part = '".$MemToRem."'");
    $req->execute();
    refresh();
}



$groupIntoProject = getAllGroupsOnProject($projetID);
foreach  (getAllGroups() as $all) {
    $found = false;
    foreach  ($groupIntoProject as $groups) {
        if ($all['id_groupe'] == $groups['id_groupe']) $found = true;
    }
    if ($found) {
        echo $all['nom_groupe']." <a href='?groupRemToProject=".$all['id_groupe']."'>
    <button type='button' style='padding: 2px; font-size: 0.8rem;' class='btn btn-outline-danger'>Retirer du projet</button></a><BR>";
        echo "Membres : ";
        foreach (getAllParticipantsFromGroup($all['id_groupe']) as $members) {
            echo $members['nom_part']." <a href='?groupRemMember=".$members['id_part']."&idGroup=".$all['id_groupe']."'><i class='text-danger icon-remove'></i></a>,  ";
        }
        echo '<br><br>';
    }
    else {
        echo $all['nom_groupe']." <a href='?groupAddToProject=".$all['id_groupe']."'>
    <button type='button' style='padding: 2px; font-size: 0.8rem;' class='btn btn-outline-success'>Cliquer pour ajouter</button></a><BR>";
        echo "Membres : ";
        foreach (getAllParticipantsFromGroup($all['id_groupe']) as $members) {
            echo $members['nom_part']." <a href='?groupRemMember=".$members['id_part']."&idGroup=".$all['id_groupe']."'><i class='text-danger icon-remove'></i></a>,  ";
        }
        echo '<br><br>';
    }
}
?>
</div>
</div>

<header>
    <title>Gantt</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.js"></script>
</header>

<?php

if (isset($_POST['addObj']) AND $_POST['addObj'] != null){
    if (isset($_POST['nomObj']) AND isset($_POST['priorityObj'])){
        $req = $db->prepare("INSERT INTO objectif (nom_objectif, dateDebut_objectif, id_projet) VALUES (:nom_objectif, :dateDebut_objectif, :id_projet)");
        $req->bindParam(':nom_objectif', $_POST['nomObj']);
        $req->bindParam(':dateDebut_objectif', $_POST['priorityObj']);
        $req->bindParam(':id_projet', $projetID);
        $req->execute();

        //$idLastInsert = $db->lastInsertId();

       refresh();
    }
}

?>
<style>
    body {
        /*font:16px Calibri;*/
        margin: 0px 80px;
    }

    table {
        border-collapse:separate;
        border-top:1px solid grey;
    }

    td {
        border-top-width:0;
        white-space:nowrap;
        margin:0;
    }

    .tabl {
        width:calc(100% - 19em);
        overflow-x:scroll;
        margin-left:19em;
        overflow-y:visible;
        padding-bottom:1px;
    }

    .headcol {
        position:absolute;
        width:18em;
        left:6px;
        top:auto;
        border-right:0 none #000;
        border-top-width:3px;
        margin-left: 80px;
    }

    .long {
        background:#FF0;
    }
    .tabl::-webkit-scrollbar
    {
        height:7px;

    }
    .tabl::-webkit-scrollbar-track
    {
        border-radius: 10px;

        webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    }
    .tabl::-webkit-scrollbar-thumb
    {
        background-color: #2b2b2b;


        webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
    }
    .tabl::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    .tabl::-webkit-scrollbar-track {
        background: rgb(0,0,0);
        border: 4px solid transparent;
        background-clip: content-box;   /* THIS IS IMPORTANT */
    }
    .table-sm td, .table-sm th {
        padding: .3rem;
        height: 26px;
    }
    .table-bordered {
        border: none;
    }
</style>
<h3> Diagramme de Gantt</h3>
<div style="background-color: #A1BECC;">
    <div class="tabl">
<TABLE BORDER id="tab" class="table table-striped table-bordered table-sm">
    <TR id="dateMois">
        <TD class="headcol" style="min-width: 50px;"> </TD>
    </TR>
    <TR id="date">
        <TD class="headcol" >Jours</TD>
    </TR>
</TABLE>
    </div>
</div>
<form action="" method="post" style="margin-top:25px;">
    Ajouter un objectif :
    <input type="text" name="nomObj" style="min-width:250px;">
    De priorité
    <select name="priorityObj">
        <option selected value=1>1</option>
        <option value=2>2</option>
        <option value=3>3</option>
        <option value=4>4</option>
        <option value=5>5</option>
    </select>
    <input type="submit" value="Créer un objectif" name="addObj" /><br>
</form>

<?php

if (isset($_POST['addEtape']) AND $_POST['addEtape'] != null) {
    $percent = 0;
    $desc = "";
        if (isset($_POST['nomEtape']) AND isset($_POST['idObj']) AND isset($_POST['dateEtapeS']) AND isset($_POST['dateEtapeE'])){
            $req = $db->prepare("INSERT INTO etape (titre_etape, dateDebut_etape, dateFin_etape, pourcentage_etape, description_etape, id_objectif) 
VALUES (:titre_etape, :dateDebut_etape, :dateFin_etape, :pourcentage_etape, :description_etape, :id_objectif)");
            $dats = DATE($_POST['dateEtapeS']);
            $date = DATE($_POST['dateEtapeE']);
            $req->bindParam(':titre_etape', $_POST['nomEtape']);
            $req->bindParam(':dateDebut_etape', $dats);
            $req->bindParam(':dateFin_etape', $date);
            $req->bindParam(':pourcentage_etape', $percent);
            $req->bindParam(':description_etape', $desc);
            $req->bindParam(':id_objectif', $_POST['idObj']);
            $req->execute();

            //$idLastInsert = $db->lastInsertId();

            refresh();
            echo "Etape bien ajoutée";
        } else echo "Erreur. Au moins un champ n'est pas complet ! ";

//echo $_POST['dateEtapeS'];
}

if (sizeof(getAllObjectivesFromProject($projetID)) > 0 ) {
?>
<form action="" method="post" style="margin-top:25px;">
    Ajouter une étape :
    <input id="writeEtapeTitle" type="text" name="nomEtape" style="min-width:250px;">
    Dans quel objectif l'ajouter ?
    <select name="idObj">
    <?php
        foreach (getAllObjectivesFromProject($projetID) as $objs) {
            echo "<option selected value=".$objs['id_objectif'].">".$objs['nom_objectif']."</option>";
        }
    ?>
    </select>
    <li id="dateEtape"><b>Sélectionnez dans le diagramme la durée de votre étape ! </b></li>
    <input id="dateEtapeS" type="text" name="dateEtapeS" readonly> au <input id="dateEtapeE" type="text" name="dateEtapeE" readonly><br>
    <input type="submit" value="Créer une étape" name="addEtape" /><br>
</form>
<?php
}
?>

<script src="./app.js" type="text/javascript"></script>


<?php
        $etapeIndex = 1;
foreach (getAllObjectivesFromProject($projetID) as $objs) {
    echo "<script>addObj(".$objs['id_objectif'].", '".$objs['nom_objectif']."')</script>";

    foreach (getAllStepsFromObjective($objs['id_objectif']) as $step) {
        $dS = new DateTime($step['dateDebut_etape']);
        $dE = new DateTime($step['dateFin_etape']);
        $str = "['".str_replace('\'', ' ', $step['titre_etape'])."', [".date_format($dS, 'd').", ".date_format($dS, 'm')."], [".date_format($dE, 'd').", ".date_format($dE, 'm')."]]";
        echo "<script>addEtape(".$etapeIndex.", ".$str.")</script>";

        $etapeIndex++;
    }
}
echo "<script>initRow(0);</script>";
?>


<script>
    // Récupéré sur Stackoverflow

    const $source = document.querySelector('#writeEtapeTitle');
    const $result = document.querySelector('#newEtapeText');

    const typeHandler = function(e) {
        $result.innerHTML = e.target.value;
    }

    $source.addEventListener('input', typeHandler) // register for oninput
    $source.addEventListener('propertychange', typeHandler) // for IE8
</script>