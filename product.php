<?php
require_once 'config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = $id > 0 ? getProductById($id) : null;

// Fallbacks
$productName = $product ? $product['name'] : 'Produkt ikke funnet';
$productDesc = $product ? $product['description'] : '';
$productImage = $product && !empty($product['image']) ? $product['image'] : 'images/work-1.jpg';
$productCategory = $product ? $product['category'] : null;

function categoryToTitle($category) {
    if ($category === 'reborn') return 'Re-born';
    if ($category === 'stas') return 'STAS';
    if ($category === 'logomatter') return 'Logomatter';
    return '';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo htmlspecialchars($productName); ?> - Salon Exclusive</title>
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
      /* Navbar link colors before scrolling */
      .ftco-navbar-light .navbar-nav > .nav-item > .nav-link {
        color: #000000 !important;
      }
      .ftco-navbar-light .navbar-brand {
        color: #000000 !important;
      }

      .product-hero {
        background: #f8f9fa;
        padding: 30px 0;
      }
      .product-title {
        font-weight: 800;
      }
      .product-meta a {
        color: #bf925b;
        text-decoration: none;
      }
      .product-meta a:hover { text-decoration: underline; }
      .product-image-lg {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 6px 24px rgba(0,0,0,0.08);
      }
      /* Robust sticky using Bootstrap utility */
      .product-sticky.sticky-top { 
        top: 110px; 
        z-index: 2 !important; /* Lower than navbar z-index (3) */
      }
      .product-desc-lg {
        font-size: 18px;
        line-height: 1.8;
        color: #374151;
      }
      .back-link { color: #6b7280; text-decoration: none; }
      .back-link:hover { text-decoration: underline; }
      /* New Product Page Design */
      .product-wrap { padding: 120px 0 40px; background: #fbfbfb; }
      
      .meta-row { display: flex; gap: 16px; align-items: center; color: #6b7280; margin-bottom: 20px; }
      .primary-btn { background: #bf925b; color: #fff; border: none; padding: 10px 18px; border-radius: 10px; font-weight: 700; }
      .secondary-btn { background: #fff; color: #bf925b; border: 2px solid #eadac2; padding: 9px 16px; border-radius: 10px; font-weight: 700; }
      .product-card-lg { background: #fff; border: 1px solid #eee; border-radius: 16px; padding: 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.06); }
      .desc-card { background: #fff; border: 1px solid #eee; border-radius: 16px; padding: 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.06); }
      .desc-block { font-size: 17px; line-height: 1.9; color: #374151; }
      .feature-list { list-style: none; padding-left: 0; margin: 0; display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 10px 16px; }
      .feature-list li::before { content: '✓'; color: #22c55e; margin-right: 8px; }
      @media (max-width: 767.98px) { .feature-list { grid-template-columns: 1fr; } }
      /* Related products */
      .related-wrap { padding: 20px 0 60px; }
      .related-title { font-weight: 800; margin-bottom: 20px; }
      .related-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
      @media (max-width: 1200px) { .related-grid { grid-template-columns: repeat(3, 1fr); } }
      @media (max-width: 768px) { .related-grid { grid-template-columns: repeat(2, 1fr); } }
      @media (max-width: 520px) { .related-grid { grid-template-columns: 1fr; } }
      .related-card { background: #fff; border: 1px solid #eee; border-radius: 14px; overflow: hidden; box-shadow: 0 6px 24px rgba(0,0,0,0.05); transition: transform .2s ease, box-shadow .2s ease; }
      .related-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }
      .related-thumb { width: 100%; height: 180px; background-size: cover; background-position: center; }
      .related-body { padding: 14px; }
      .related-name { font-weight: 700; color: #1f2937; margin: 0 0 6px 0; font-size: 16px; }
      .related-link { color: inherit; text-decoration: none; display: block; }
      
      /* Mobile navbar - white text by default, black when scrolled */
      @media (max-width: 991.98px) {
        .ftco-navbar-light .navbar-nav > .nav-item > .nav-link {
          color: #ffffff !important;
        }
        .ftco-navbar-light .navbar-brand {
          color: #ffffff !important;
        }
        /* When scrolled, make navbar items black on mobile */
        .ftco-navbar-light.scrolled .navbar-nav > .nav-item > .nav-link {
          color: #000000 !important;
        }
        .ftco-navbar-light.scrolled .navbar-brand {
          color: #000000 !important;
        }
      }
    </style>
  </head>
  <body>
	  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar ftco-navbar-light" id="ftco-navbar">
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
	          <li class="nav-item"><a href="contact.php" class="nav-link">Kontakt</a></li>
	        </ul>
	      </div>
	    </div>
	  </nav>

    <section class="product-wrap">
      <div class="container">
        
        <div class="row">
          <div class="col-lg-6 mb-4">
            <div class="product-card-lg product-sticky sticky-top">
              <img src="<?php echo htmlspecialchars($productImage); ?>" alt="<?php echo htmlspecialchars($productName); ?>" class="product-image-lg">
            </div>
          </div>
          <div class="col-lg-6">
            <div class="desc-card">
              <div class="mb-2">
                <h1 class="product-title mb-0"><?php echo htmlspecialchars($productName); ?></h1>
              </div>
              <div class="meta-row">
                <span>Tilgjengelig</span>
                <?php if (!empty($productCategory)): ?>
                  <span>•</span>
                  <span><?php echo htmlspecialchars(categoryToTitle($productCategory)); ?></span>
                <?php endif; ?>
              </div>
              <div class="desc-block mb-3">
                <?php if ($product): ?>
                  <?php echo nl2br(htmlspecialchars($productDesc)); ?>
                <?php else: ?>
                  Beklager, vi fant ikke dette produktet. Det kan ha blitt fjernet eller er inaktivt.
                <?php endif; ?>
              </div>
              <ul class="feature-list mb-4">
                <li>Skånsom og effektiv</li>
                <li>Passer de fleste hårtyper</li>
                <li>Profesjonell kvalitet</li>
                <li>Utviklet av fagfolk</li>
              </ul>
              <div class="d-flex gap-2">
                <a href="contact.php" class="btn primary-btn">Kontakt oss</a>
                <a href="<?php echo $productCategory === 'reborn' ? 'reborn.php' : ($productCategory === 'stas' ? 'stas.php' : 'index.php'); ?>" class="btn secondary-btn">Se flere produkter</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <?php
    $related = [];
    if ($productCategory) {
        $all = getProductsByCategory($productCategory);
        foreach ($all as $p) {
            if (count($related) >= 8) break;
            if ($p['id'] == $id) continue;
            $related[] = $p;
        }
    }
    ?>

    <?php if (!empty($related)): ?>
    <section class="related-wrap">
      <div class="container">
        <h3 class="related-title">Relaterte produkter</h3>
        <div class="related-grid">
          <?php foreach ($related as $rp): ?>
            <a class="related-link" href="product.php?id=<?php echo intval($rp['id']); ?>">
              <div class="related-card">
                <div class="related-thumb" style="background-image:url(<?php echo htmlspecialchars($rp['image']); ?>)"></div>
                <div class="related-body">
                  <div class="related-name"><?php echo htmlspecialchars($rp['name']); ?></div>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

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

