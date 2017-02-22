<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lokisalle - Start Bootstrap Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/shop-homepage.css" rel="stylesheet">
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
                      <a <?= ($page == 'Profil') ? 'class="active"' : '' ?> href="<?php echo RACINE_SITE; ?>profil.php">Profil</a>
                    </li>
                    <li>
                    <a href="<?php echo RACINE_SITE; ?>connexion.php?action=deconnexion">Deconnexion</a>
                  </li>
                <?php else : ?>
                  <li>
                  <a <?= ($page == 'Inscription') ? 'class="active"' : '' ?> href="<?php echo RACINE_SITE; ?>inscription.php">Inscription</a>
                </li>
                <li>
                  <a <?= ($page == 'Connexion') ? 'class="active"' : '' ?> href="<?php echo RACINE_SITE; ?>connexion.php">Connexion</a>
                </li>
                <?php endif; ?>
                <?php if(userAdmin()):?>
                  <li>
                  <a <?= ($page == 'Gestion salles') ? 'class="active"' : '' ?> href="<?php echo RACINE_SITE; ?>admin/gestion_salle.php">Gestion Boutique</a>
                </li>
                <li>
                  <a <?= ($page == 'Gestion produits') ? 'class="active"' : '' ?> href="<?php echo RACINE_SITE; ?>admin/gestion_produits.php">Gestion Membres</a>
                </li>
                <li>
                  <a <?= ($page == 'Gestion membres') ? 'class="active"' : '' ?> href="<?php echo RACINE_SITE; ?>admin/gestion_membres.php">Gestion Commandes</a>
                </li>
                <li>
                  <a <?= ($page == 'Gestion avis') ? 'class="active"' : '' ?> href="<?php echo RACINE_SITE; ?>admin/gestion_avis.php">Gestion Commandes</a>
                </li>

                <?php endif; ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-md-3">
                <p class="lead">Catégorie</p>
                <div class="list-group">
                    <a href="#" class="list-group-item">Réunion</a>
                    <a href="#" class="list-group-item">Bureau</a>
                </div>
                  <p class="lead">Ville</p>
                  <div class="list-group">
                      <a href="#" class="list-group-item">Paris</a>
                      <a href="#" class="list-group-item">Lyon</a>
                      <a href="#" class="list-group-item">Bordeau</a>
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
                                    <img class="slide-image" src="../photo/bureau1.jpg" alt="">
                                </div>
                                <div class="item">
                                    <img class="slide-image" src="../photo/salle1.jpg" alt="">
                                </div>
                                <div class="item">
                                    <img class="slide-image" src="../photo/salle2.jpg" alt="">
                                </div>
                                <div class="item">
                                    <img class="slide-image" src="../photo/salle3.jpg" alt="">
                                </div>
                                <div class="item">
                                    <img class="slide-image" src="../photo/bureau3.jpg" alt="">
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
    <!-- /.container -->

    <div class="container">

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Your Website 2014</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
