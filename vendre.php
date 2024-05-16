<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Traitement du formulaire de filtrage
if(isset($_POST['filter'])) {
    $filter_query = "SELECT * FROM tblvehiclesvendre WHERE 1";
    
    // Filtre par nom
    if(!empty($_POST['nom'])) {
        $nom = implode("','", $_POST['nom']);
        $filter_query .= " AND VehiclesTitle IN ('$nom')";
    }
    
    // Filtre par prix (minimum et maximum)
    $min_price = isset($_POST['min_price']) ? $_POST['min_price'] : 0;
    $max_price = isset($_POST['max_price']) ? $_POST['max_price'] : PHP_INT_MAX;
    $filter_query .= " AND prix BETWEEN $min_price AND $max_price";
    
    // Filtre par marque
    if(!empty($_POST['marque'])) {
        $marque = implode("','", $_POST['marque']);
        $filter_query .= " AND VehiclesBrand IN ('$marque')";
    }
    
    // Filtre par carburant
    if(!empty($_POST['carburant'])) {
        $carburant = implode("','", $_POST['carburant']);
        $filter_query .= " AND FuelType IN ('$carburant')";
    }
    
    // Filtre par année
    if(!empty($_POST['annee'])) {
        $annee = implode("','", $_POST['annee']);
        $filter_query .= " AND ModelYear IN ('$annee')";
    }
    
    // Filtre par capacité
    if(!empty($_POST['capacite'])) {
        $capacite = implode("','", $_POST['capacite']);
        $filter_query .= " AND SeatingCapacity IN ('$capacite')";
    }
    
    // Exécuter la requête de filtrage
    $stmt = $dbh->prepare($filter_query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);
} else {
    // Sélectionner toutes les données de la base de données si aucun filtre n'est appliqué
    $sql = "SELECT *
    FROM tblvehiclesvendre 
    JOIN tblbrands ON tblbrands.id = tblvehiclesvendre.VehiclesBrand";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
    include "lo.php";
    ?>
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    


    <!-- Spinner End -->


    <!-- Topbar Start -->
   


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
  <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
    <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>CarServ</h2>
  </a>

  <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarCollapse">
    <div class="navbar-nav ms-auto p-4 p-lg-0">
      <a href="index.php" class="nav-item nav-link ">Accueil</a>
      <a href="about.php" class="nav-item nav-link">À propos</a>

      <div class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
        <div class="dropdown-menu fade-up m-0">
          <a href="location.php" class="dropdown-item">Location</a>
          <a href="vendre.php" class="dropdown-item active">Vendre</a>
        </div>
      </div>

      <a href="contact.php" class="nav-item nav-link ">Contact</a>
     










      <?php
// Récupérer les produits dans le panier pour l'utilisateur connecté
$sql = "SELECT tblvehicles.VehiclesTitle, tblvehicles.PricePerDay, tblvehicles.Vimage1 FROM tblvehicles INNER JOIN cart ON tblvehicles.id = cart.product_id WHERE cart.user_id = :user_id";
$query = $dbh->prepare($sql);
$query->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$query->execute();
$cartProducts = $query->fetchAll(PDO::FETCH_ASSOC);
?>
          <li class="nav-link dropdown" style="list-style: none;">
          <i class="fa fa-shopping-cart" style="color:#1613d8;"></i>

            <a href="#" data-bs-toggle="dropdown" aria-expanded="false">


              <i class="fa fa-angle-down" aria-hidden="true"></i>
            </a>
            <ul class="dropdown-menu" style="  list-style: none;   margin-left: -150px;width: 250px;">
            <li class="header" style="font-size:12px;list-style: none;">Vous avez <span class="cart_count"><?php echo count($cartProducts); ?></span> articles dans le panier</li>
<br>
<?php foreach ($cartProducts as $cartProduct) : ?>

            <ul class="menu" style="list-style: none;    margin-left: -30px;" id="cart_menu">
            <div class="row align-items-center">
                <div class="col-4">
                    <img src="../admin/img/vehicleimages/<?php echo $cartProduct['Vimage1']; ?>" alt="<?php echo $cartProduct['VehiclesTitle']; ?>" class="img-fluid">
                </div>
                <div class="col-8">
                    <p><?php echo $cartProduct['VehiclesTitle']; ?></p>
                    <p><?php echo $cartProduct['PricePerDay']; ?> DT / Jour</p>
                </div>
                
            </div>
                </ul>
              </li>
              <br> <?php endforeach; ?>
              <li class="footer" style="background:#1613d8;list-style: none;"><a href="cart_view.php" style="color:white;">Aller au panier</a></li>
            
            </ul>
          </li>

    </div>

    <div class="header_wrap">
      <div class="user_login" style="    margin-top: 15px;    margin-left: -50px;">
        <ul>
        <?php

// Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit(); // Toujours arrêter l'exécution après une redirection
}

