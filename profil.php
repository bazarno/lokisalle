<?php

require_once('inc/init.inc.php');

if(!userAdmin()){
	header('location:connexion.php');
}

extract($_SESSION['membre']);

if($_POST){
	//debug($_POST);
	//debug($_FILES);


	if(isset($_GET['action']) && $_GET['action'] == 'modifier'){
		$resultat = $pdo -> prepare("REPLACE INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, statut) VALUES (:id_membre, :pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut)");

	}

	$resultat -> bindParam(':id_membre', $_POST['id_membre'], PDO::PARAM_INT);
	$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
	$resultat -> bindParam(':mdp', $_POST['mdp'], PDO::PARAM_STR);
	$resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
	$resultat -> bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
	$resultat -> bindParam(':email', $_POST['email'], PDO::PARAM_STR);
	$resultat -> bindParam(':civilite', $_POST['civilite'], PDO::PARAM_STR);
	$resultat -> bindParam(':statut', $_POST['statut'], PDO::PARAM_INT);

	//on met le execute dans un if pour etre sur que les traitements contenus dans le if
	// ne s'effcuteront qu'en cas de succès de la requête
	if($resultat -> execute()){
		$_GET['action'] = 'affichage';
		$last_id = $pdo -> lastInsertId();
		$msg .= '<dic class="validation">Le membre ' . $last_id . 'a bien été enregistré</div>';
	}


}

require_once('inc/header.inc.php');
?>

  <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="thumbnail">
                    <img class="img-responsive" src="<?= RACINE_SITE ?>img/profil.png" style="height:300px;" alt="">
                    <div class="caption-full">
                        <h4>Bienvenue <?= $pseudo ?></h4><br>
												<div><a href="?action=modifier&id_membre=<?php echo $id_membre; ?>">Modifier mon profil</a></div><br>
                        <p><?= $prenom ?></p>
												<p><?= $nom ?></p>
                        <p>
                        	<ul>
														<li>Email : <?= $email ?></li>
														<li>Civilté : <?= $civilite ?></li>
														<li>Date d'enregistrement : <?= $date_enregistrement ?></li>
													</ul>
                        </p>
                    </div>

                </div>

            </div>

        </div>

				<div class="row">
            <div class="col-md-12">
							<div class="caption-full">
							<?php if(isset($_GET['action']) &&  $_GET['action'] == 'modifier') : ?>

								<!-- s'il s'agit de modifier un membre on recupère ses infos via l'id dans l'url -->
								<?php
								if(isset($_GET['id_membre']) && is_numeric($_GET['id_membre'])){
									$resultat = $pdo -> prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
									$resultat -> bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
									if($resultat -> execute()){
										$membre_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
										//produit_actuel est un array avec toutes les infos du produit à modifier
									}
								}
								//toutes les infos du produit sont stockées dans des variables
								//si il y a un produit_actuel (à modfier) on y stocke sa valeur pour pouvoir les afficher dans le Formulaire
								// s'il n'y a pas de produit_actuel c'est donc un ajout donc on affiche rien ('') dans la variable
								$pseudo = (isset($membre_actuel)) ? $membre_actuel['pseudo'] : '';
								$mdp = (isset($membre_actuel)) ? $membre_actuel['mdp'] : '';
								$nom = (isset($membre_actuel)) ? $membre_actuel['nom'] : '';
								$prenom = (isset($membre_actuel)) ? $membre_actuel['prenom'] : '';
								$email = (isset($membre_actuel)) ? $membre_actuel['email'] : '';
								$civilite = (isset($membre_actuel)) ? $membre_actuel['civilite'] : '';
								$statut = (isset($membre_actuel)) ? $membre_actuel['statut'] : '';

								$action = (isset($membre_actuel)) ? 'Modifier' : 'Ajouter' ;
								$id_membre = (isset($membre_actuel)) ? $membre_actuel['id_membre'] : '';


								?>


							<form action="" method="post">
								<h2>Modifier mon profil</h2>
								<!-- encrypt permet de recuperer les fichiers uploader grace à la superglobale $_file -->
								<input type="hidden" name="id_membre" value="<?= $id_membre ?>">

								<label>Pseudo</label><br>
								<input type="text" name="pseudo" value="<?= $pseudo ?>"><br>

								<label>Mot de passe</label><br>
								<input type="text" name="mdp" value="<?= $mdp ?>"><br>

								<label>Nom</label><br>
								<input type="text" name="nom" value="<?= $nom ?>"><br>

								<label>Prenom</label><br>
								<input type="text" name="prenom" value="<?= $prenom ?>"><br>

								<label>Email</label><br>
								<input type="text" name="email" value="<?= $email ?>"></textarea><br><br>

								<label>Civilite: </label>
									<select name="civilite">
										<option>-- Selectionnez --</option>
										<option <?= ($civilite == 'm') ? 'selected' : '' ?> value="m">Homme</option>
										<option <?= ($civilite == 'f') ? 'selected' : '' ?> value="f">Femme</option>
									</select><br/><br>



								<input type="hidden" name="statut" value="<?= $statut ?>">

								<input type="submit" value="modifier">
								<br><br>


							</form>

			<?php endif; ?>
							</div>
						</div>
				</div>

    </div>
    <!-- /.container -->


    <?php
     require_once('inc/footer.inc.php')
    ?>
