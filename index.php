<?php

session_start();

$isLoggedIn = isset($_SESSION["user_id"]);

$userName = $isLoggedIn
    ? $_SESSION["user_name"]
    : "";

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

  <title>TSA — Shop Everything You Love</title>

  <link rel="preconnect"
        href="https://fonts.googleapis.com">

  <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

  <link rel="stylesheet"
        href="style.css">

</head>

<body>

  <!-- ================= HEADER ================= -->

  <header class="topbar">

    <div class="nav">

      <a class="logo"
         href="index.php">

        TSA<span>.</span><small>Shop</small>

      </a>


      <button class="location">

        📍

        <span>
          Deliver to<br>
          <b>India</b>
        </span>

      </button>


      <div class="search-wrap">

        <input
          id="searchInput"
          type="search"
          placeholder="Search for products, brands and more"
        >

        <button id="searchBtn">
          🔍
        </button>

      </div>


      <!-- ================= LOGIN / ACCOUNT ================= -->

      <?php if ($isLoggedIn): ?>

        <a
          class="login-btn"
          id="accountBtn"
          href="dashboard.php"
        >
          👤
          <?php echo htmlspecialchars($userName); ?>
        </a>

        <a
          class="nav-link"
          href="auth/logout.php"
        >
          Logout
        </a>

      <?php else: ?>

        <a
          class="login-btn"
          id="loginBtn"
          href="auth/login.php"
        >
          Login
        </a>

        <a
          class="nav-link"
          href="auth/register.php"
        >
          Register
        </a>

      <?php endif; ?>


      <button class="cart-btn"
              id="cartBtn">

        🛒 Cart

        <b id="cartCount">
          0
        </b>

      </button>

    </div>

  </header>


  <!-- ================= CATEGORIES ================= -->

  <nav class="categories">

    <div
      class="category-inner"
      id="categoryBar">
    </div>

  </nav>


  <!-- ================= MAIN ================= -->

  <main>


    <!-- ================= HERO ================= -->

    <section class="hero">

      <div class="hero-copy">

        <span class="eyebrow">
          TSA BIG SAVINGS
        </span>

        <h1>
          Everything you need.<br>
          <strong>Better prices.</strong>
        </h1>

        <p>
          Discover trending electronics, fashion,
          home essentials and everyday favorites.
        </p>

        <button
          class="shop-now"
          onclick="scrollToProducts()"
        >
          Shop Now →
        </button>

      </div>


      <div class="hero-art">

        <div class="deal-tag">

          UP TO<br>

          <strong>
            70% OFF
          </strong>

        </div>

        <img
          src="https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=1000&q=85"
          alt="Electronics collection"
        >

      </div>

    </section>


    <!-- ================= TRUST ================= -->

    <section class="trust-row">

      <div>
        🚚
        <b>Free Delivery</b>
        <span>On orders over ₹499</span>
      </div>

      <div>
        🔒
        <b>Secure Payments</b>
        <span>100% protected checkout</span>
      </div>

      <div>
        ↩️
        <b>Easy Returns</b>
        <span>7-day hassle-free returns</span>
      </div>

      <div>
        ⭐
        <b>TSA Assured</b>
        <span>Quality checked products</span>
      </div>

    </section>


    <!-- ================= PRODUCTS ================= -->

    <section
      class="section"
      id="productsSection"
    >

      <div class="section-head">

        <div>

          <h2>
            Best Deals
          </h2>

          <p>
            Top picks at prices you'll love
          </p>

        </div>

        <button
          class="view-all"
          onclick="showAll()"
        >
          View All →
        </button>

      </div>


      <div
        class="product-grid"
        id="productGrid"
      ></div>

    </section>


    <!-- ================= TRENDING CATEGORIES ================= -->

    <section class="section">

      <div class="section-head">

        <div>

          <h2>
            Trending Categories
          </h2>

          <p>
            Explore what everyone is shopping
          </p>

        </div>

      </div>


      <div
        class="category-cards"
        id="categoryCards"
      ></div>

    </section>


    <!-- ================= PROMO ================= -->

    <section class="promo">

      <div>

        <span class="eyebrow">
          TSA EXCLUSIVE
        </span>

        <h2>
          Upgrade your everyday.
        </h2>

        <p>
          Fresh arrivals across tech,
          fashion and lifestyle.
        </p>

        <button
          class="shop-now"
          onclick="scrollToProducts()"
        >
          Explore Deals →
        </button>

      </div>


      <img
        src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=900&q=85"
        alt="Watch and accessories"
      >

    </section>

  </main>


  <!-- ================= FOOTER ================= -->

  <footer>

    <div class="footer-main">


      <div>

        <div class="footer-logo">
          TSA<span>.</span>
        </div>

        <p>
          Your everyday online shopping destination.
        </p>

      </div>


      <div>

        <h4>
          ABOUT
        </h4>

        <a href="#">
          Contact Us
        </a>

        <a href="#">
          About TSA
        </a>

        <a href="#">
          Careers
        </a>

      </div>


      <div>

        <h4>
          HELP
        </h4>

        <a href="#">
          Payments
        </a>

        <a href="#">
          Shipping
        </a>

        <a href="#">
          Returns
        </a>

      </div>


      <div>

        <h4>
          POLICY
        </h4>

        <a href="#">
          Terms of Use
        </a>

        <a href="#">
          Privacy
        </a>

        <a href="#">
          Security
        </a>

      </div>


      <div>

        <h4>
          CONNECT
        </h4>

        <a href="#">
          Instagram
        </a>

        <a href="#">
          Facebook
        </a>

        <a href="#">
          YouTube
        </a>

      </div>

    </div>


    <div class="footer-bottom">

      © 2026 TSA Shop. All rights reserved.

    </div>

  </footer>


  <!-- ================= OVERLAY ================= -->

  <div
    class="overlay"
    id="overlay">
  </div>


  <!-- ================= CART ================= -->

  <aside
    class="cart-drawer"
    id="cartDrawer"
  >

    <div class="drawer-head">

      <h2>
        Your Cart
      </h2>

      <button id="closeCart">
        ✕
      </button>

    </div>


    <div id="cartItems"></div>


    <div class="cart-total">

      <span>
        Total
      </span>

      <strong id="cartTotal">
        ₹0
      </strong>

    </div>


    <button class="checkout">
      Proceed to Checkout
    </button>

  </aside>


  <!-- ================= PRODUCT MODAL ================= -->

  <div
    class="product-modal"
    id="productModal"
  >

    <button
      class="modal-close"
      onclick="closeProduct()"
    >
      ✕
    </button>


    <div class="product-detail">


      <div class="detail-gallery">

        <img
          id="detailImage"
          src=""
          alt=""
        >

      </div>


      <div class="detail-content">


        <div
          class="product-cat"
          id="detailCat">
        </div>


        <h2 id="detailName"></h2>


        <span
          class="rating"
          id="detailRating">
        </span>


        <div
          class="detail-price"
          id="detailPrice">
        </div>


        <div
          class="detail-offer"
          id="detailOffer">
        </div>


        <p
          id="detailDescription">
        </p>


        <div class="delivery-box">

          <b>
            🚚 Delivery
          </b>


          <div class="delivery-row">

            <input
              id="pincodeInput"
              maxlength="6"
              placeholder="Enter delivery pincode"
            >

            <button onclick="checkDelivery()">
              Check
            </button>

          </div>


          <div id="deliveryMessage">
            Enter your pincode to check estimated delivery.
          </div>

        </div>


        <div class="detail-actions">

          <button
            class="add detail-add"
            id="detailAdd"
          >
            Add to Cart
          </button>


          <button
            class="buy-now"
            id="detailBuy"
          >
            Buy Now
          </button>

        </div>

      </div>

    </div>

  </div>


  <!-- ================= CHECKOUT ================= -->

  <div
    class="checkout-modal"
    id="checkoutModal"
  >

    <button
      class="modal-close"
      onclick="closeCheckout()"
    >
      ✕
    </button>


    <div class="checkout-grid">


      <div>

        <div class="checkout-title">
          TSA Checkout
        </div>


        <div class="stepper">

          <span class="active">
            1. Address
          </span>

          <span>
            2. Delivery
          </span>

          <span>
            3. Payment
          </span>

        </div>


        <h3>
          Delivery Address
        </h3>


        <div class="form-grid">

          <input
            id="customerName"
            placeholder="Full name"
          >

          <input
            id="customerPhone"
            placeholder="Mobile number"
          >

          <input
            id="customerPin"
            placeholder="Pincode"
          >

          <input
            id="customerCity"
            placeholder="City"
          >

          <input
            id="customerState"
            placeholder="State"
          >

          <input
            id="customerHouse"
            placeholder="House / Flat / Building"
          >

          <textarea
            id="customerAddress"
            placeholder="Area, street, landmark"
          ></textarea>

        </div>


        <h3>
          Delivery Options
        </h3>


        <label class="delivery-option">

          <input
            type="radio"
            name="delivery"
            id="standardDelivery"
            value="standard"
            checked
            onchange="renderCheckout()"
          >

          <span>

            <b>
              Standard Delivery
            </b>

            <small>
              Free on orders ₹499+ • 4–7 business days
            </small>

          </span>

        </label>


        <label class="delivery-option">

          <input
            type="radio"
            name="delivery"
            id="fastDelivery"
            value="fast"
            onchange="renderCheckout()"
          >

          <span>

            <b>
              Fast Delivery
            </b>

            <small>
              ₹99 • 1–3 business days
            </small>

          </span>

        </label>


        <h3>
          Payment Method
        </h3>


        <label class="payment-option">

          <input
            type="radio"
            name="payment"
            value="Cash on Delivery"
            checked
          >

          <span>

            <b>
              Cash on Delivery
            </b>

            <small>
              Pay when your order arrives
            </small>

          </span>

        </label>


        <label class="payment-option">

          <input
            type="radio"
            name="payment"
            value="UPI"
          >

          <span>

            <b>
              UPI
            </b>

            <small>
              Demo payment flow for frontend
            </small>

          </span>

        </label>


        <label class="payment-option">

          <input
            type="radio"
            name="payment"
            value="Credit / Debit Card"
          >

          <span>

            <b>
              Credit / Debit Card
            </b>

            <small>
              Demo payment flow for frontend
            </small>

          </span>

        </label>


        <button
          class="place-order"
          onclick="placeOrder()"
        >
          Place Order
        </button>

      </div>


      <div class="checkout-summary">

        <h3>
          Order Summary
        </h3>


        <div id="checkoutItems"></div>


        <div class="summary-line">

          <span>
            Subtotal
          </span>

          <b id="checkoutSubtotal">
            ₹0
          </b>

        </div>


        <div class="summary-line">

          <span>
            Delivery
          </span>

          <b id="checkoutDelivery">
            FREE
          </b>

        </div>


        <div class="summary-line total">

          <span>
            Total
          </span>

          <b id="checkoutTotal">
            ₹0
          </b>

        </div>


        <div class="secure-note">

          🔒 Secure checkout<br>
          🛡️ TSA buyer protection<br>
          ↩️ 7-day eligible returns

        </div>

      </div>

    </div>

  </div>


  <!-- ================= ORDER SUCCESS ================= -->

  <div
    class="order-success"
    id="orderSuccess"
  >

    <div class="success-icon">
      ✓
    </div>


    <h2>
      Order Placed Successfully!
    </h2>


    <p>
      Your TSA order has been confirmed.
    </p>


    <div class="success-card">


      <div>

        <span>
          Order ID
        </span>

        <b id="successOrderId"></b>

      </div>


      <div>

        <span>
          Payment
        </span>

        <b id="successPayment"></b>

      </div>


      <div>

        <span>
          Total
        </span>

        <b id="successTotal"></b>

      </div>


      <div>

        <span>
          Expected Delivery
        </span>

        <b id="successDelivery"></b>

      </div>

    </div>


    <div class="tracking">


      <div class="track active">

        <i>
          ✓
        </i>

        <span>
          Order Confirmed
        </span>

      </div>


      <div class="track">

        <i>
          2
        </i>

        <span>
          Packed
        </span>

      </div>


      <div class="track">

        <i>
          3
        </i>

        <span>
          Shipped
        </span>

      </div>


      <div class="track">

        <i>
          4
        </i>

        <span>
          Delivered
        </span>

      </div>

    </div>


    <p class="demo-note">

      This frontend demo records the order in your browser.
      Real payment processing, inventory and courier tracking
      require a backend and payment/shipping integrations.

    </p>


    <button
      class="continue-shopping"
      onclick="continueShopping()"
    >
      Continue Shopping
    </button>

  </div>


  <!-- ================= LOGIN MODAL ================= -->

  <!--
      The old frontend-only mobile login has been replaced
      by the real PHP login system.

      Login now happens through:
      auth/login.php

      Registration happens through:
      auth/register.php
  -->


  <!-- ================= JAVASCRIPT ================= -->

  <script src="script.js"></script>

</body>

</html>