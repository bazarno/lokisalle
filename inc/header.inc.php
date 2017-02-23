
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lokisalle - Choisissez la salle dont vous avez besoin</title>

    <link href="<?php echo RACINE_SITE; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo RACINE_SITE; ?>css/homepage.css" rel="stylesheet">
    <link href="<?php echo RACINE_SITE; ?>css/shop-item.css" rel="stylesheet">
    <link href="<?php echo RACINE_SITE; ?>css/style.css" rel="stylesheet">

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
                <a class="navbar-brand" href="<?php echo RACINE_SITE; ?>index.php">LOKISALLE</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                  <li>
                    <a href="<?php echo RACINE_SITE; ?>quisommesnous.php">Qui sommes nous</a>
                  </li>
                  <li>
                    <a href="<?php echo RACINE_SITE; ?>contact.php>Contact">Contact</a>
                  </li>
                  <?php if(userConnecte()):?>
                  <li>
                    <a href="<?php echo RACINE_SITE; ?>profil.php">Profil</a>
                  </li>
                  <li>
                    <a href="<?php echo RACINE_SITE; ?>connexion.php?action=deconnexion">Deconnexion</a>
                  </li>
                <?php else : ?>
                  <li>
										<a href="<?php echo RACINE_SITE; ?>connexion.php">Connexion</a>
										<!--<a href="#" data-toggle="modal" data-target="#insc-modal">Inscription</a>-->
                  </li>
                  <li>
										<a href="<?php echo RACINE_SITE; ?>inscription.php">Inscription</a>
                    <!--<a href="#" data-toggle="modal" data-target="#login-modal">Connexion</a>-->
                  </li>
                    <?php endif; ?>
                    <?php if(userAdmin()):?>
                  <li>
                    <a href="<?php echo RACINE_SITE; ?>admin/gestion_salles.php">Gestion des salles</a>
                  </li>
                  <li>
                    <a href="<?php echo RACINE_SITE; ?>admin/gestion_produits.php">Gestion des produits</a>
                  </li>
                  <li>
                    <a href="<?php echo RACINE_SITE; ?>admin/gestion_membres.php">Gestion des membres</a>
                  </li>
                  <li>
                    <a href="<?php echo RACINE_SITE; ?>admin/gestion_avis.php">Gestion des avis</a>
                  </li>
									<li><?php echo $msg; ?></li>
                  <?php endif; ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
    </nav>
<!--
    <div class="modal fade" id="login-modal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    	<div class="modal-dialog">
        <?php //echo $msg; ?>
    		<div class="loginmodal-container">
    			<h2>Connexion</h2><br>
    		  <form action="" method="post" >
    			<input type="text" name="user" placeholder="login">
    			<input type="password" name="pass" placeholder="mot de passe">
    			<input type="submit" name="connexion" class="login loginmodal-submit" value="connexion">
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
-->
