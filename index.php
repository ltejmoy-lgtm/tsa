<?php
session_start();
$isLoggedIn = isset($_SESSION["user_id"]);
$userName = $isLoggedIn ? $_SESSION["user_name"] : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TSA — Shop Everything You Love</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- ================= HEADER ================= -->
  <header class="topbar">
    <div class="nav">
      <a class="logo" href="index.php" aria-label="TSA Shop">
        <span class="logo-mark" aria-hidden="true"><b>T</b><i>S</i></span>
        <span class="logo-word">TSA<span>.</span></span>
        <small>Selective goods</small>
      </a>

      <button class="location" type="button" onclick="alert('Delivery is available across India with standard & express shipping!')">
        <span class="location-pin" aria-hidden="true"></span><span>Deliver to<br><b>India</b></span>
      </button>

      <div class="search-wrap">
        <input id="searchInput" type="search" placeholder="Search for products, brands and more" autocomplete="off">
        <button id="searchBtn" type="button" aria-label="Search">⌕</button>
      </div>

      <!-- User Auth State -->
      <?php if ($isLoggedIn): ?>
        <a class="login-btn" id="accountBtn" href="dashboard.php">
          👤 <?php echo htmlspecialchars($userName); ?>
        </a>
        <a class="nav-link" href="auth/logout.php">Logout</a>
      <?php else: ?>
        <a class="login-btn" id="loginBtn" href="auth/login.php">Login</a>
        <a class="nav-link" href="auth/register.php">Register</a>
      <?php endif; ?>

      <button class="cart-btn" id="cartBtn" type="button" aria-label="View Cart">
        <span class="bag-icon" aria-hidden="true"></span> Bag <b id="cartCount">0</b>
      </button>
    </div>
  </header>

  <!-- ================= CATEGORY BAR ================= -->
  <nav class="categories" aria-label="Product categories">
    <div class="category-inner" id="categoryBar"></div>
  </nav>

  <!-- ================= MAIN CONTENT ================= -->
  <main>
    <!-- Hero Banner -->
    <section class="hero">
      <div class="hero-copy">
        <span class="eyebrow">TSA BIG SAVINGS</span>
        <h1>Everything you need.<br><strong>Better prices.</strong></h1>
        <p>Discover trending electronics, fashion, home essentials, and everyday favorites with fast delivery.</p>
        <button class="shop-now" type="button" onclick="scrollToProducts()">Shop Now →</button>
      </div>
      <div class="hero-art">
        <div class="deal-tag">UP TO<br><strong>70% OFF</strong></div>
        <img src="https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=1000&q=85" alt="Electronics collection" loading="eager">
      </div>
    </section>

    <!-- Trust Row -->
    <section class="trust-row">
      <div>🚚 <div><b>Free Delivery</b><span>On orders over ₹499</span></div></div>
      <div>🔒 <div><b>Secure Payments</b><span>100% protected checkout</span></div></div>
      <div>↩️ <div><b>Easy Returns</b><span>7-day hassle-free returns</span></div></div>
      <div>⭐ <div><b>TSA Assured</b><span>Quality checked products</span></div></div>
    </section>

    <!-- Products Section -->
    <section class="section" id="productsSection">
      <div class="section-head">
        <div>
          <h2>Best Deals</h2>
          <p>Top picks from our catalog at prices you'll love</p>
        </div>
        <button class="view-all" type="button" onclick="showAll()">View All →</button>
      </div>
      <div class="product-grid" id="productGrid"></div>
    </section>

    <!-- Trending Categories -->
    <section class="section">
      <div class="section-head">
        <div>
          <h2>Trending Categories</h2>
          <p>Explore what everyone is shopping right now</p>
        </div>
      </div>
      <div class="category-cards" id="categoryCards"></div>
    </section>

    <!-- Promo Banner -->
    <section class="promo">
      <div>
        <span class="eyebrow">TSA EXCLUSIVE</span>
        <h2>Upgrade your everyday.</h2>
        <p>Fresh arrivals across smartphones, audio gear, apparel, and lifestyle accessories.</p>
        <button class="shop-now" type="button" onclick="scrollToProducts()">Explore Deals →</button>
      </div>
      <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=900&q=85" alt="Watch and accessories" loading="lazy">
    </section>
  </main>

  <!-- ================= FOOTER ================= -->
  <footer>
    <div class="footer-main">
      <div>
        <div class="footer-logo"><span class="footer-mark">TS</span><span>TSA<span>.</span></span><small>Selective goods</small></div>
        <p>Your premium online marketplace for authentic electronics, lifestyle and fashion.</p>
        <div style="margin-top:14px;">
          <a href="test_db.php" style="display:inline-block;color:#60a5fa;text-decoration:none;font-size:11px;border:1px solid #1e3a8a;padding:4px 10px;border-radius:4px;">
            🛠️ System & Database Diagnostic
          </a>
        </div>
      </div>
      <div>
        <h4>ABOUT</h4>
        <a href="#about">About TSA</a>
        <a href="#contact">Contact Us</a>
        <a href="#careers">Careers</a>
        <a href="#press">Press Releases</a>
      </div>
      <div>
        <h4>HELP & SUPPORT</h4>
        <a href="#payments">Payments</a>
        <a href="#shipping">Shipping & Delivery</a>
        <a href="#returns">Returns & Cancellations</a>
        <a href="#faq">FAQ</a>
      </div>
      <div>
        <h4>POLICY</h4>
        <a href="#terms">Terms of Use</a>
        <a href="#privacy">Privacy Notice</a>
        <a href="#security">Security Information</a>
        <a href="#compliance">Grievance Officer</a>
      </div>
      <div>
        <h4>CONNECT</h4>
        <a href="#instagram">Instagram</a>
        <a href="#facebook">Facebook</a>
        <a href="#twitter">Twitter / X</a>
        <a href="#youtube">YouTube</a>
      </div>
    </div>
    <div class="footer-bottom">
      © 2026 TSA Shop. All rights reserved. Realistic Marketplace Catalog.
    </div>
  </footer>

  <!-- ================= OVERLAYS & MODALS ================= -->
  <div class="overlay" id="overlay"></div>

  <!-- Cart Drawer -->
  <aside class="cart-drawer" id="cartDrawer" aria-label="Shopping cart">
    <div class="drawer-head">
      <h2>Your Cart</h2>
      <button id="closeCart" type="button" aria-label="Close cart">✕</button>
    </div>
    <div id="cartItems"></div>
    <div class="cart-total">
      <span>Total</span>
      <strong id="cartTotal">₹0</strong>
    </div>
    <button class="checkout" type="button">Proceed to Checkout</button>
  </aside>

  <!-- Product Detail Modal -->
  <div class="product-modal" id="productModal" role="dialog" aria-modal="true">
    <button class="modal-close" onclick="closeProduct()" type="button" aria-label="Close details">✕</button>
    <div class="product-detail">
      <div class="detail-gallery">
        <img id="detailImage" src="" alt="Product view" onerror="this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80'">
      </div>
      <div class="detail-content">
        <div class="product-cat" id="detailCat"></div>
        <h2 id="detailName"></h2>
        <span class="rating" id="detailRating"></span>
        <div class="detail-price" id="detailPrice"></div>
        <div class="detail-offer" id="detailOffer"></div>
        <p id="detailDescription"></p>
        <div class="delivery-box">
          <b>🚚 Delivery Availability</b>
          <div class="delivery-row">
            <input id="pincodeInput" maxlength="6" placeholder="Enter 6-digit pincode" pattern="[0-9]*">
            <button type="button" onclick="checkDelivery()">Check</button>
          </div>
          <div id="deliveryMessage">Enter your pincode to check estimated delivery date.</div>
        </div>
        <div class="detail-actions">
          <button class="add detail-add" id="detailAdd" type="button">Add to Cart</button>
          <button class="buy-now" id="detailBuy" type="button">Buy Now</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Checkout Modal -->
  <div class="checkout-modal" id="checkoutModal" role="dialog" aria-modal="true">
    <button class="modal-close" onclick="closeCheckout()" type="button" aria-label="Close checkout">✕</button>
    <div class="checkout-grid">
      <div>
        <div class="checkout-title">TSA Fast Checkout</div>
        <div class="stepper">
          <span class="active">1. Address</span>
          <span>2. Delivery</span>
          <span>3. Payment</span>
        </div>

        <h3>Delivery Address</h3>
        <div class="form-grid">
          <input id="customerName" placeholder="Full name" value="<?php echo htmlspecialchars($userName); ?>" required>
          <input id="customerPhone" placeholder="10-digit mobile number" maxlength="10" required>
          <input id="customerPin" placeholder="6-digit pincode" maxlength="6" required>
          <input id="customerCity" placeholder="City" required>
          <input id="customerState" placeholder="State" required>
          <input id="customerHouse" placeholder="House / Flat / Building No." required>
          <textarea id="customerAddress" placeholder="Area, colony, street, landmark" required></textarea>
        </div>

        <h3>Delivery Options</h3>
        <label class="delivery-option">
          <input type="radio" name="delivery" id="standardDelivery" value="standard" checked onchange="renderCheckout()">
          <span><b>Standard Delivery</b><small>Free on orders ₹499+ • 4–7 business days</small></span>
        </label>
        <label class="delivery-option">
          <input type="radio" name="delivery" id="fastDelivery" value="fast" onchange="renderCheckout()">
          <span><b>Express Delivery</b><small>₹99 • 1–3 business days priority shipping</small></span>
        </label>

        <h3>Payment Method</h3>
        <label class="payment-option">
          <input type="radio" name="payment" value="Cash on Delivery" checked>
          <span><b>Cash on Delivery</b><small>Pay securely at your doorstep</small></span>
        </label>
        <label class="payment-option">
          <input type="radio" name="payment" value="UPI">
          <span><b>UPI (Google Pay / PhonePe / Paytm)</b><small>Instant zero-fee payment</small></span>
        </label>
        <label class="payment-option">
          <input type="radio" name="payment" value="Credit / Debit Card">
          <span><b>Credit / Debit Card / Net Banking</b><small>All major Indian banks accepted</small></span>
        </label>

        <button class="place-order" type="button" onclick="placeOrder()">Place Order</button>
      </div>

      <div class="checkout-summary">
        <h3>Order Summary</h3>
        <div id="checkoutItems"></div>
        <div class="summary-line"><span>Subtotal</span><b id="checkoutSubtotal">₹0</b></div>
        <div class="summary-line"><span>Delivery</span><b id="checkoutDelivery">FREE</b></div>
        <div class="summary-line total"><span>Total Payable</span><b id="checkoutTotal">₹0</b></div>
        <div class="secure-note">
          🔒 256-bit SSL encrypted checkout<br>
          🛡️ TSA 100% Buyer Protection Guarantee<br>
          ↩️ 7-day hassle-free replacement or refund
        </div>
      </div>
    </div>
  </div>

  <!-- Order Success Modal -->
  <div class="order-success" id="orderSuccess" role="dialog" aria-modal="true">
    <div class="success-icon">✓</div>
    <h2>Order Placed Successfully!</h2>
    <p>Thank you for shopping with TSA Shop. Your order is confirmed.</p>
    <div class="success-card">
      <div><span>Order ID</span><b id="successOrderId">TSA-000000</b></div>
      <div><span>Payment Method</span><b id="successPayment">Cash on Delivery</b></div>
      <div><span>Total Amount</span><b id="successTotal">₹0</b></div>
      <div><span>Estimated Delivery</span><b id="successDelivery">Within 5 days</b></div>
    </div>
    <div class="tracking">
      <div class="track active"><i>✓</i><span>Confirmed</span></div>
      <div class="track"><i>2</i><span>Packed</span></div>
      <div class="track"><i>3</i><span>Shipped</span></div>
      <div class="track"><i>4</i><span>Delivered</span></div>
    </div>
    <div style="display:flex;gap:12px;justify-content:center;margin-top:20px;">
      <button class="continue-shopping" type="button" onclick="continueShopping()">Continue Shopping</button>
      <?php if ($isLoggedIn): ?>
        <a href="dashboard.php" class="continue-shopping" style="background:#0f172a;text-decoration:none;display:inline-block;">View in Dashboard</a>
      <?php endif; ?>
    </div>
  </div>

  <!-- JavaScript -->
  <script src="script.js"></script>
</body>
</html>