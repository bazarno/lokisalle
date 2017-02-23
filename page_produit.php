<?php
require_once('inc/init.inc.php');

if(!userAdmin()){
	header('location:connexion.php');
}

if(isset($_GET['id_produit']) && $_GET['id_produit'] != ''){
	if(is_numeric($_GET['id_produit'])){
		$resultat = $pdo -> prepare("SELECT *
      FROM produit
      LEFT JOIN salle
      ON salle.id_salle = produit.id_salle
      ");
      debug($resultat);
		$resultat -> bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
		$resultat -> execute();

		if($resultat -> rowCount() > 0){
			// Si tout est OK, je récupère les infos du produit dans un array $produit
			$produit = $resultat -> fetch(PDO::FETCH_ASSOC);
			// debug($produit);
			extract($produit);
      /*
      $resultat = $pdo -> query("SELECT ROUND(AVG(note), 0) as note_moyenne FROM avis WHERE id_produit = $id_produit");
      $result = $resultat -> fetch(PDO::FETCH_ASSOC);
      extract($result); // $note_moyenne nous donne la note moyenne

      $resultat = $pdo -> query("SELECT * FROM avis WHERE id_produit = $id_produit");
      $nbr_de_note = $resultat -> rowCount();
      */
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
                    <img class="img-responsive" src="http://placehold.it/800x300" alt="">
                    <div class="caption-full">
                        <h4 class="pull-right">$24.99</h4>
                        <h4><a href="#">Product Name</a>
                        </h4>
                        <p>See more snippets like these online store reviews at <a target="_blank" href="http://bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
                        <p>Want to make these reviews work? Check out
                            <strong><a href="http://maxoffsky.com/code-blog/laravel-shop-tutorial-1-building-a-review-system/">this building a review system tutorial</a>
                            </strong>over at maxoffsky.com!</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                    </div>
                    <div class="ratings">
                        <p class="pull-right">3 reviews</p>
                        <p>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star-empty"></span>
                            4.0 stars
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
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star-empty"></span>
                            Anonymous
                            <span class="pull-right">10 days ago</span>
                            <p>This product was great in terms of quality. I would definitely buy another!</p>
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
