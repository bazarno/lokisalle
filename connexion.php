<?php
require_once('inc/init.inc.php');

//traitement pour la deconnexion
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){
	unset($_SESSION['membre']);
	header('location:connexion.php');
}

//redirection si l'utilisateur est déjà connecté
if(userConnecte()){
	header('location:profil.php');
}

//traitement pour la connexion
if($_POST){

	$resultat = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
	$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
	$resultat -> execute();

	if($resultat -> rowCount() > 0){
		//comparer le mdp en post et mdp en base
		$membre = $resultat -> fetch(PDO::FETCH_ASSOC);

		if(md5($_POST['mdp']) == $membre['mdp']){
			foreach($membre as $indice => $valeur){
				$_SESSION['membre'][$indice] = $valeur;
			}

			header('location:profil.php');
		}
	}
	else{
		$msg .='<div class="erreur">Ce pseudo ' . $_POST['pseudo'] . ' n\'existe pas</div>';
	}


}

$page = 'Connexion';
require_once('inc/header.inc.php');

?>
<!-- Contenu html -->
<h1 class="titre_formulaire">Connexion</h1>
<form action="" method="post" style= "width: 400px; padding: 1em; border: 1px solid #CCC; border-radius: 1em;">
	<?php echo $msg; ?>
	<label>Pseudo :</label><br>
	<input type="text" name="pseudo" value=""/><br><br>

	<label>Mot de passe :</label><br>
	<input type="password" name="mdp" value="" /><br><br>

  <input type="submit" value="connection" />
</form>



<?php
 require_once('inc/footer.inc.php')
?>