// Inclure le fichier de configuration pour récupérer les données de l'utilisateur

// Récupérer les détails de l'utilisateur connecté
$sql = "SELECT FullName, ProfileImage FROM tblusers WHERE id = :user_id";
$query = $dbh->prepare($sql);
$query->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

if ($query->execute() && $query->rowCount() > 0) {
    $user = $query->fetch(PDO::FETCH_ASSOC);
} else {
    // Si l'utilisateur n'est pas trouvé, détruire la session et rediriger vers la page de connexion
    session_destroy();
    header('Location: login.php');
    exit();
}

?>
          <li class="nav-link dropdown">
            <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
            <?php if ($user['ProfileImage']): ?>
                <img src="../<?php echo htmlspecialchars($user['ProfileImage']); ?>" alt="Image de profil" style="width: 30px; height: 30px; border-radius: 50%;">
            <?php else: ?>
                <img src="assets/img/default-profile.png" alt="Image de profil par défaut" style="width: 30px; height: 30pxx; border-radius: 50%;">
            <?php endif; ?>
            <?php echo htmlspecialchars($user['FullName']); ?>

              <i class="fa fa-angle-down" aria-hidden="true"></i>
            </a>
            <ul class="dropdown-menu" style="    margin-left: -100px;">
                <li><a href="profile.php" class="dropdown-item">Paramètres du profil</a></li>
                <li><a href="update-password.php" class="dropdown-item ">Changer mot de passe</a></li>
                <li><a href="my-booking.php" class="dropdown-item ">Mes réservations</a></li>
                <li><a href="my-ventes.php" class="dropdown-item">Mes ventes</a></li>
                <li><a href="post-testimonial.php" class="dropdown-item">Publier un témoignage</a></li>
                <li><a href="my-testimonials.php" class="dropdown-item">Mes témoignages</a></li>
                <li><a href="logout.php" class="dropdown-item">Se déconnecter</a></li>
             
            
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
    <!-- Navbar End -->

<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 p-0" style="background-image: url(./img/aze.jpg);">
    <div class="container-fluid page-header-inner py-5">
        <div class="container text-center">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Vendre</h1>
        </div>
    </div>
