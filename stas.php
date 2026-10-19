<?php
require_once 'config/database.php';

// Get STAS products from database
$stas_products = getProductsByCategory('stas');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>STAS Produkter - Salon Exclusive</title>
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
    .products-showcase {
      position: relative;
      padding: 40px 0;
      overflow: hidden;
    }
    
    .products-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      padding: 20px 0;
    }
    
    .product-card {
      background: white;
      border: 1px solid #e0e0e0;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: all 0.3s ease;
      position: relative;
      display: flex;
      flex-direction: column;
      height: 450px;
    }
    
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .card-header {
      position: relative;
      height: 250px;
      overflow: hidden;
      flex-shrink: 0;
    }
    
    .product-image {
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      transition: transform 0.4s ease;
    }
    
    .product-card:hover .product-image {
      transform: scale(1.1);
    }
    
    
    
    .card-body {
      padding: 25px;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 150px;
    }
    
    .product-name {
      font-size: 22px;
      font-weight: 800;
      color: #2c3e50;
      margin-bottom: 15px;
      line-height: 1.4;
      min-height: 60px;
      display: flex;
      align-items: center;
    }
    
    .product-desc {
      display: none;
    }
    
    .product-read-more {
      margin-top: auto;
      padding-top: 15px;
    }
    
    .btn-custom {
      color: #bf925b;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      display: inline-block;
    }
    
    .btn-custom:hover {
      color: #a67c52;
      text-decoration: none;
    }
    
    .btn-custom .ion-ios-arrow-round-forward {
      margin-left: 5px;
      transition: transform 0.3s ease;
    }
    
    .btn-custom:hover .ion-ios-arrow-round-forward {
      transform: translateX(5px);
    }
    
    /* Modal styles for full description */
    .description-modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      backdrop-filter: blur(5px);
    }
    
    .modal-content {
      background-color: white;
      margin: 5% auto;
      padding: 0;
      border-radius: 12px;
      width: 90%;
      max-width: 600px;
      max-height: 80vh;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      animation: modalSlideIn 0.3s ease-out;
    }
    
    @keyframes modalSlideIn {
      from {
        opacity: 0;
        transform: translateY(-50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .modal-header {
      background: linear-gradient(135deg, #bf925b 0%, #a67c52 100%);
      color: white;
      padding: 20px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .modal-header h3 {
      margin: 0;
      font-size: 24px;
      font-weight: 700;
      color: white;
    }
    
    .close-modal {
      background: none;
      border: none;
      color: white;
      font-size: 28px;
      cursor: pointer;
      padding: 0;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: background-color 0.3s ease;
    }
    
    .close-modal:hover {
      background-color: rgba(255,255,255,0.2);
    }
    
    .modal-body {
      padding: 30px;
      max-height: 60vh;
      overflow-y: auto;
    }
    
    .modal-description {
      font-size: 16px;
      line-height: 1.8;
      color: #333;
      margin: 0;
    }
    
    .card-footer {
      padding: 0;
      display: none;
    }
    
    
    
    
    .featured-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: linear-gradient(135deg, #bf925b 0%, #a67c52 100%);
      color: white;
      padding: 4px 12px;
      border-radius: 15px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 2px 8px rgba(191, 146, 91, 0.3);
      z-index: 2;
    }
    
    
    /* Responsive design */
    @media (max-width: 1200px) {
      .products-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }
    
    @media (max-width: 768px) {
      .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
      }
      
      .card-body {
        padding: 20px;
      }
      
      .card-footer {
        display: none;
      }
      
      .price-section {
        width: 100%;
        text-align: center;
      }
    }
    
    @media (max-width: 480px) {
      .products-grid {
        grid-template-columns: 1fr;
      }
    }
    
    .hero-video {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: -2;
    }
    
    .hero-wrap {
      position: relative;
      overflow: hidden;
    }
    
    .hero-wrap .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: -1;
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
	        	<li class="nav-item active"><a href="stas.php" class="nav-link">STAS</a></li>
	        	<li class="nav-item"><a href="blog.php" class="nav-link">Blogg</a></li>
	          <li class="nav-item"><a href="contact.php" class="nav-link">Kontakt</a></li>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->

    <section class="hero-wrap hero-wrap-2" data-stellar-background-ratio="0.5">
      <video id="heroVideo" muted loop playsinline class="hero-video">
        <source src="file.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate pb-5 text-center">
          </div>
        </div>
      </div>
    </section>
    
    <section class="ftco-section">
    	<div class="container">
    		<div class="row justify-content-center pb-3">
          <div class="col-md-10 heading-section text-center ftco-animate">
          	<span class="subheading">STAS Produkter</span>
            <h2 class="mb-4">Enkelt. Bærekraftig. Og veldig stas.</h2>
          </div>
        </div>
        <div class="row">
        	<div class="col-md-12 ftco-animate">
        		<div class="text">
        			<p>Trenger verden flere hårprodukter? Nei, det er færre og bedre hårprodukter vi trenger. Hårprodukter av høy kvalitet som gjør det de lover.</p>
        			
        			<p>Idéen bak Stas ble født da jeg ryddet i mine egne baderomshyller. Utallige produkter så spesialiserte og kompliserte å bruke at de for folk flest blir ubrukelige. Du kjenner deg kanskje igjen? Sånn burde det jo ikke være.</p>
        			
        			<p>Tenk om du kunne erstattet alt som står i hyllene i dusjen og på badet ditt med noen få, enkle produkter du faktisk kan bruke? Og som dekker 99% av behovene dine? Hadde ikke det vært stas?</p>
        			
        			<p>Nå er denne serien her. Jeg har kalt den Stas Products. Min egen hårlinje.</p>
        			
        			<p><strong>Enkelt. Bærekraftig. Og veldig stas.</strong></p>
        		</div>
        	</div>
        </div>
    	</div>
    </section>

    <section class="ftco-section bg-light">
    	<div class="container">
    		<div class="row justify-content-center pb-3">
          <div class="col-md-10 heading-section text-center ftco-animate">
          	<span class="subheading">STAS Produkter</span>
            <h2 class="mb-4">Våre Produkter</h2>
            <p>Utforsk vårt enkle og effektive utvalg av STAS hårprodukter</p>
          </div>
        </div>
        <div class="row">
        	<div class="col-md-12">
        		<div class="products-showcase">
        		<div class="products-grid">
        				<?php
        				// Display STAS products from database
        				if (!empty($stas_products)) {
        				    foreach ($stas_products as $index => $product) {
        				        $featuredClass = $product['featured'] ? 'featured' : '';
        				        $delay = $index * 0.15;
        				        $description = htmlspecialchars($product['description']);
        				        
					echo '<div class="product-card" style="animation-delay: ' . $delay . 's; cursor: pointer;" onclick="window.location.href=\'product.php?id=' . intval($product['id']) . '\'">';
        						echo '<div class="card-header">';
        						echo '<a href="product.php?id=' . intval($product['id']) . '" style="display:block; width:100%; height:100%;">';
        						echo '<div class="product-image" style="background-image: url(' . htmlspecialchars($product['image']) . ');"></div>';
        						echo '</a>';
        				        if ($product['featured']) {
        				            echo '<div class="featured-badge">Favoritt</div>';
        				        }
        				        echo '</div>';
        				        echo '<div class="card-body">';
        						echo '<h4 class="product-name"><a href="product.php?id=' . intval($product['id']) . '" style="color: inherit; text-decoration: none;">' . htmlspecialchars($product['name']) . '</a></h4>';
        				        echo '<p class="product-desc" id="desc-' . $index . '">' . $description . '</p>';
        				        echo '<div class="product-read-more"><a href="product.php?id=' . intval($product['id']) . '" class="btn-custom" onclick="event.stopPropagation();">Les mer <span class="ion-ios-arrow-round-forward"></span></a></div>';
        				        echo '</div>';
        echo '<div class="card-footer"></div>';
        				        echo '</div>';
        				    }
        				} else {
        				    echo '<div class="col-12 text-center">';
        				    echo '<p>Ingen STAS produkter tilgjengelig for øyeblikket.</p>';
        				    echo '</div>';
        				}
        				?>
        			</div>
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
    
  

  <!-- Description Modal -->
  <div id="descriptionModal" class="description-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="modalTitle">Produktbeskrivelse</h3>
        <button class="close-modal" onclick="closeDescriptionModal()">&times;</button>
      </div>
      <div class="modal-body">
        <p class="modal-description" id="modalDescription"></p>
      </div>
    </div>
  </div>

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
  
  <script>
    // Open description modal
    function openDescriptionModal(index, productName, description) {
      const modal = document.getElementById('descriptionModal');
      const modalTitle = document.getElementById('modalTitle');
      const modalDescription = document.getElementById('modalDescription');
      
      modalTitle.textContent = productName;
      modalDescription.textContent = description;
      modal.style.display = 'block';
      
      // Prevent body scroll when modal is open
      document.body.style.overflow = 'hidden';
    }
    
    // Close description modal
    function closeDescriptionModal() {
      const modal = document.getElementById('descriptionModal');
      modal.style.display = 'none';
      
      // Restore body scroll
      document.body.style.overflow = 'auto';
    }
    
    // Close modal when clicking outside of it
    window.onclick = function(event) {
      const modal = document.getElementById('descriptionModal');
      if (event.target === modal) {
        closeDescriptionModal();
      }
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeDescriptionModal();
      }
    });
    
    // Mobile video autoplay fix
    document.addEventListener('DOMContentLoaded', function() {
      const video = document.getElementById('heroVideo');
      
      if (video) {
        // Try to play the video
        const playPromise = video.play();
        
        if (playPromise !== undefined) {
          playPromise.then(() => {
            // Video started playing successfully
            console.log('Video autoplay started');
          }).catch(error => {
            // Autoplay failed, likely due to mobile browser restrictions
            console.log('Autoplay failed:', error);
            
            // Add a play button overlay for mobile users
            const playButton = document.createElement('div');
            playButton.innerHTML = '<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.7); color: white; padding: 20px; border-radius: 50px; cursor: pointer; font-size: 18px; z-index: 10;"><i class="icon-play" style="margin-right: 10px;"></i>Trykk for å spille av video</div>';
            playButton.style.position = 'absolute';
            playButton.style.top = '0';
            playButton.style.left = '0';
            playButton.style.width = '100%';
            playButton.style.height = '100%';
            playButton.style.zIndex = '5';
            
            const heroWrap = document.querySelector('.hero-wrap');
            if (heroWrap) {
              heroWrap.appendChild(playButton);
              
              // Add click event to play video
              playButton.addEventListener('click', function() {
                video.play().then(() => {
                  playButton.style.display = 'none';
                }).catch(err => {
                  console.log('Manual play failed:', err);
                });
              });
            }
          });
        }
        
        // Also try to play on user interaction (scroll, touch, etc.)
        let userInteracted = false;
        const tryPlay = () => {
          if (!userInteracted && video.paused) {
            video.play().then(() => {
              userInteracted = true;
              const playButton = document.querySelector('.hero-wrap > div[style*="z-index: 5"]');
              if (playButton) {
                playButton.style.display = 'none';
              }
            }).catch(err => {
              console.log('Interaction play failed:', err);
            });
          }
        };
        
        // Listen for various user interactions
        document.addEventListener('scroll', tryPlay, { once: true });
        document.addEventListener('touchstart', tryPlay, { once: true });
        document.addEventListener('click', tryPlay, { once: true });
      }
    });
  </script>
    
  </body>
</html>
