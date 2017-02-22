<?php
require_once('inc/init.inc.php');



$page = 'Accueil';
require_once('inc/header.inc.php');
?>



<!-- Contenu html -->
<div class="container">
  <div class="row">

      <div class="col-md-3">
          <p class="lead">Categorie</p>
          <div class="list-group">
              <a href="#" class="list-group-item">Reunion</a>
              <a href="#" class="list-group-item">Bureau</a>
          </div>
          <p class="lead">Ville</p>
          <div class="list-group">
              <a href="#" class="list-group-item">Paris</a>
              <a href="#" class="list-group-item">Lyon</a>
              <a href="#" class="list-group-item">Bordeaux</a>
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

              <div class="col-sm-4 col-lg-4 col-md-4">
                  <div class="thumbnail">
                      <img src="http://placehold.it/320x150" alt="">
                      <div class="caption">
                          <h4 class="pull-right">$24.99</h4>
                          <h4><a href="#">First Product</a>
                          </h4>
                          <p>See more snippets like this online store item at <a target="_blank" href="http://www.bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
                      </div>
                      <div class="ratings">
                          <p class="pull-right">15 reviews</p>
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
          </div>
      </div>
  </div>
</div>




<?php
 require_once('inc/footer.inc.php')
?>