<?php
require_once ('./core.php');
if (isset($_SESSION['id'])) {
    header("Location: ui.php");
}
?>

<style>

    .container {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
    }
    .projects {
        flex: 1 auto;
        order: 0;
        background-color:#E0A868;
        max-width: 200px;
        height: 180px;
        margin-right:20px;
        padding:10px;
        word-wrap: break-word;
        margin-bottom: 20px;
    }

    .newProject {flex: 1 auto;
        order: 0;
        background-color:#E7B65B;
        max-width: 200px;
        height: 180px;
        margin-right:20px;
        padding:10px;
        word-wrap: break-word;
    }
</style>
<body style="font-family: monospace; color:#404040; background-color:#F5FAFF;" />
<?php
if (isset($_POST['connect']) AND $_POST['connect'] != null){
    if (isset($_POST['account']) && isset($_POST['password'])){
        $req = $db->query("SELECT * FROM participant WHERE nom_part = '".$_POST['account']."' AND password_part = '".$_POST['password']."'")->fetchAll();
        if (count($req) > 0) {
            $_SESSION['id'] = $req[0]['id_part'];
            header("Location: ui.php");
        }else echo "Le nom de compte ou le mot de passe est incorrect !";

        //$req = $db->prepare("INSERT INTO projet (nom_projet) VALUES (:nom_projet)");
        //$req->bindParam(':nom_projet', $_POST['nomProjet']);
        //$req->execute();
    }
}
?>

<center>
<div style="margin-top:50px;" />
<h2>Page de connexion</h2>
<div class="container">
	<div class="login newProject">
        <form action="" method="post">
            <h3>Nom de compte : <input type="text" name="account" maxlength="50" /><br></h3>
            <h3>Mot de passe : <input type="password" name="password" maxlength="50" /><br></h3>
            <input type="submit" value="Se connecter" name="connect" /><br>
        </form>
	</div>
</div>
</center>
<script src="./app.js" type="text/javascript"></script>