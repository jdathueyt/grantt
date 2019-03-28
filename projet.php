<?php
session_start();

if (isset($_GET['id']) AND $_GET['id'] != null) {
    $_SESSION['id_projet'] = intval(htmlentities($_GET['id']));
    //echo $_GET['id'] . " !!!!";
    //echo $_SESSION['id_projet'];
   header("Location: projet.php");
}
if ($_SESSION['id_projet'] == 0) header("Location: index.php");

function refresh() {
    header("Location: projet.php");
}
$idProjet = $_SESSION['id_projet'];


$db = new PDO('mysql:host=localhost;dbname=grantt', 'root', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$db->exec("SET CHARACTER SET utf8");
//echo $db->query('SELECT COUNT(*) FROM client')->fetchColumn();

// ETAPE
function getAllStepsFromObjective($idObjectif) {
    global $db;
    return $db->query("SELECT * FROM etape WHERE id_objectif = '".$idObjectif."'")->fetchAll();
}
function getStep($idEtape) {
    global $db;
    return $db->query("SELECT * FROM etape WHERE id_etape = '".$idEtape."'")->fetch();
}

//PROJET
function PartIsIntoTheProject($idPart) {
    global $db;
    global $idProjet;
    return $db->query("SELECT * FROM projet_avoir_participant WHERE id_projet = '".$idProjet."' AND id_part = '".$idPart."' LIMIT 1")->fetch();
}
function getAllProjects() {
    global $db;
    return $db->query("SELECT * FROM projet")->fetchAll();
}
function getProject($idProjet) {
    global $db;
    return $db->query("SELECT * FROM projet WHERE id_projet = '".$idProjet."' ORDER BY nom_projet DESC LIMIT 1")->fetch();
}

//GROUPE
function getAllGroups() {
    global $db;
    return $db->query("SELECT * FROM groupe")->fetchAll();
}
function getAllGroupsOnProject($idProjet) {
    global $db;
    return $db->query("SELECT * FROM groupe LEFT JOIN projet_avoir_groupe ON groupe.id_groupe = projet_avoir_groupe.id_groupe where projet_avoir_groupe.id_projet = '".$idProjet."'")->fetchAll();
}
function getGroup($idGroup) {
    global $db;
    return $db->query("SELECT * FROM groupe WHERE id_groupe = '".$idGroup."' ORDER BY nom_groupe DESC LIMIT 1")->fetch();
}

//OBJECTIF

function getAllObjectivesFromProject($idProject) {
    global $db;
    return $db->query("SELECT * FROM objectif WHERE id_projet = '".$idProject."'")->fetchAll();
}
function getObjective($idObjectif) {
    global $db;
    return $db->query("SELECT * FROM objectif WHERE id_objectif = '".$idObjectif."'")->fetch();
}

//PARTICIPANT
function getAllParticipants() {
    global $db;
    return $db->query("SELECT * FROM participant")->fetchAll();
}
function getAllParticipantsFromGroup($idGroup) {
    global $db;
    return $db->query("SELECT * FROM participant LEFT JOIN participant_avoir_groupe ON participant.id_part = participant_avoir_groupe.id_part where participant_avoir_groupe.id_group = '".$idGroup."'")->fetchAll();
}
function getAllParticipantsFromProject($idProject) {
    global $db;
    return $db->query("SELECT * FROM participant LEFT JOIN projet_avoir_participant ON participant.id_part = projet_avoir_participant.id_part where projet_avoir_participant.id_projet = '".$idProject."'")->fetchAll();
}
function getParticipant($idParticipant) {
    global $db;
    return $db->query("SELECT * FROM participant WHERE id_groupe = '".$idParticipant."' ORDER BY nom_participant DESC LIMIT 1")->fetch();
}

$idPart = 1;
$idGroup = 1;

?>

<?php
$proj = getProject(intval($idProjet));




if (isset($_GET['join']) AND $_GET['join'] != null) {
    $req = $db->prepare("INSERT INTO projet_avoir_participant (id_projet, id_part) VALUES (:id_projet, :id_part)");
    $req->bindParam(':id_projet', $idProjet);
    $req->bindParam(':id_part', $idPart);
    $req->execute();
    refresh();
}

if (PartIsIntoTheProject($idPart) == null) {
    echo 'Voulez vous rejoindre ce projet ? <a href="?join=1">Rejoindre</a>';
} else echo 'Vous faites déjà partie de ce projet';
?>


    <p><h3>Gérer les participant du projet</h3>
    <?php



    if (isset($_GET['addToProject']) AND $_GET['addToProject'] != null){
        $userToAdd = htmlentities($_GET['addToProject']);
        $req = $db->prepare("INSERT INTO projet_avoir_participant (id_projet, id_part) VALUES (:id_projet, :id_part)");
        $req->bindParam(':id_projet', $idProjet);
        $req->bindParam(':id_part', $userToAdd);
        $req->execute();
        refresh();
    }



    $partIntoProject = getAllParticipantsFromProject($idProjet);
    //var_dump($partIntoProject);
    foreach  (getAllParticipants() as $all) {
        $found = false;
        foreach  ($partIntoProject as $parts) {
            if ($all['id_part'] == $parts['id_part']) $found = true;
        }
       if ($found) echo $all['nom_part']." est déjà dans le projet <BR>";
       else echo $all['nom_part']." <a href='?addToProject=".$all['id_part']."'>Cliquer pour ajouter</a><br>";
    }
    ?>
    ______________________________________________
</form>



<p><h3>Gérer les groupes dans le projet</h3>
<?php



if (isset($_GET['groupAddToProject']) AND $_GET['groupAddToProject'] != null){
    $groupToAdd = htmlentities($_GET['groupAddToProject']);
    $req = $db->prepare("INSERT INTO projet_avoir_groupe (id_projet, id_groupe) VALUES (:id_projet, :id_groupe)");
    $req->bindParam(':id_projet', $idProjet);
    $req->bindParam(':id_groupe', $groupToAdd);
    $req->execute();
    refresh();
}



$groupIntoProject = getAllGroupsOnProject($idProjet);
foreach  (getAllGroups() as $all) {
    $found = false;
    foreach  ($groupIntoProject as $groups) {
        if ($all['id_groupe'] == $groups['id_groupe']) $found = true;
    }
    if ($found) echo $all['nom_groupe']." est déjà dans le projet <BR>";
    else echo $all['nom_groupe']." <a href='?groupAddToProject=".$all['id_groupe']."'>Cliquer pour ajouter</a><br>";
}
?>
    ______________________________________________


<header>
    <title>Grantt</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.js"></script>
</header>

<?php

if (isset($_POST['addObj']) AND $_POST['addObj'] != null){
    if (isset($_POST['nomObj']) AND isset($_POST['priorityObj'])){
        $req = $db->prepare("INSERT INTO objectif (nom_objectif, dateDebut_objectif, id_projet) VALUES (:nom_objectif, :dateDebut_objectif, :id_projet)");
        $req->bindParam(':nom_objectif', $_POST['nomObj']);
        $req->bindParam(':dateDebut_objectif', $_POST['priorityObj']);
        $req->bindParam(':id_projet', $idProjet);
        $req->execute();

        //$idLastInsert = $db->lastInsertId();

       refresh();
    }
}

?>
<H1> Diagramme <?php echo getProject($idProjet)['nom_projet']; ?> </H1>
<TABLE BORDER id="tab">
    <TR id="dateMois">
        <TD style="min-width: 350px;"> </TD>
        <TD COLSPAN=31>Janvier</TD>
        <TD COLSPAN=28>Février</TD>
        <TD COLSPAN=31>Mars</TD>
        <TD COLSPAN=30>Avril</TD>
    </TR>
    <TR id="date">
        <TD>Jours</TD>
    </TR>
</TABLE>

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

}

