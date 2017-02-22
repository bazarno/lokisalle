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

require_once('inc/header.inc.php');

?>
<!-- Contenu html -->
<div class="formulaire">
  <form method="post" action="">
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-2 col-form-label">Pseudo</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="pseudo" placeholder="Pseudo">
      </div>
    </div>
    <div class="form-group row">
      <label for="inputPassword3" class="col-sm-2 col-form-label">Mot de passe</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" name="mdp" placeholder="Mot de passe">
      </div>
    </div>
    <div class="form-group row">
      <div class="offset-sm-2 col-sm-10">
        <button type="submit" name="connexion" class="btn btn-primary">Valider</button>
      </div>
    </div>
  </form>
</div>



<?php
 require_once('inc/footer.inc.php')
?>
