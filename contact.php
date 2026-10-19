<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Kontakt - Salon Exclusive</title>
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
      .ftco-footer p a {
        color: #bf925b !important;
        text-decoration: none !important;
        transition: all 0.3s ease !important;
        font-weight: 600 !important;
      }
      
      .ftco-footer p a:hover {
        color: #a67c52 !important;
        text-decoration: underline !important;
        transform: scale(1.05) !important;
      }

      /* Contact Section Styling */
      .contact-main-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
        position: relative;
      }

      .contact-main-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(45deg, #bf925b, #d4a574);
        border-radius: 2px;
      }

      .contact-subtitle {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 0;
      }

      .contact-card {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: auto;
        min-height: 280px;
        position: relative;
        overflow: hidden;
      }

      .contact-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(45deg, #bf925b, #d4a574);
      }

      .contact-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      }

      .contact-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        background: linear-gradient(45deg, #bf925b, #d4a574);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
      }

      .contact-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: rotate(45deg);
        transition: all 0.6s ease;
      }

      .contact-card:hover .contact-icon::before {
        animation: shine 0.6s ease;
      }

      @keyframes shine {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
      }

      .contact-icon i {
        font-size: 1.5rem;
        color: #fff;
        position: relative;
        z-index: 1;
      }

      .contact-card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
        position: relative;
      }

      .contact-content {
        text-align: left;
      }

      /* Service Area Card */
      .service-area-card .contact-content {
        text-align: center;
      }


      .location-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
        margin-top: 0.5rem;
      }

      .location-tag {
        background: #f8f9fa;
        color: #666;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.85rem;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
      }

      .location-tag:hover {
        background: #bf925b;
        color: white;
        transform: scale(1.05);
      }

      /* Contact Info Card */
      .business-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
        text-align: center;
      }

      .phone-link {
        display: block;
        font-size: 1rem;
        font-weight: 600;
        color: #bf925b;
        text-decoration: none;
        margin-bottom: 0.75rem;
        text-align: center;
        transition: all 0.3s ease;
      }

      .phone-link:hover {
        color: #a67c52;
        transform: scale(1.05);
        text-decoration: none;
      }

      .address-section {
        text-align: center;
      }

      .address-label {
        font-weight: 600;
        color: #666;
        font-size: 0.8rem;
        margin-bottom: 0.2rem;
      }

      .address-text {
        color: #333;
        font-size: 0.85rem;
      }

      /* Email Card */
      .email-link {
        display: block;
        font-size: 1rem;
        color: #bf925b;
        text-decoration: none;
        margin-bottom: 0;
        word-break: break-all;
        transition: all 0.3s ease;
        font-weight: 600;
      }
      
      .email-card:hover .email-link {
        color: #a67c52;
      }

      .email-note {
        color: #666;
        font-size: 0.9rem;
        font-style: italic;
        text-align: center;
      }

      /* Hours Card */
      .hours-section {
        text-align: center;
      }

      .hours-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
      }

      .hours-row:last-child {
        border-bottom: none;
      }

      .hours-row.closed {
        opacity: 0.6;
      }

      .days {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
      }

      .time {
        color: #666;
        font-size: 0.9rem;
      }

      .hours-row.closed .time {
        color: #bf925b;
        font-weight: 600;
      }

      /* Desktop View - Fixed Height */
      @media (min-width: 992px) {
        .contact-card {
          min-height: 250px;
          padding: 1.25rem;
        }
        
        .contact-icon {
          width: 50px;
          height: 50px;
          margin: 0 auto 0.75rem;
        }
        
        .contact-icon i {
          font-size: 1.25rem;
        }
        
        .contact-card-title {
          font-size: 1.1rem;
          margin-bottom: 0.75rem;
        }
        
        .business-name {
          font-size: 0.85rem;
          margin-bottom: 0.4rem;
        }
        
        .phone-link {
          font-size: 0.95rem;
          margin-bottom: 0.6rem;
        }
        
        .address-label {
          font-size: 0.75rem;
          margin-bottom: 0.15rem;
        }
        
        .address-text {
          font-size: 0.8rem;
        }
      }

      /* Responsive Design */
      @media (max-width: 768px) {
        .contact-main-title {
          font-size: 2rem;
        }
        
        .contact-card {
          padding: 1.5rem;
        }
        
        .contact-icon {
          width: 60px;
          height: 60px;
        }
        
        .contact-icon i {
          font-size: 1.5rem;
        }
        
        .location-tags {
          justify-content: center;
        }
      }

      @media (max-width: 576px) {
        .contact-card {
          margin-bottom: 1rem;
        }
        
        .hours-row {
          flex-direction: column;
          gap: 0.3rem;
        }
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
	        	<li class="nav-item"><a href="index.php" class="nav-link">Hjem</a></li>
	        	<li class="nav-item"><a href="reborn.php" class="nav-link">Re-born</a></li>
	        	<li class="nav-item"><a href="stas.php" class="nav-link">STAS</a></li>
	        	<li class="nav-item"><a href="blog.php" class="nav-link">Blogg</a></li>
	          <li class="nav-item active"><a href="contact.php" class="nav-link">Kontakt</a></li>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->

    <section class="hero-wrap hero-wrap-2" style="background-image: url('hero_contact.jpeg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate pb-5 text-center">
          </div>
        </div>
      </div>
    </section>
    
    <section class="ftco-section contact-section">
      <div class="container">
        <!-- Contact Header -->
        <div class="row justify-content-center mb-5">
          <div class="col-md-8 text-center">
            <h2 class="contact-main-title">Kontakt Oss</h2>
            <p class="contact-subtitle">Vi er her for å hjelpe deg med alle dine behov</p>
          </div>
        </div>

        <!-- Contact Info Cards -->
        <div class="row mb-5 contact-info justify-content-center">
          <!-- Contact Info Card -->
          <div class="col-lg-3 col-md-6 mb-4">
            <div class="contact-card contact-info-card">
              <div class="contact-icon">
                <i class="icon-phone"></i>
              </div>
              <h4 class="contact-card-title">Kontaktinfo</h4>
              <div class="contact-content">
                <div class="business-name">SALON EXCLUSIVE</div>
                <a href="tel://+4797190030" class="phone-link">+47 971 90 030</a>
                <div class="address-section">
                  <div class="address-label">Adresse:</div>
                  <div class="address-text">Svelvikveien 61 D, 3039 Drammen</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Email Card -->
          <div class="col-lg-3 col-md-6 mb-4">
            <a href="mailto:borre@salonexclusive.no" style="text-decoration: none; display: block; color: inherit;">
              <div class="contact-card email-card" style="cursor: pointer;">
                <div class="contact-icon">
                  <i class="icon-envelope"></i>
                </div>
                <h4 class="contact-card-title">E-post</h4>
                <div class="contact-content">
                  <div class="email-link">borre@salonexclusive.no</div>
                </div>
              </div>
            </a>
          </div>

          <!-- Opening Hours Card -->
          <div class="col-lg-3 col-md-6 mb-4">
            <div class="contact-card hours-card">
              <div class="contact-icon">
                <i class="icon-clock-o"></i>
              </div>
              <h4 class="contact-card-title">Åpningstider</h4>
              <div class="contact-content">
                <div class="hours-section">
                  <div class="hours-row">
                    <span class="days">Man - Fre</span>
                    <span class="time">8:00 - 16:00</span>
                  </div>
                  <div class="hours-row closed">
                    <span class="days">Lør - Søn</span>
                    <span class="time">Lukket</span>
                  </div>
                </div>
              </div>
            </div>
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