if (sizeof(getAllObjectivesFromProject($idProjet)) > 0 ) {
?>
<form action="" method="post" style="margin-top:25px;">
    Ajouter une étape :
    <input id="writeEtapeTitle" type="text" name="nomEtape" style="min-width:250px;">
    Dans quel objectif l'ajouter ?
    <select name="idObj">
    <?php
        foreach (getAllObjectivesFromProject($idProjet) as $objs) {
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
foreach (getAllObjectivesFromProject($idProjet) as $objs) {
    echo "<script>addObj(5".$objs['id_objectif'].", '".$objs['nom_objectif']."')</script>\n"; // LE 5 PERMET D EVITER LES DOUBLONS ENTRE OBJECTIF ET ETAPE

    foreach (getAllStepsFromObjective($objs['id_objectif']) as $step) {
        $dS = new DateTime($step['dateDebut_etape']);
        $dE = new DateTime($step['dateFin_etape']);
        $str = "['".str_replace('\'', ' ', $step['titre_etape'])."', [".date_format($dS, 'd').", ".date_format($dS, 'm')."], [".date_format($dE, 'd').", ".date_format($dE, 'm')."]]";
        echo "<script>addEtape(".$etapeIndex.", ".$str.")</script>\n";

        $etapeIndex++;
    }
}
echo "<script>initRow(0);</script>";
?>
<script>

    const $source = document.querySelector('#writeEtapeTitle');
    const $result = document.querySelector('#newEtapeText');

    const typeHandler = function(e) {
        $result.innerHTML = e.target.value;
    }

    $source.addEventListener('input', typeHandler) // register for oninput
    $source.addEventListener('propertychange', typeHandler) // for IE8
</script>
