
<?php
require_once('inc/init.inc.php');

if(!userAdmin()){
	header('location:connexion.php');
}

if(isset($_GET['id_produit']) && $_GET['id_produit'] != ''){
	if(is_numeric($_GET['id_produit'])){
		$resultat = $pdo -> prepare(
		"SELECT *
		FROM produit p, salle s
		WHERE p.id_salle = s.id_salle
		AND id_produit = $_GET[id_produit]");

		$resultat -> bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
		$resultat -> execute();


		if($resultat -> rowCount() > 0){
			// Si tout est OK, je récupère les infos du produit dans un array $produit
			$produit = $resultat -> fetch(PDO::FETCH_ASSOC);
			// debug($produit);
			extract($produit);
			//debug($produit);

      $resultat = $pdo -> query("SELECT ROUND(AVG(a.note), 0) as note_moyenne FROM avis a, salle s
  		WHERE a.id_salle = s.id_salle
  		AND a.id_salle = $_GET[id_produit]");

      $result = $resultat -> fetch(PDO::FETCH_ASSOC);
      extract($result); // $note_moyenne nous donne la note moyenne
      //debug($result);

      $resultat = $pdo -> query("SELECT * FROM avis a, produit p WHERE a.id_salle = p.id_salle AND p.id_produit = $_GET[id_produit]");
      $nbr_de_note = $resultat -> rowCount();
      //debug($nbr_de_note);

		}
		else{
			// Si l'ID ne correspond à aucun produit en BBD = REDIRECTION
			header('location:index.php');
		}
	}
	else{
		// Si l'ID dans l'URL n'est pas un chiffre = REDIRECTION
		header('location:index.php');
	}
}
else{
	// S'il n'y a pas d'ID dans l'URL ou qu'il est vide = REDIRECTION
	header('location:index.php');
}

require_once('inc/header.inc.php');
?>




  <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="thumbnail">
                    <img class="img-responsive" img src="<?= RACINE_SITE ?>/photo/<?= $photo ?>" style="height:500px;"alt="" >
                    <div class="caption-full">
                        <h4 class="pull-right"><b><?= $prix ?> €</b></h4>
                        <h4><?= $titre ?></h4>
                        <p><strong> <?= $etat ?> </strong></p>
                        <p><?= $adresse ?><br>
													<?= $cp ?>
													<?= $ville ?><br>
													<?= $pays ?>
												</p>
												<p class="pull-left">
													<?= $description ?>
												</p>
                    </div>
                    <div class="ratings">
                        <p><input type="submit" value="Louer cette salle"></p>
                        <p>
                            <span>Note moyenne : <?= $note_moyenne ?> sur <?= $nbr_de_note ?> vote(s)</span><br>
                            <?php for($i=0; $i < 5; $i++) : ?>
                            <?php if($i < $note_moyenne) : ?>
                            <span class="glyphicon glyphicon-star"></span>
                            <?php else : ?>
                            <span class="glyphicon glyphicon-star-empty"></span>
                            <?php endif; ?>
                            <?php endfor; ?>

                        </p>
                    </div>
                </div>

                <div class="well">
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star-empty"></span>
                            <span class="glyphicon glyphicon-star-empty"></span>
                            Anonyme
                            <p>Parfait !</p>
                        </div>
                    </div>

                </div>

                <div class="well">
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div id="map"></div>
                            <input id="address" type="textbox" value="<?= $adresse ?> <?= $cp ?> <?= $ville ?> <?= $pays ?>">
                            <input id="submit" type="button" value="voir le plan">

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- /.container -->

    <?php
     require_once('inc/footer.inc.php')
    ?>
