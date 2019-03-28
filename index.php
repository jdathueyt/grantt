<?php

$db = new PDO('mysql:host=localhost;dbname=grantt', 'root', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
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
function getAllGroupsOnProject($idProject) {
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
    return $db->query("SELECT * FROM objectif WHERE id_project = '".$idProject."'")->fetchAll();
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
function getAllParticipantsFromProject($idGroup) {
    global $db;
    return $db->query("SELECT * FROM participant LEFT JOIN participant_avoir_groupe ON participant.id_part = participant_avoir_groupe.id_part where participant_avoir_groupe.id_group = '".$idGroup."'")->fetchAll();
}
function getParticipant($idParticipant) {
    global $db;
    return $db->query("SELECT * FROM participant WHERE id_groupe = '".$idParticipant."' ORDER BY nom_participant DESC LIMIT 1")->fetch();
}

$idPart = 1;
$idGroup = 1;
$idProjet = 1;

?>

<?php

foreach  (getAllProjects() as $value) {
    ?>
    <div style="border: 1px solid green ; width:200px;">
        <H1><?php echo $value['nom_projet']; ?></H1>
        <a href="projet.php?id=<?php echo $value['id_projet']; ?>">Y accéder</a>
    </div>
    <?php
}
?>


<?php
if (isset($_POST['addPart']) AND $_POST['addPart'] != null){
        if (isset($_POST['nomPart']) AND isset($_POST['mailPart']) AND isset($_POST['passwordPart'])){
            $req = $db->prepare("INSERT INTO participant (nom_part, mail_part, password_part) VALUES (:nom_part, :mail_part, :password_part)");
            $req->bindParam(':nom_part', $_POST['nomPart']);
            $req->bindParam(':mail_part', $_POST['mailPart']);
            $req->bindParam(':password_part', $_POST['passwordPart'] );
            $req->execute();
            echo "Ajouté";
        } else echo "Des valeurs sont nulles <br>";
}
?>

<form action="" method="post">
    <p><h3>Ajouter un participant</h3>
    Nom : <input type="text" name="nomPart" maxlength="50" /><br>
    Mail : <input type="text" name="mailPart" maxlength="50" /><br>
    Mot de passe : <input type="text" name="passwordPart" maxlength="50" /><br>
    <input type="submit" value="Ajouter ce participant" name="addPart" /><br>
    </p>
    ______________________________________________
</form>


<?php
if (isset($_POST['createGroupe']) AND $_POST['createGroupe'] != null){
    if (isset($_POST['nomGroupe'])){
        $req = $db->prepare("INSERT INTO groupe (nom_groupe) VALUES (:nom_groupe)");
        $req->bindParam(':nom_groupe', $_POST['nomGroupe']);
        $req->execute();

        $idLastInsert = $db->lastInsertId();

        $req = $db->prepare("INSERT INTO projet_avoir_groupe (id_groupe, id_projet) VALUES (:id_groupe, :id_projet)");
        $req->bindParam(':id_groupe', $idLastInsert);
        $req->bindParam(':id_projet', $idProjet);
        $req->execute();

        echo "Groupé créé dans le projet !";
    } else echo "Group et projet group ok <br>";
}


?>

<form action="" method="post">
    <p><h3>Créer un groupe</h3>
    Nom du groupe : <input type="text" name="nomGroupe" maxlength="50" /><br>
    <input type="submit" value="Créer un groupe" name="createGroupe" /><br>
    </p>
    ______________________________________________
</form>


<?php
if (isset($_POST['createProjet']) AND $_POST['createProjet'] != null){
    if (isset($_POST['nomProjet'])){
        $req = $db->prepare("INSERT INTO projet (nom_projet) VALUES (:nom_projet)");
        $req->bindParam(':nom_projet', $_POST['nomProjet']);
        $req->execute();
        echo "Projet créé !";
    } else echo "Des valeurs sont nulles <br>";
}
?>

<form action="" method="post">
    <p><h3>Créer un projet</h3>
    Nom du projet : <input type="text" name="nomProjet" maxlength="50" /><br>
    <input type="submit" value="Créer un projet" name="createProjet" /><br>
    </p>
    ______________________________________________
</form>
