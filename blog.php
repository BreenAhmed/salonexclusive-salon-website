<?php
require_once 'config/database.php';

// Get published blog posts from database
try {
    $blog_posts = getPublishedBlogPosts();
    if (!is_array($blog_posts)) {
        $blog_posts = [];
    }
} catch (Exception $e) {
    error_log("Error fetching blog posts: " . $e->getMessage());
    $blog_posts = [];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Blogg - Salon Exclusive</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="cx.png">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Barlow+Condensed:500,600,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">

    
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
      .blog-entry .img {
        height: 250px;
        width: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 8px 8px 0 0;
        transition: transform 0.3s ease;
        object-fit: cover;
      }
      
      .blog-entry:hover .img {
        transform: scale(1.05);
      }
      
      .blog-entry {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 30px;
        height: 450px;
        display: flex;
        flex-direction: column;
        width: 100%;
      }
      
      .blog-entry:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      }
      
      .blog-entry .text {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }
      
      .blog-entry .meta {
        margin-bottom: 15px;
      }
      
      .blog-entry .meta div {
        display: inline-block;
        margin-right: 15px;
        font-size: 14px;
        color: #666;
      }
      
      .blog-entry .heading {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 15px;
        line-height: 1.4;
      }
      
      .blog-entry .heading a {
        color: #2c3e50;
        text-decoration: none;
      }
      
      .blog-entry .heading a:hover {
        color: #F96D00;
      }
      
      .blog-entry .content {
        flex: 1;
      }
      
      .blog-entry p {
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
      }
      
      .blog-entry .read-more {
        margin-top: auto;
        padding-top: 15px;
      }
      
      .btn-custom {
        color: #F96D00;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
      }
      
      .btn-custom:hover {
        color: #e55a00;
        text-decoration: none;
      }
      
      /* Overflow prevention */
      .blog-entry p {
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
      }
      
      .blog-entry .heading {
        word-wrap: break-word;
        overflow-wrap: break-word;
      }
      
      .blog-entry .heading a {
        word-wrap: break-word;
        overflow-wrap: break-word;
      }
      
      /* Ensure all blog entries in the grid have consistent sizing */
      .row .col-md-6.col-lg-4 {
        display: flex;
        margin-bottom: 30px;
      }
      
      .row .col-md-6.col-lg-4 .blog-entry {
        width: 100%;
        margin-bottom: 0;
      }
      
      
      .ftco-footer p a {
        color: #ff6b6b !important;
        text-decoration: none !important;
        transition: all 0.3s ease !important;
        font-weight: 600 !important;
      }
      
      .ftco-footer p a:hover {
        color: #ff5252 !important;
        text-decoration: underline !important;
        transform: scale(1.05) !important;
      }
    </style>
  </head>
  <body>
	  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="index.php"><img src="cx.png" alt="Logo" style="height: 100px;"></a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	        	<li class="nav-item"><a href="index.php" class="nav-link">Hjem</a></li>
	        	<li class="nav-item"><a href="reborn.php" class="nav-link">Re-born</a></li>
	        	<li class="nav-item"><a href="stas.php" class="nav-link">STAS</a></li>
	        	<li class="nav-item active"><a href="blog.php" class="nav-link">Blogg</a></li>
	          <li class="nav-item"><a href="contact.php" class="nav-link">Kontakt</a></li>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->

    <section class="hero-wrap hero-wrap-2" style="background-image: url('hero_blog.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate pb-5 text-center">
          </div>
        </div>
      </div>
    </section>
    
    <!-- Blog Introduction Section -->
    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-10 text-center ftco-animate">
            <div class="heading-section mb-5">
              <span class="subheading">Blogg</span>
              <h2 class="mb-4">Hårpleie Tips og Råd</h2>
              <p>Oppdag de nyeste trendene innen hårpleie, profesjonelle tips fra våre eksperter, og få innsikt i hvordan du kan oppnå det perfekte håret hjemme.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Blog Posts Section -->
    <section class="ftco-section bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="row">
              <?php
              // Display blog posts from database
              if (!empty($blog_posts)) {
                  foreach ($blog_posts as $index => $post) {
                      $delay = $index * 0.1;
                      $formatted_date = isset($post['created_at']) ? date('j. M Y', strtotime($post['created_at'])) : '';
                      $featured_image = !empty($post['featured_image']) ? htmlspecialchars($post['featured_image']) : 'images/work-1.jpg';
                      $author = isset($post['author']) ? htmlspecialchars($post['author']) : 'Unknown';
                      $title = isset($post['title']) ? htmlspecialchars($post['title']) : 'Untitled';
                      $post_id = isset($post['id']) ? intval($post['id']) : 0;
                      
                      echo '<div class="col-md-6 col-lg-4 ftco-animate" style="animation-delay: ' . $delay . 's;">';
                      echo '<div class="blog-entry">';
                      echo '<div class="img" style="background-image: url(' . $featured_image . ');"></div>';
                      echo '<div class="text">';
                      echo '<div class="content">';
                      echo '<div class="meta">';
                      echo '<div><span class="icon-calendar"></span> ' . $formatted_date . '</div>';
                      echo '<div><span class="icon-person"></span> ' . $author . '</div>';
                      echo '</div>';
                      echo '<h3 class="heading"><a href="blog-single.php?id=' . $post_id . '">' . $title . '</a></h3>';
                      echo '</div>';
                      echo '<div class="read-more">';
                      echo '<p><a href="blog-single.php?id=' . $post_id . '" class="btn-custom">Les mer <span class="ion-ios-arrow-round-forward"></span></a></p>';
                      echo '</div>';
                      echo '</div>';
                      echo '</div>';
                      echo '</div>';
                  }
              } else {
                  echo '<div class="col-12 text-center">';
                  echo '<p>Ingen blogginnlegg tilgjengelig for øyeblikket.</p>';
                  echo '</div>';
              }
              ?>
            </div>
            
          </div>
        </div>
      </div>
    </section>


    <footer class="ftco-footer ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2 logo"><img src="cx.png" alt="Salon Exclusive" style="height: 100px; filter: brightness(0) invert(1);"></h2>
              <p>Børre leverer kvalitet og personlig service med erfaring som styrker Salon Exclusives visjon løfter.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                <li class="ftco-animate"><a href="https://www.instagram.com/salonexclusive_/"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
             <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Lenker</h2>
              <ul class="list-unstyled">
                <li><a href="index.php" class="py-2 d-block">Hjem</a></li>
                <li><a href="reborn.php" class="py-2 d-block">Re-born</a></li>
                <li><a href="stas.php" class="py-2 d-block">STAS</a></li>
                <li><a href="blog.php" class="py-2 d-block">Blogg</a></li>
                <li><a href="contact.php" class="py-2 d-block">Kontakt</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2">Har du spørsmål?</h2>
            	<div class="block-23 mb-3">
	              <ul>
	                <li><span class="icon icon-map-marker"></span><span class="text">Svelvikveien 61 D, 3039 Drammen</span></li>
	                <li><a href="tel:+4797190030"><span class="icon icon-phone"></span><span class="text">+47 971 90 030</span></a></li>
	                <li><a href="mailto:borre@salonexclusive.no"><span class="icon icon-envelope"></span><span class="text">borre@salonexclusive.no</span></a></li>
	              </ul>
	            </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">

            <p>© 2025 <a href="https://www.brezzo.co" target="_blank">Brezzo</a>. Alle rettigheter forbeholdt.</p>
          </div>
        </div>
      </div>
    </footer>
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
    
  </body>
</html>
