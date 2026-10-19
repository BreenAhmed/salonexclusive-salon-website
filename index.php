<?php
require_once 'config/database.php';

// Get Re-born products from database
$reborn_products = getProductsByCategory('reborn');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Salon Exclusive - Hårpleie og Styling</title>
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
      
      .hero-wrap .slider-text {
        padding-bottom: 80px;
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
      
      /* Shift brush image content to the right inside container */
      .image-section-left .services-wrap {
        background-position: 30% center;
      }
      
      /* Shift forwomen image content to the left inside container */
      .image-section-right .services-wrap {
        background-position: 70% center;
      }
      
      /* Mobile reordering for welcome section */
      @media (max-width: 768px) {
        .ftco-section .row {
          display: flex !important;
          flex-direction: column !important;
        }
        
        .image-section-left {
          order: 1 !important;
        }
        
        .welcome-section {
          order: 2 !important;
          width: 100% !important;
          flex: none !important;
        }
        
        .image-section-right {
          order: 3 !important;
        }
        
        .welcome-section .text-about {
          background: #f8f9fa;
          margin: 20px 0;
          border-radius: 10px;
          box-shadow: 0 5px 15px rgba(0,0,0,0.1);
          text-align: center;
        }
        
        .welcome-section h2 {
          font-size: 1.8rem;
          margin-bottom: 1rem;
        }
        
        .welcome-section p {
          font-size: 0.95rem;
          line-height: 1.6;
        }
        
        .welcome-section .logo img {
          height: 100px !important;
        }
      }
      
      /* Re-born Product Overlay Styles */
      .project {
        position: relative;
        overflow: hidden;
      }
      
      .project-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 20px;
        opacity: 0;
        transition: all 0.3s ease;
        transform: translateY(20px);
      }
      
      .project:hover .project-overlay {
        opacity: 1;
        transform: translateY(0);
      }
      
      .project-overlay h4 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #fff;
      }
      
      .project-overlay p {
        font-size: 14px;
        margin-bottom: 15px;
        line-height: 1.4;
        color: #f8f9fa;
      }
      
      
      
      .project img {
        width: 100%;
        height: 350px;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
      }
      
      .project:hover img {
        transform: scale(1.1);
      }

      /* FAQ theme to match site colors */
      #faq-accordion .card {
        border: 1px solid #eadac2;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(191, 146, 91, 0.10);
      }
      #faq-accordion .card + .card { margin-top: 12px; }
      #faq-accordion .card-header {
        background: linear-gradient(135deg, #fbf7f0 0%, #fffcf7 100%);
        border-bottom: 1px solid #eadac2;
        padding: 0.75rem 1rem;
      }
      #faq-accordion .btn-link {
        color: #1f2937;
        font-weight: 700;
        text-decoration: none;
      }
      #faq-accordion .btn-link:hover,
      #faq-accordion .btn-link:focus {
        color: #bf925b;
        text-decoration: none;
      }
      #faq-accordion .card-body {
        background: #ffffff;
        color: #444;
      }
      #faq-accordion .collapse.show {
        border-top: 1px solid #eadac2;
      }
      
      /* Temporarily hide hero section video */
      .hero-wrap {
        display: none !important;
      }
      
      /* Thank You Modal */
      .thank-you-modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        animation: fadeIn 0.3s ease;
      }
      
      .thank-you-modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
      }
      
      .thank-you-content {
        background-color: white;
        padding: 40px;
        border-radius: 15px;
        max-width: 500px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideIn 0.3s ease;
        position: relative;
      }
      
      @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
      }
      
      @keyframes slideIn {
        from {
          opacity: 0;
          transform: translateY(-50px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      
      .thank-you-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #bf925b 0%, #a67c52 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: white;
      }
      
      .thank-you-content h3 {
        color: #2c3e50;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 15px;
      }
      
      .thank-you-content p {
        color: #666;
        font-size: 16px;
        margin-bottom: 25px;
        line-height: 1.6;
      }
      
      .thank-you-close {
        background: linear-gradient(135deg, #bf925b 0%, #a67c52 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
      }
      
      .thank-you-close:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(191, 146, 91, 0.4);
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
	        	<li class="nav-item active"><a href="index.php" class="nav-link">Hjem</a></li>
	        	<li class="nav-item"><a href="reborn.php" class="nav-link">Re-born</a></li>
	        	<li class="nav-item"><a href="stas.php" class="nav-link">STAS</a></li>
	        	<li class="nav-item"><a href="blog.php" class="nav-link">Blogg</a></li>
	          <li class="nav-item"><a href="contact.php" class="nav-link">Kontakt</a></li>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->

    <section class="hero-wrap js-fullheight" data-stellar-background-ratio="0.5">
      <video id="heroVideo" muted loop playsinline class="hero-video">
        <source src="homehero.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
	      <div class="overlay"></div>
	      <div class="container">
	        <div class="row no-gutters slider-text js-fullheight justify-content-center align-items-end">
	          <div class="col-lg-12 ftco-animate d-flex align-items-end">
	          	<div class="text text-center">
						</div>
	            </div>
	          </div>
	        </div>
	      </div>
    </section>
		
		<section class="ftco-section ftco-no-pt ftco-no-pb">
		<div class="container-fluid px-0">
			<div class="row no-gutters">
				<div class="col-md text-center d-flex align-items-stretch image-section-left">
					<div class="services-wrap d-flex align-items-center img" style="background-image: url(images/brsuh.jpg);">
					</div>
				</div>
				<div class="col-md-3 text-center d-flex align-items-stretch welcome-section">
					<div class="text-about py-5 px-4">
						<h1 class="logo">
							<a href="#"><img src="cx.png" alt="Logo" style="height: 120px; margin-bottom: 15px;"></a>
						</h1>
						<h2>VELKOMMEN TIL VÅR NETTSIDE</h2>
						<p>Børre har mer enn 30 års erfaring innen frisørbransjen og har bygget opp et sterkt rykte gjennom sin profesjonalitet og evne til å følge utviklingen i markedet.<br><br>
						Gjennom Salon Exclusive som han startet 1 januar 2020, har han skapt et konsept med fokus på kvalitet, personlig oppfølging og punktlighet-verdier harmonerer med proukutene og ambisjonene til Stas og Re-Bornhairsolution.</p>
						<p class="mt-3"><a href="blog.php" class="btn btn-primary btn-outline-primary">Les mer</a></p>
					</div>
				</div>
				<div class="col-md text-center d-flex align-items-stretch image-section-right">
					<div class="services-wrap d-flex align-items-center img" style="background-image: url(images/forwomen.jpg);">
					</div>
				</div>
			</div>
		</div>
		</section>


    




    <section class="ftco-section ftco-no-pt ftco-no-pb">
    	<div class="container">
    		<div class="row no-gutters justify-content-center mb-5 pb-2">
          <div class="col-md-6 text-center heading-section ftco-animate">
            <h2 class="mb-4">Re-born</h2>
            <p>Reborns HYLOREN-produkter produseres av et ledende selskap innen hårhelseteknologi, som resultat av samarbeid mellom skjønnhetseksperter og avanserte internasjonale forskningslaboratorier.</p>
            <p><a href="reborn.php" class="btn btn-primary">Se alle Re-born produkter</a></p>
          </div>
        </div>
    	</div>
		<div class="container-fluid p-0">
    		<div class="row no-gutters">
    			<?php 
    			// Display Re-born products from database
    			$product_count = 0;
				foreach ($reborn_products as $product): 
					if ($product_count >= 8) break; // Limit to 8 products for homepage
					// Show only highlighted (featured) Re-born products on homepage
					if (empty($product['featured']) || !$product['featured']) continue;
    			?>
    			<div class="col-md-6 col-lg-3 ftco-animate">
    				<div class="project">
	    				<img src="<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
	    				<a href="<?php echo htmlspecialchars($product['image']); ?>" class="icon image-popup d-flex justify-content-center align-items-center">
	    					<span class="icon-expand"></span>
	    				</a>
            <div class="project-overlay">
            	<h4><?php echo htmlspecialchars($product['name']); ?></h4>
            </div>
    				</div>
    			</div>
    			<?php 
    			$product_count++;
    			endforeach; 
    			
    			// If we have less than 8 products, fill with placeholder images
    			while ($product_count < 8):
    			?>
    			<div class="col-md-6 col-lg-3 ftco-animate">
    				<div class="project">
	    				<img src="images/work-<?php echo ($product_count % 8) + 1; ?>.jpg" class="img-fluid" alt="Re-born Product">
	    				<a href="images/work-<?php echo ($product_count % 8) + 1; ?>.jpg" class="icon image-popup d-flex justify-content-center align-items-center">
	    					<span class="icon-expand"></span>
	    				</a>
    				</div>
    			</div>
    			<?php 
    			$product_count++;
    			endwhile;
    			?>
    		</div>
    	</div>
		</section>

		<section class="ftco-section contact-section">
			<div class="container">
				<div class="row justify-content-center pb-3">
					<div class="col-md-10 heading-section text-center ftco-animate">
						<span class="subheading">Kontakt</span>
						<h2 class="mb-4">Ta kontakt</h2>
						<p>Kontakt oss for spørsmål eller for å bestille time</p>
					</div>
				</div>
				<div class="row block-9">
					<div class="col-md-6 order-md-last d-flex">
						<form action="https://formspree.io/f/xwpakanz" method="POST" class="bg-light p-5 contact-form" id="contactForm">
							<div class="form-group">
								<label for="name">Navn:</label>
								<input type="text" class="form-control" id="name" name="name" placeholder="Skriv inn ditt navn" required>
							</div>
							<div class="form-group">
								<label for="mobile">Mobilnummer:</label>
								<input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Skriv inn ditt mobilnummer">
							</div>
							<div class="form-group">
								<label for="email">E-postadresse:</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Skriv inn din e-postadresse" required>
							</div>
							<div class="form-group">
								<label for="notes">Merknader:</label>
								<textarea name="message" id="notes" cols="30" rows="7" class="form-control" placeholder="Skriv inn dine merknader eller melding" required></textarea>
							</div>
							<div class="form-group">
								<input type="submit" value="Send melding" class="btn btn-primary py-3 px-5">
							</div>
						</form>
					</div>
					<div class="col-md-6 d-flex">
						<img src="woman-before-after-washing-hair-600nw-2150166601.webp" alt="Location" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
					</div>
				</div>
			</div>
		</section>
		
		<!-- FAQ Section (moved after contact) -->
		<section class="ftco-section bg-light">
			<div class="container">
				<div class="row justify-content-center pb-3">
					<div class="col-md-10 heading-section text-center ftco-animate">
						<span class="subheading">Ofte stilte spørsmål</span>
						<h2 class="mb-4">FAQ</h2>
						<p>Finn raske svar på vanlige spørsmål om våre produkter og tjenester.</p>
					</div>
				</div>

				<div class="row justify-content-center">
					<div class="col-md-10">
						<div id="faq-accordion">
							<div class="card mb-2">
								<div class="card-header" id="faqOne">
									<h5 class="mb-0">
										<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
											Hvordan bestiller jeg Re-born produkter?
										</button>
									</h5>
								</div>
								<div id="collapseOne" class="collapse show" aria-labelledby="faqOne" data-parent="#faq-accordion">
									<div class="card-body">
										Kontakt oss via skjemaet ovenfor eller ring oss direkte, så hjelper vi deg med anbefaling og bestilling.
									</div>
								</div>
							</div>

							<div class="card mb-2">
								<div class="card-header" id="faqTwo">
									<h5 class="mb-0">
										<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
											Hvor lang leveringstid er det?
										</button>
									</h5>
								</div>
								<div id="collapseTwo" class="collapse" aria-labelledby="faqTwo" data-parent="#faq-accordion">
									<div class="card-body">
										Normalt 2–5 virkedager. Vi bekrefter estimert levering når bestillingen er registrert.
									</div>
								</div>
							</div>

							<div class="card mb-2">
								<div class="card-header" id="faqThree">
									<h5 class="mb-0">
										<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
											Tilbyr dere veiledning til riktig produkt?
										</button>
									</h5>
								</div>
								<div id="collapseThree" class="collapse" aria-labelledby="faqThree" data-parent="#faq-accordion">
									<div class="card-body">
										Ja. Vi gir personlig anbefaling basert på hårtype og behov – ta kontakt via skjemaet.
									</div>
								</div>
							</div>

							<div class="card">
								<div class="card-header" id="faqFour">
									<h5 class="mb-0">
										<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
											Har dere retur- eller bytterett?
										</button>
									</h5>
								</div>
								<div id="collapseFour" class="collapse" aria-labelledby="faqFour" data-parent="#faq-accordion">
									<div class="card-body">
										Ja, i henhold til gjeldende regler. Kontakt oss før retur, så hjelper vi deg videre.
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
  <script src="js/main.js"></script>
  
  <script>
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
    
    // Form submission handler
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('contactForm');
      if (form) {
        form.addEventListener('submit', async function(e) {
          e.preventDefault();
          
          const submitButton = form.querySelector('input[type="submit"]');
          const originalValue = submitButton.value;
          submitButton.value = 'Sender...';
          submitButton.disabled = true;
          
          const formData = new FormData(form);
          
          try {
            const response = await fetch('https://formspree.io/f/xwpakanz', {
              method: 'POST',
              body: formData,
              headers: {
                'Accept': 'application/json'
              }
            });
            
            if (response.ok) {
              form.reset();
              showThankYouModal();
            } else {
              const data = await response.json();
              if (data.errors) {
                alert('Det oppstod en feil. Vennligst prøv igjen.');
              }
            }
          } catch (error) {
            alert('Det oppstod en feil. Vennligst prøv igjen.');
          } finally {
            submitButton.value = originalValue;
            submitButton.disabled = false;
          }
        });
      }
    });
    
    function showThankYouModal() {
      const modal = document.getElementById('thankYouModal');
      if (modal) {
        modal.classList.add('show');
      }
    }
    
    function closeThankYouModal() {
      const modal = document.getElementById('thankYouModal');
      if (modal) {
        modal.classList.remove('show');
      }
    }
  </script>
  
  <!-- Thank You Modal -->
  <div id="thankYouModal" class="thank-you-modal" onclick="if(event.target === this) closeThankYouModal();">
    <div class="thank-you-content">
      <div class="thank-you-icon">✓</div>
      <h3>Takk for din melding!</h3>
      <p>Vi har mottatt din henvendelse og vil kontakte deg så snart som mulig.</p>
      <button class="thank-you-close" onclick="closeThankYouModal()">Lukk</button>
    </div>
  </div>
    
  </body>
</html>
