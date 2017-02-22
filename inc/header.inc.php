
<?php

/////  GESTION DECONNEXION   //////

if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){
	unset($_SESSION['membre']);
	header('location:connexion.php');
}

/////  GESTION CONNEXION   //////
//redirection si l'utilisateur est déjà connecté
if(userConnecte()){
	header('location:profil.php');
}

//traitement pour la connexion
if (isset($_POST['connexion'])) {

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

// GESTION INSCRIPTION ///////

if (isset($_POST['inscription'])) {
  $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo']);

  if(!empty($_POST['pseudo'])){
    if($verif_caractere){
      if(strlen($_POST['pseudo']) < 3 || strlen($_POST['pseudo']) > 20){
      $msg .= '<div class="erreur">Veuillez renseigner un pseudo de 3 à 20 caractères</div>';
      }
    }
    else{
    $msg .= '<div class="erreur">Caractères acceptés : A à Z, 0 à 9 et ".", "-", "_"</div>';
    }
  }
  else{
  $msg .= '<div class="erreur">Veuillez renseigner un pseudo</div>';
  }

  // ++ verifs a faire sur les autres champs


  // avant d'inserer le nouveau membre il faut verifier que le pseudo est disponible
  if(empty($msg)){ // si $msg est vide tout est ok
  $result = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
  $result -> bindParam (':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
  $result -> execute();

      if ($result -> rowCount() > 0){ // si sup à 0 c'est qu'il y en a au moins 1
          $msg .= '<div class="erreur">Ce pseudo ' . $_POST['pseudo'] . ' est déjà pris</div>';
      }
      else{
          $resultat = $pdo -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, 0, NOW())");

          $mdp = md5($_POST['mdp']);
          $resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
          $resultat -> bindParam(':mdp',$mdp , PDO::PARAM_STR);
          $resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
          $resultat -> bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
          $resultat -> bindParam(':email', $_POST['email'], PDO::PARAM_STR);
          $resultat -> bindParam(':civilite', $_POST['civilite'], PDO::PARAM_STR);

          //$resultat -> execute();
          if($resultat -> execute()){
						header('location:connexion.php');
          }

          $msg .='<div class="validation">Inscription validée</div>';

        }
      }
}

 ?>


<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lokisalle - Choisissez la salle dont vous avez besoin</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/homepage.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">LOKISALLE</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                  <li>
                    <a href="#">Qui sommes nous</a>
                  </li>
                  <li>
                    <a href="#">Contact</a>
                  </li>
                  <?php if(userConnecte()):?>
                  <li>
                    <a href="profil.php">Profil</a>
                  </li>
                  <li>
                    <a href="connexion.php?action=deconnexion">Deconnexion</a>
                  </li>
                <?php else : ?>
                  <li>
										<!--<a href="connexion.php">Connexion</a>-->
										<a href="#" data-toggle="modal" data-target="#insc-modal">Inscription</a>
                  </li>
                  <li>
										<!--<a href="inscription.php">Inscription</a>-->
                    <a href="#" data-toggle="modal" data-target="#login-modal">Connexion</a>
                  </li>
                    <?php endif; ?>
                    <?php if(userAdmin()):?>
                  <li>
                    <a href="admin/gestion_salle.php">Gestion des salles</a>
                  </li>
                  <li>
                    <a href="admin/gestion_produits.php">Gestion des produits</a>
                  </li>
                  <li>
                    <a href="admin/gestion_membres.php">Gestion des membres</a>
                  </li>
                  <li>
                    <a href="admin/gestion_avis.php">Gestion des avis</a>
                  </li>

                  <?php endif; ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
    </nav>

    <div class="modal fade" id="login-modal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    	<div class="modal-dialog">
        <?php //echo $msg; ?>
    		<div class="loginmodal-container">
    			<h2>Connexion</h2><br>
    		  <form action="" method="post" >
    			<input type="text" name="user" placeholder="login">
    			<input type="password" name="pass" placeholder="mot de passe">
    			<input type="submit" name="login" class="login loginmodal-submit" value="connexion">
    		  </form>

    		  <div class="login-help">
    			<a href="#">Mot de passe oublié</a>
    		  </div>
    		</div>
    	</div>
    </div>

    <div class="modal fade" id="insc-modal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <?php //echo $msg; ?>
        <div class="loginmodal-container">
          <h2>Inscription</h2><br>
          <form action="" method="post" >
            <?php //echo $msg; ?>
          	<label>Pseudo :</label><br>
          	<input type="text" name="pseudo" value=""/><br><br>

          	<label>Mot de passe :</label><br>
          	<input type="password" name="mdp" value="" /><br><br>

            <label>Nom :</label><br>
          	<input type="text" name="nom" value="" /><br><br>

            <label>prénom :</label><br>
          	<input type="text" name="prenom" value="" /><br><br>

            <label>Email :</label><br>
          	<input type="text" name="email" value="" /><br><br>

            <label>Civilité :</label>
          	<select name="civilite">
              <option value="m">Homme</option>
              <option value="f">Femme</option>
            </select><br><br>
            <input type="submit" name="inscription" class="login loginmodal-submit" value="inscription">
          </form>
        </div>
      </div>
    </div>

        <section>
