<?php
require_once('inc/init.inc.php');



//debug($produits);

$req = $pdo -> query("SELECT *
  FROM produit
  LEFT JOIN salle
  ON salle.id_salle = produit.id_salle ");

  if(isset($_GET['action']) && $_GET['action'] == 'reunion'){
    $req = $pdo -> query("SELECT *
		FROM produit p, salle s
		WHERE p.id_salle = s.id_salle
		AND categorie = 'Réunion'");
  }

  if(isset($_GET['action']) && $_GET['action'] == 'bureau'){
    $req = $pdo -> query("SELECT *
		FROM produit p, salle s
		WHERE p.id_salle = s.id_salle
		AND categorie = 'Bureau'");
  }

  if(isset($_GET['action']) && $_GET['action'] == 'paris'){
    $req = $pdo -> query("SELECT *
    FROM produit p, salle s
    WHERE p.id_salle = s.id_salle
    AND ville = 'Paris'");
  }

  if(isset($_GET['action']) && $_GET['action'] == 'lyon'){
    $req = $pdo -> query("SELECT *
    FROM produit p, salle s
    WHERE p.id_salle = s.id_salle
    AND ville = 'Lyon'");
  }

  if(isset($_GET['action']) && $_GET['action'] == 'bordeaux'){
    $req = $pdo -> query("SELECT *
    FROM produit p, salle s
    WHERE p.id_salle = s.id_salle
    AND ville = 'Bordeaux'");
  }

  $resultat = $req ;
  $produits = $resultat -> fetchAll(PDO::FETCH_ASSOC);



require_once('inc/header.inc.php');

?>


<section>
<!-- Contenu html -->
<div class="container">
  <div class="row">

      <div class="col-md-3">
          <p class="lead">Categorie</p>
          <div class="list-group">
            <form method="get" action="">
              <a href="?action=reunion" class="list-group-item">Reunion</a>
              <a href="?action=bureau" class="list-group-item">Bureau</a>
            </form>
          </div>
          <p class="lead">Ville</p>
          <div class="list-group">
            <form method="get" action="">
              <a href="?action=paris" class="list-group-item">Paris</a>
              <a href="?action=lyon" class="list-group-item">Lyon</a>
              <a href="?action=bordeaux" class="list-group-item">Bordeaux</a>
            </form>
          </div>
      </div>

      <div class="col-md-9">

          <div class="row carousel-holder">

              <div class="col-md-12">
                  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                      <ol class="carousel-indicators">
                          <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                          <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                          <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                          <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                          <li data-target="#carousel-example-generic" data-slide-to="4"></li>
                      </ol>
                      <div class="carousel-inner">
                          <div class="item active">
                              <img class="slide-image" src="photo/c1.jpg" alt="">
                          </div>
                          <div class="item">
                              <img class="slide-image" src="photo/c2.jpg" alt="">
                          </div>
                          <div class="item">
                              <img class="slide-image" src="photo/c3.jpg" alt="">
                          </div>
                          <div class="item">
                              <img class="slide-image" src="photo/c4.jpg" alt="">
                          </div>
                          <div class="item">
                              <img class="slide-image" src="photo/c5.jpg" alt="">
                          </div>
                      </div>
                      <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                          <span class="glyphicon glyphicon-chevron-left"></span>
                      </a>
                      <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                          <span class="glyphicon glyphicon-chevron-right"></span>
                      </a>
                  </div>
              </div>

          </div>

          <div class="row">
            <?php foreach($produits as $valeur) : ?>
              <div class="col-sm-4 col-lg-4 col-md-4">
                  <div class="thumbnail">
                      <a href="page_produit.php?id_produit=<?= $valeur['id_produit'] ?>"><img src="<?= RACINE_SITE ?>/photo/<?= $valeur['photo'] ?> " style="height:140px" >
                      <div class="caption">
                          <h4 class="pull-right"><?= $valeur['prix'] ?>€</h4>
                          <h4><a href="page_produit.php?id_produit=<?= $valeur['id_produit'] ?>"><?= $valeur['titre'] ?></a></h4>
                          <p><?= substr($valeur['description'], 0, 50) ?>...</p>
                          <p>disponible le <?= $valeur['date_arrivee'] ?><br>
                            <?= $valeur['ville'] ?>
                          </p>
                      </div>
                      <div class="ratings">
                          <p class="pull-right"><?= $valeur['categorie'] ?></p>
                          <p>
                              <span class="glyphicon glyphicon-star"></span>
                              <span class="glyphicon glyphicon-star"></span>
                              <span class="glyphicon glyphicon-star"></span>
                              <span class="glyphicon glyphicon-star"></span>
                              <span class="glyphicon glyphicon-star"></span>
                          </p>
                      </div>
                  </div>
              </div>
              <?php endforeach; ?>
          </div>
      </div>

  </div>

</div>
</section>



<?php
 require_once('inc/footer.inc.php')
?>
