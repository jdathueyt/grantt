<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=ppe_gantt', 'root', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//echo $db->query('SELECT COUNT(*) FROM client')->fetchColumn();

// ETAPE
function getAllStepsFromObjective($idObjectif) {
    global $db;
    return $db->query("SELECT * FROM etape WHERE id_objectif = '".$idObjectif."'")->fetchAll();
}
function getAllStepsFromProjet($idProjet) {
    global $db;
    return $db->query("SELECT * FROM etape LEFT JOIN objectif ON objectif.id_projet = '".$idProjet."' WHERE etape.id_objectif = objectif.id_objectif")->fetchAll();
}
function getStep($idEtape) {
    global $db;
    return $db->query("SELECT * FROM etape WHERE id_etape = '".$idEtape."'")->fetch();
}
function isInGroup($idParticipant, $idGroup) {
    global $db;
    return $db->query("SELECT count(*) FROM participant_avoir_groupe WHERE id_groupe = '".$idGroup."' AND id_part = '".$idParticipant."'")->fetch();
}


//PROJET
function PartIsIntoTheProject($idPart, $idProjet) {
    global $db;
    return $db->query("SELECT * FROM projet_avoir_participant WHERE id_projet = '".$idProjet."' AND id_part = '".$idPart."' LIMIT 1")->fetch();
}
function getAllProjects() {
    global $db;
    return $db->query("SELECT * FROM projet")->fetchAll();
}
function getAllProjectsWith($idPart) {
    global $db;
    return $db->query("

    SELECT projet.* FROM projet
    
    LEFT JOIN projet_avoir_participant ON 
      projet_avoir_participant.id_part = '".$idPart."'
    
    
    LEFT JOIN participant_avoir_groupe ON
        participant_avoir_groupe.id_part = '".$idPart."'
    
    LEFT JOIN projet_avoir_groupe ON
        projet_avoir_groupe.id_groupe = participant_avoir_groupe.id_groupe
    
    
    WHERE projet_avoir_participant.id_projet = projet.id_projet OR
        projet_avoir_groupe.id_projet = projet.id_projet
        
    GROUP BY projet.id_projet

")->fetchAll();
}
function getAllProjectsWithout($idPart) {
    global $db;
    return $db->query("

    SELECT projet.* 
    FROM projet
    WHERE 
    projet.id_projet not in (select id_projet from projet_avoir_participant where id_part = '".$idPart."')
    AND
    projet.id_projet not in (
        SELECT id_projet from participant_avoir_groupe 
        LEFT JOIN projet_avoir_groupe ON
        projet_avoir_groupe.id_groupe = participant_avoir_groupe.id_groupe 
        WHERE participant_avoir_groupe.id_part = '".$idPart.")');

")->fetchAll();
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

function getAllParticipantsExcludeGroupFromProject($idProjet) {
    global $db;
    return $db->query("
SELECT * FROM participant

LEFT JOIN projet_avoir_participant ON 
  projet_avoir_participant.id_projet = '".$idProjet."'
  
  WHERE participant.id_part =  projet_avoir_participant.id_part;
  
")->fetchAll();
}

function getAllParticipantsFromProject($idProjet) {
    global $db;
    return $db->query("
SELECT * FROM participant

LEFT JOIN projet_avoir_participant ON 
  projet_avoir_participant.id_projet = '".$idProjet."'


LEFT JOIN projet_avoir_groupe ON
    projet_avoir_groupe.id_projet = '".$idProjet."'

LEFT JOIN participant_avoir_groupe ON
    participant_avoir_groupe.id_groupe = projet_avoir_groupe.id_groupe


WHERE projet_avoir_participant.id_part = participant.id_part OR
    participant_avoir_groupe.id_part = participant.id_part

GROUP BY participant.id_part
")->fetchAll();
}
function getParticipant($idParticipant) {
    global $db;
    return $db->query("SELECT * FROM participant WHERE id_groupe = '".$idParticipant."' ORDER BY nom_participant DESC LIMIT 1")->fetch();
}
function getAllParticipantsFromGroup($idGroup) {
    global $db;
    return $db->query("SELECT * FROM participant LEFT JOIN participant_avoir_groupe ON participant_avoir_groupe.id_groupe = '".$idGroup."' WHERE participant.id_part = participant_avoir_groupe.id_part")->fetchAll();
}
?>