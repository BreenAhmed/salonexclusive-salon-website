<?php
require_once 'config/database.php';

// Get Re-born products from database
$reborn_products = getProductsByCategory('reborn');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Re-born Produkter - Salon Exclusive</title>
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
    
    /* Reduce spacing above Instructions section */
    .instructions-section {
      padding-top: 2rem !important;
      margin-top: -2rem !important;
    }
    
    /* Instructions redesign */
    .instructions-wrap{
      background: linear-gradient(135deg, #fbf7f0 0%, #fffcf7 100%);
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 8px 30px rgba(191, 146, 91, 0.12);
      border: 1px solid #eadac2;
    }
    .instruction-item{
      background: #ffffff;
      border-radius: 14px;
      padding: 18px 18px;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;
      border: 1px solid #eadac2;
      transition: box-shadow .2s ease, transform .2s ease, border-color .2s ease;
      border-left: 4px solid #bf925b;
    }
    .instruction-item:hover{
      box-shadow: 0 8px 24px rgba(191, 146, 91, 0.18);
      transform: translateY(-2px);
      border-color: #e5d4b8;
    }
    .instruction-meta{
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .instruction-badge{
      width: 44px;
      height: 44px;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #f6efe3;
      color: #bf925b;
      font-weight: 700;
      font-size: 14px;
      border: 1px solid #eadac2;
    }
    .instruction-text h5{
      margin: 0 0 2px 0;
      font-weight: 800;
      color: #1f2937;
    }
    .instruction-text span{
      color: #6b7280;
      font-size: 14px;
    }
    .chevron{
      transition: transform .2s ease;
      color: #bf925b;
      font-size: 18px;
    }
    .instruction-collapse.collapse.show + .instruction-spacer .chevron,
    .instruction-item[aria-expanded="true"] .chevron{
      transform: rotate(180deg);
    }
    .video-card{
      background: #ffffff;
      border-radius: 12px;
      padding: 12px;
      border: 1px solid #eadac2;
      box-shadow: 0 6px 20px rgba(191, 146, 91, 0.12);
    }
    .ratio{
      position: relative;
      width: 100%;
      padding-top: 56.25%;
      overflow: hidden;
      border-radius: 10px;
    }
    .ratio iframe{
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      border: 0;
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
	        	<li class="nav-item active"><a href="reborn.php" class="nav-link">Re-born</a></li>
	        	<li class="nav-item"><a href="stas.php" class="nav-link">STAS</a></li>
	        	<li class="nav-item"><a href="blog.php" class="nav-link">Blogg</a></li>
	          <li class="nav-item"><a href="contact.php" class="nav-link">Kontakt</a></li>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->

    <section class="hero-wrap hero-wrap-2" data-stellar-background-ratio="0.5">
      <video id="heroVideo" muted loop playsinline class="hero-video">
        <source src="rebornhero.mp4" type="video/mp4">
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
          	<span class="subheading">Re-born Produkter</span>
            <h2 class="mb-4">Hvorfor er reborn-produkter så effektive?</h2>
          </div>
        </div>
        <div class="row">
        	<div class="col-md-12 ftco-animate">
        		<div class="text">
        			<p>En av dagens mest populære kosmetiske behandlinger består i å injisere eller påføre hyaluronsyre på ansiktshuden. Hyaluronsyre, eller hyaluronat, er et surt polysakkarid som forekommer naturlig i cellene i forskjellige vev i kroppen vår, inkludert bindevev og brusk. Det har en svampelignende funksjon – det absorberer vannet og omdanner det til en tykkere væske. I sin reneste form kan hyaluronsyre absorbere opptil tusen ganger sin opprinnelige størrelse.</p>
        			
        			<p>Takket være et kompleks som består av en rekke ekstra polymerer, er den nyttig for å fylle ut rynker og latterlinjer i ansiktshuden.</p>
        			
        			<p>I løpet av de siste tre årene har vi ved Reborn-laboratoriene utført utallige vitenskapelige eksperimenter for å lage en kraftig polymer som trenger inn i hårfibrene med fuktighet, styrker hårtekstur og skaper en jevn overflate på kutikula.</p>
        			
        			<p>Takket være et unikt neosomisk ledningssystem tilbyr HYLOREN hårprodukter et vitenskapelig gjennombrudd innen hårpleie og -styrking.</p>
        			
        			<p>Behandlingen består av tre trinn (tre produkter), som hver har en avgjørende rolle i hårets styrkings- og gjenopprettingsprosess. Etter den første behandlingen kan du allerede se en merkbar endring i hårkvaliteten, på syv forskjellige dimensjoner:</p>
        			
        			<ul>
        				<li>Fuktighetsabsorpsjon i hårfibrene</li>
        				<li>Økt volum ved bruk av volumizere</li>
        				<li>Styrking av hårfibrene og mindre hårbrudd over tid – noe som bidrar til hårvekst</li>
        				<li>En jevn overflate der kutikulaen er jevnt belagt for å skape en glatt og behagelig følelse på hårfibrene</li>
        				<li>Betydelig styrking av hårfibrene takket være et forhøyet vanninnhold (=fuktighet) i flasslaget</li>
        				<li>Opptil 58 % mer fleksibilitet i hårfiberen, fra den første unike kombinasjonen av de fire behandlingsproduktene, resulterer i et hårpleienivå som er enestående i hårproduktbransjen.</li>
        			</ul>
        		</div>
        	</div>
        </div>
    	</div>
    </section>

    <section class="ftco-section bg-light">
    	<div class="container">
    		<div class="row justify-content-center pb-3">
          <div class="col-md-10 heading-section text-center ftco-animate">
          	<span class="subheading">Re-born Produkter</span>
            <h2 class="mb-4">Våre Produkter</h2>
            <p>Utforsk vårt komplette utvalg av Re-born hårpleieprodukter</p>
          </div>
        </div>
        <div class="row">
        	<div class="col-md-12">
        		<div class="products-showcase">
        		<div class="products-grid">
        				<?php
        				// Display Re-born products from database
        				if (!empty($reborn_products)) {
        				    foreach ($reborn_products as $index => $product) {
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
        				    echo '<p>Ingen Re-born produkter tilgjengelig for øyeblikket.</p>';
        				    echo '</div>';
        				}
        				?>
        			</div>
        		</div>
        	</div>
        </div>
    	</div>
    </section>

    <!-- Instructions Section (moved after products) -->
    <section class="ftco-section bg-light instructions-section">
	    	<div class="container">
	    		<div class="row justify-content-center pb-3">
	    			<div class="col-md-10 heading-section text-center ftco-animate">
					<span class="subheading">Instruksjoner</span>
					<h2 class="mb-4">Hvordan bruke Re-born-produkter</h2>
					<p>Velg ønsket språk for å se instruksjonene.</p>
	    			</div>
	    		</div>

	    		<div class="row justify-content-center">
	    			<div class="col-md-10">
	    				<div class="instructions-wrap">
	    					<div class="instruction-item" data-toggle="collapse" data-target="#instr-en" aria-expanded="false" aria-controls="instr-en">
	    						<div class="instruction-meta">
	    							<div class="instruction-badge">EN</div>
	    							<div class="instruction-text">
	    								<h5>instruksjoner på engelsk</h5>
	    								<span>Watch step-by-step guidance in English</span>
	    							</div>
	    						</div>
	    						<span class="chevron">⌄</span>
	    					</div>
	    					<div class="instruction-collapse collapse" id="instr-en">
	    						<div class="video-card mb-3">
	    							<div class="ratio">
	    								<iframe src="https://www.youtube.com/embed/0FIJt4wGlzk" allowfullscreen title="Instructions in English"></iframe>
	    							</div>
	    						</div>
	    					</div>

	    					<div class="instruction-item" data-toggle="collapse" data-target="#instr-sv" aria-expanded="false" aria-controls="instr-sv">
	    						<div class="instruction-meta">
	    							<div class="instruction-badge">SE</div>
	    							<div class="instruction-text">
	    								<h5>instruktioner på svenska</h5>
	    								<span>Titta på steg-för-steg vägledning på svenska</span>
	    							</div>
	    						</div>
	    						<span class="chevron">⌄</span>
	    					</div>
	    					<div class="instruction-collapse collapse" id="instr-sv">
	    						<div class="video-card">
	    							<div class="ratio">
	    								<iframe src="https://www.youtube.com/embed/MEVVgHdloUc" allowfullscreen title="Instruktioner på svenska"></iframe>
	    							</div>
	    						</div>
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
