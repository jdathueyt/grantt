<?php
require_once ('./core.php');

if (!isset($_SESSION['id']))
    header("Location: index.php");

function refresh() {

    header("Location: cleanUI.php");
}
?>
<style>

.container {
  display: flex; /* or inline-flex */
  flex-direction: row; /*left to right in ltr; right to left in rtl*/
  flex-wrap: wrap; /*flex items will wrap onto multiple lines, from top to bottom.*/
}
.projects {flex: 1 auto;
  order: 0; /* default is 0 */
  background-color:#E0A868;
  max-width: 200px;
  height: 180px;
  margin-right:20px;
  padding:10px;
  word-wrap: break-word;
  margin-bottom: 20px;
}

.newProject {flex: 1 auto;
  order: 0; /* default is 0 */
  background-color:#E7B65B;
  max-width: 200px;
  height: 180px;
  margin-right:20px;
  padding:10px;
  word-wrap: break-word;
}
</style>
<body style="font-family: monospace; color:#404040; background-color:#F5FAFF;" />


<div style="margin-top:50px;" />
<a href="deconnexion.php">Deconnexion</a><br>
<h2> Mes projet</h2>

<div class="container">
    <?php

    foreach  (getAllProjectsWith($_SESSION['id']) as $value) {
        ?>

        <div class="projects">

            <h3><?php echo $value['nom_projet']; ?></h3>
            <ul>
                <li>Objectifs : <?php echo count(getAllObjectivesFromProject($value['id_projet'])); ?></li>
                <li>Etapes : <?php echo count(getAllStepsFromProjet($value['id_projet'])); ?></li>
                <li>Participants : <?php echo count(getAllParticipantsFromProject($value['id_projet'])); ?></li>
            </ul>
            <br>
            <center>
                <a style="color:#ffffff; font-weight:bold;" href="projet.php?id=<?php echo $value['id_projet']; ?>">Y accéder</a>
            </center>
        </div>

        <?php
    }
    ?>

</div>
<h2> Les autres projet</h2>

<div class="container">
    <?php

    foreach  (getAllProjectsWithout($_SESSION['id']) as $value) {
        ?>

        <div class="projects">

            <h3><?php echo $value['nom_projet']; ?></h3>
            <ul>
                <li>Objectifs : <?php echo count(getAllObjectivesFromProject($value['id_projet'])); ?></li>
                <li>Etapes : <?php echo count(getAllStepsFromProjet($value['id_projet'])); ?></li>
                <li>Participants : <?php echo count(getAllParticipantsFromProject($value['id_projet'])); ?></li>
            </ul>
            <br>
            <center>
                <a style="color:#ffffff; text-decoration: none; font-weight:bold;" href="projet.php?id=<?php echo $value['id_projet']; ?>">Y accéder</a>
            </center>
        </div>

        <?php
    }
    ?>

</div>

<header>
    <title>Gantt</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.js"></script>
</header>


<script src="./app.js" type="text/javascript"></script>


<div style="margin-top:50px;" />
<h2> Débuter un nouveau projet</h2>

<?php
if (isset($_POST['createProjet']) AND $_POST['createProjet'] != null){
    if (isset($_POST['nomProjet'])){
        $req = $db->prepare("INSERT INTO projet (nom_projet) VALUES (:nom_projet)");
        $req->bindParam(':nom_projet', $_POST['nomProjet']);
        $req->execute();

        refresh();
    } else echo "Des valeurs sont nulles <br>";
}
?>

<div class="container">
	<div class="newProject">
        <form action="" method="post">
            <h3>Nom du projet : <input type="text" name="nomProjet" maxlength="50" /><br></h3>
            <input type="submit" value="Créer un nouveau projet" name="createProjet" /><br>
        </form>
	</div>
</div>

</body>