<?php
require_once('inc/init.inc.php');

if(!userAdmin()){
	header('location:connexion.php');
}


require_once('inc/header.inc.php');
?>




  <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="thumbnail">
                    <img class="img-responsive" src="<?= RACINE_SITE ?>/img/cover.jpg" alt="">
                    <div class="caption-full">
                        <h4><a href="#">Bienvenue</a>
                        </h4>
                        </p>
                        <p style="font-size: 14px;">Lokisalle est la plus grande plateforme de réservation en ligne de salles de réunion en Europe. Avec plus de 32 000 salles, ce marché unique et multilingue propose tous types de salles à la location, que ce soit une petite salle de réunion confortable dans un hôtel, une salle de formation de taille moyenne dans un espace de coworking ou une grande salle de conférence dans l’un de nos plus grands centres d’affaires. Rapide et facile d'utilisation, notre outil vous donne la possibilité de trouver une salle rapidement, de comparer des offres équivalentes en temps réel, et, de réserver l’espace de votre choix sans frais supplémentaires. Ne tardez donc plus et commencez à parcourir notre catalogue dès maintenant !</p>

												<p style="font-size: 14px;">
													Les entreprises à la recherche d'un bureau à louer peuvent trouver l'espace de travail lokisalle adapté à leurs préférences, à l’adresse de leur choix et pour la durée de leur choix. Notre réseau mondial d’espaces de travail peut s'adapter à votre budget et à la taille de vos équipes.
												</p>


												<p style="font-size: 14px;">
													Réserver une salle de réunion devrait être simple.

Pour cette raison, notre volonté est de vous faciliter la tâche pour que vous puissiez vous concentrer sur ce qui importe vraiment : votre business. Notre but est de rassembler, en un seul lieu, l'offre et la demande afin de fournir une plateforme transparente et flexible où vos choix sont faits en toute connaissance de cause. Après tout, il s'agit de vous offrir l'outil le plus simple et le plus rapide !


													Vous souhaitez organiser un événement de plus grande envergure ?

N'hésitez pas à contacter notre équipe. Remplissez notre formulaire de demande d'offres en y inscrivant le maximum de détails concernant votre événement. Nous vous recontacterons dans les plus brefs délais !
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
