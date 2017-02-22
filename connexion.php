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
<form class="form-basic" method="post" action="#">

		<div class="form-title-row">
				<h1>Form Example</h1>
		</div>

		<div class="form-row">
				<label>
						<span>Full name</span>
						<input type="text" name="name">
				</label>
		</div>

		<div class="form-row">
				<label>
						<span>Email</span>
						<input type="email" name="email">
				</label>
		</div>

		<div class="form-row">
				<button type="submit">Submit Form</button>
		</div>

</form>



<?php
 require_once('inc/footer.inc.php')
?>