</div>
<!-- Page Header End -->
<!-- Page Header Content -->
<section class='section-padding gray-bg' style='margin-top: -100px;'>
    <div class='container'>
        <div class='section-header text-center' style="margin-top: 130px;">
            <h2>Trouvez la meilleure <span>vendre de voiture</span></h2>
            <p>Carserv facilite la recherche de voitures de luxe en vente à des prix abordables. Notre plateforme offre une sélection variée de véhicules haut de gamme pour répondre à tous les besoins et budgets.</p>
        </div>
        <main>
            <div class="container">
                <div class="row">
                    <!-- Section des filtres -->
                    <div class="col-md-3">
                        <!-- Formulaire de filtrage -->
                        <form method="POST" action="filtervendre.php">
                        <h4>Filtre</h4>
                        <hr>
                        <!-- Filtre de prix -->
                        <div class="filter-element">
                            <h6>Prix</h6>
                            <?php
                            $price_query = "SELECT MAX(prix) AS max_price, MIN(prix) AS min_price FROM tblvehiclesvendre";
                            $price_result = $dbh->query($price_query);
                            $price_row = $price_result->fetch(PDO::FETCH_ASSOC);
                            $min_price = $price_row['min_price'];
                            $max_price = $price_row['max_price'];
                            ?>
                            <input type="range" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="20" id="priceSlider" name="max_price">
                            <div id="price"><h6><?php echo $min_price; ?></h6><h6 style="margin-top: -27px;margin-left: 94px;"><?php echo $max_price; ?></h6></div>

                        </div>
                        <!-- Autres filtres -->
                        <div class='filter-element'>
                            <h6>Nom de voiture</h6>
                            <!-- Sélection des noms depuis la base de données -->
                            <?php
                            $nom_query = "SELECT DISTINCT VehiclesTitle FROM tblvehiclesvendre";
                            $nom_stmt = $dbh->prepare($nom_query);
                            $nom_stmt->execute();
                            $noms = $nom_stmt->fetchAll(PDO::FETCH_COLUMN);
                            foreach($noms as $nom) {
                                echo "<div class='form-check'>";
                                echo "<input type='checkbox' class='form-check-input product-check' id='$nom' name='nom[]' value='$nom'>";
                                echo "<label class='form-check-label' for='$nom'>$nom</label>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                       
                        <div class='filter-element'>
                            <h6>Nom de carburant</h6>
                            <!-- Sélection des noms depuis la base de données -->
                            <?php
                            $carburant_query = "SELECT DISTINCT FuelType FROM tblvehiclesvendre";
                            $carburant_stmt = $dbh->prepare($carburant_query);
                            $carburant_stmt->execute();
                            $carburants = $carburant_stmt->fetchAll(PDO::FETCH_COLUMN);
                            foreach($carburants as $carburant) {
                                echo "<div class='form-check'>";
                                echo "<input type='checkbox' class='form-check-input product-check' id='$carburant' name='carburant[]' value='$carburant'>";
                                echo "<label class='form-check-label' for='$carburant'>$carburant</label>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <div class='filter-element'>
                            <h6>Année de modèle</h6>
                            <!-- Sélection des noms depuis la base de données -->
                            <?php
                            $annee_query = "SELECT DISTINCT ModelYear FROM tblvehiclesvendre";
                            $annee_stmt = $dbh->prepare($annee_query);
                            $annee_stmt->execute();
                            $annees = $annee_stmt->fetchAll(PDO::FETCH_COLUMN);
                            foreach($annees as $annee) {
                                echo "<div class='form-check'>";
                                echo "<input type='checkbox' class='form-check-input product-check' id='$annee' name='annee[]' value='$annee'>";
                                echo "<label class='form-check-label' for='$annee'>$annee</label>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <div class='filter-element'>
                            <h6>Capacité de voiture</h6>
                            <!-- Sélection des noms depuis la base de données -->
                            <?php
                            $capacite_query = "SELECT DISTINCT SeatingCapacity FROM tblvehiclesvendre";
                            $capacite_stmt = $dbh->prepare($capacite_query);
                            $capacite_stmt->execute();
                            $capacites = $capacite_stmt->fetchAll(PDO::FETCH_COLUMN);
                            foreach($capacites as $capacite) {
                                echo "<div class='form-check'>";
                                echo "<input type='checkbox' class='form-check-input product-check' id='$capacite' name='capacite[]' value='$capacite'>";
                                echo "<label class='form-check-label' for='$capacite'>$capacite</label>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <!-- Autres filtres (Marque, Carburant, Année, Capacité) -->
                        <!-- Ajoutez le code similaire pour les autres filtres -->
                        <div class="filter-element">
                            <!-- Autres filtres -->
                        </div>
                   
                </div>
            </form>
            <!-- Section des produits -->
            <div class="col-md-9" id="result">
                <!-- Affichage des résultats -->
                <?php
                // Boucle pour afficher deux produits par ligne
                for ($i = 0; $i < count($results); $i += 2) {
                    echo "<div class='row' style='--bs-gutter-x: 4rem;'>";
                    for ($j = $i; $j < min($i + 2, count($results)); $j++) {
                        $result = $results[$j];
                        echo "<div class='col-md-6' style='width: 50%;>";
                        echo "<div class='recent-car-list'>";
                        echo "<div class='car-info-box'>";
                        echo "<a href='detailsvendre.php?vhid=" . $result->id . "''> <img src='../admin/img/vehicleimages/" . $result->Vimage1 . "' alt='Image de véhicule' class='img-responsive'></a>";
                        echo "<ul>";
                        echo "<li><i class='fa fa-car' aria-hidden='true'></i> " . $result->FuelType . "</li>";
                        echo "<li><i class='fa fa-calendar' aria-hidden='true'></i> " . $result->ModelYear . " Model</li>";
                        echo "<li><i class='fa fa-user' aria-hidden='true'></i> " . $result->SeatingCapacity . " seats</li>";
                        echo "</ul>"; // fin de la liste

                        echo "</div>"; // car-info-box

                        echo "<div class='car-title-m'>";
                        echo "<h6><a href='detailsvendre.php?vhid=" . $result->id . "'>";
                        echo $result->BrandName . ", " . $result->VehiclesTitle;
                        echo "</a></h6>";
                        echo "<span class='price'>" . $result->prix . " DT / Jour</span>";
                        echo "</div>"; // car-title-m

                        echo "<div class='inventory_info_m'>";
                        echo "<p style='color: #343a40;'>" . $result->VehiclesOverview . "...</p>";
                        echo"<a href='cart.php'> <button type='submit' name='add_to_cart' style='background-color: #1613d8;color: white;border-color: #1613d8;'><i class='fa fa-shopping-cart'></i> Ajouter au panier</button></a>";

                        echo "</div>"; // inventory_info_m

                        echo "</div>"; // recent-car-list
                        echo "</div>"; // col-md-6
                    }
                    echo "</div>"; // row
                }
                ?>
            </div>
        </div>
    </div>
</main>
</div>
</section>

<?php
include "footer.php";
?>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/counterup/counterup.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="lib/tempusdominus/js/moment.min.js"></script>
<script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
<script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>
<!-- Script de filtrage -->
<script>
$(document).ready(function() {
function filtrer() {
    var nom = [];
    $("input[name='nom[]']:checked").each(function() {
        nom.push($(this).val());
    });

    var min_price = $("#priceSlider").val();
    var max_price = $("#priceSlider").attr("max");

    var carburant = [];
    $("input[name='carburant[]']:checked").each(function() {
        carburant.push($(this).val());
    });

    var annee = [];
    $("input[name='annee[]']:checked").each(function() {
        annee.push($(this).val());
    });

    var capacite = [];
    $("input[name='capacite[]']:checked").each(function() {
        capacite.push($(this).val());
    });

    $.ajax({
        url: "filtervendre.php",
        method: "POST",
        data: {
            nom: nom,
            min_price: min_price,
            max_price: max_price,
            carburant: carburant,
            annee: annee,
            capacite: capacite
        },
        success: function(data) {
            $("#result").html(data);
        }
    });
}

// Met à jour le filtrage lorsqu'un changement est détecté sur les cases à cocher ou le curseur de prix
$("input[type='checkbox'], input[type='range']").change(filtrer);

// Appelle la fonction de filtrage une première fois au chargement de la page
filtrer();
});

</script>
</body>
</html>


