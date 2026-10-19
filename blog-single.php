<?php
require_once 'config/database.php';

// Get blog post ID from URL parameter
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the specific blog post
$post = null;
if ($post_id > 0) {
    try {
        $post = getBlogPostById($post_id);
    } catch (Exception $e) {
        error_log("Error fetching blog post: " . $e->getMessage());
        $post = null;
    }
}

// If post not found, redirect to blog page
if (!$post) {
    header('Location: blog.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo htmlspecialchars($post['title']); ?> - Haircare</title>
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
      
      .blog-post-meta {
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 8px;
        border-left: 4px solid #F96D00;
      }
      
      .blog-post-meta .d-flex > div {
        color: #666;
        font-size: 14px;
      }
      
      .blog-content {
        line-height: 1.8;
        font-size: 16px;
        color: #333;
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
      }
      
      .blog-content p {
        white-space: pre-wrap;
      }
      
      .blog-content h2, .blog-content h3, .blog-content h4 {
        color: #2c3e50;
        margin-top: 30px;
        margin-bottom: 15px;
        word-wrap: break-word;
      }
      
      .blog-content p {
        margin-bottom: 20px;
        word-wrap: break-word;
        overflow-wrap: break-word;
      }
      
      .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin: 20px 0;
        display: block;
        margin-left: auto;
        margin-right: auto;
      }
      
      .blog-content video {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin: 20px 0;
        display: block;
        margin-left: auto;
        margin-right: auto;
      }
      
      .blog-content iframe {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin: 20px 0;
        display: block;
        margin-left: auto;
        margin-right: auto;
      }
      
      .blog-content pre {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        overflow-x: auto;
        word-wrap: break-word;
        white-space: pre-wrap;
        max-width: 100%;
      }
      
      .blog-content code {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 2px 6px;
        font-family: 'Courier New', monospace;
        font-size: 14px;
        word-wrap: break-word;
      }
      
      .blog-content blockquote {
        border-left: 4px solid #F96D00;
        padding-left: 20px;
        margin: 20px 0;
        font-style: italic;
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 0 8px 8px 0;
      }
      
      .blog-content ul, .blog-content ol {
        margin: 20px 0;
        padding-left: 30px;
      }
      
      .blog-content li {
        margin-bottom: 10px;
        word-wrap: break-word;
      }
      
      .blog-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        overflow-x: auto;
        display: block;
        white-space: nowrap;
      }
      
      .blog-content table th,
      .blog-content table td {
        border: 1px solid #dee2e6;
        padding: 12px;
        text-align: left;
      }
      
      .blog-content table th {
        background: #f8f9fa;
        font-weight: 600;
      }
      
      .blog-tags h4 {
        color: #2c3e50;
        margin-bottom: 15px;
      }
      
      .tagcloud a {
        display: inline-block;
        background: #f8f9fa;
        color: #666;
        padding: 5px 12px;
        margin: 5px 5px 5px 0;
        border-radius: 20px;
        text-decoration: none;
        font-size: 12px;
        transition: all 0.3s ease;
      }
      
      .tagcloud a:hover {
        background: #F96D00;
        color: white;
        transform: translateY(-2px);
      }
      
      .blog-navigation .btn {
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }
      
      .blog-navigation .btn-primary {
        background: #F96D00;
        border-color: #F96D00;
      }
      
      .blog-navigation .btn-primary:hover {
        background: #e55a00;
        border-color: #e55a00;
        transform: translateY(-2px);
      }
      
      .blog-navigation .btn-outline-primary {
        color: #F96D00;
        border-color: #F96D00;
      }
      
      .blog-navigation .btn-outline-primary:hover {
        background: #F96D00;
        border-color: #F96D00;
        transform: translateY(-2px);
      }
      
      /* Responsive design and overflow prevention */
      .container {
        max-width: 100%;
        overflow-x: hidden;
      }
      
      .blog-content * {
        max-width: 100%;
        box-sizing: border-box;
      }
      
      .blog-content {
        overflow-x: hidden;
        word-break: break-word;
      }
      
      /* Media queries for better responsive design */
      @media (max-width: 768px) {
        .blog-content {
          font-size: 14px;
          padding: 0 10px;
        }
        
        .blog-content img,
        .blog-content video,
        .blog-content iframe {
          margin: 15px 0;
        }
        
        .blog-content h2, .blog-content h3, .blog-content h4 {
          font-size: 1.2em;
          margin-top: 20px;
          margin-bottom: 10px;
        }
        
        .blog-content table {
          font-size: 12px;
        }
        
        .blog-content pre {
          font-size: 12px;
          padding: 10px;
        }
        
        /* Mobile button layout - side by side */
        .blog-navigation .row {
          display: flex;
          flex-direction: row;
          gap: 10px;
          margin: 0;
        }
        
        .blog-navigation .col-md-6 {
          flex: 1;
          width: auto;
          padding: 0;
          max-width: none;
        }
        
        .blog-navigation .col-md-6.text-right {
          text-align: left;
        }
        
        .blog-navigation .btn {
          width: 100%;
          padding: 10px 12px;
          font-size: 13px;
          white-space: nowrap;
          display: flex;
          align-items: center;
          justify-content: center;
          text-align: center;
        }
        
        .blog-navigation .btn .icon-arrow-left {
          margin-right: 5px;
          margin-left: 0;
        }
        
        .blog-navigation .btn .icon-arrow-right {
          margin-left: 5px;
          margin-right: 0;
        }
      }
      
      @media (max-width: 480px) {
        .blog-content {
          font-size: 13px;
          padding: 0 5px;
        }
        
        .blog-content img,
        .blog-content video,
        .blog-content iframe {
          margin: 10px 0;
        }
        
        .blog-content h2, .blog-content h3, .blog-content h4 {
          font-size: 1.1em;
          margin-top: 15px;
          margin-bottom: 8px;
        }
        
        /* Smaller screens - adjust button text if needed */
        .blog-navigation .btn {
          padding: 8px 10px;
          font-size: 12px;
        }
        
        .blog-navigation .row {
          gap: 8px;
        }
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

    <section class="hero-wrap" style="background-image: url('<?php echo htmlspecialchars($post['featured_image']); ?>');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate pb-5 text-center">
          </div>
        </div>
      </div>
    </section>
    
    <section class="ftco-section ftco-degree-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 ftco-animate">
            <div class="blog-post-meta mb-4">
              <div class="d-flex align-items-center">
                <div class="mr-3">
                  <span class="icon-calendar mr-2"></span>
                  <span><?php echo date('j. M Y', strtotime($post['created_at'])); ?></span>
                </div>
                <div class="mr-3">
                  <span class="icon-person mr-2"></span>
                  <span><?php echo htmlspecialchars($post['author']); ?></span>
                </div>
                <?php if (isset($post['category']) && $post['category'] !== null && $post['category'] !== ''): ?>
                <div>
                  <span class="icon-folder mr-2"></span>
                  <span><?php echo htmlspecialchars($post['category']); ?></span>
                </div>
                <?php endif; ?>
              </div>
            </div>
            
            <h1 class="mb-4"><?php echo htmlspecialchars($post['title']); ?></h1>
            
            <div class="blog-content">
              <?php 
              // Convert line breaks to <br> tags if content doesn't contain HTML
              $content = $post['content'];
              // If content doesn't start with HTML tags, treat it as plain text and preserve line breaks
              if (strip_tags($content) === $content) {
                  echo nl2br(htmlspecialchars($content));
              } else {
                  // If it contains HTML, output as-is (HTML already handles line breaks)
                  echo $content;
              }
              ?>
              <?php if (isset($post['sub_media']) && !empty($post['sub_media'])): ?>
                <div class="sub-media-content">
                  <?php echo $post['sub_media']; ?>
                </div>
              <?php endif; ?>
            </div>
            
            <?php if (isset($post['tags']) && !empty($post['tags'])): ?>
            <div class="blog-tags mt-5">
              <h4>Tags:</h4>
              <div class="tagcloud">
                <?php
                $tags = explode(',', $post['tags']);
                foreach ($tags as $tag) {
                    $tag = trim($tag);
                    if (!empty($tag)) {
                        echo '<a href="#" class="tag-cloud-link">' . htmlspecialchars($tag) . '</a>';
                    }
                }
                ?>
              </div>
            </div>
            <?php endif; ?>
            
            <div class="blog-navigation mt-5">
              <div class="row">
                <div class="col-md-6">
                  <a href="blog.php" class="btn btn-primary">
                    <span class="icon-arrow-left"></span> Tilbake til Blogg
                  </a>
                </div>
                <div class="col-md-6 text-right">
                  <a href="contact.php" class="btn btn-outline-primary">
                    Kontakt Oss <span class="icon-arrow-right"></span>
                  </a>
                </div>
              </div>
            </div>

          </div> <!-- .col-md-8 -->

          <div class="col-lg-4 sidebar ftco-animate">
          </div>

        </div>
      </div>
    </section> <!-- .section -->


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
