// ==========================================================
// TSA SHOP — Main Storefront JavaScript
// ==========================================================

// Global state
let cart = [];
let allProducts = [];
let products = [];
let visibleCount = 50;
let currentCategory = "Top Offers";
let currentProduct = null;

// Initialize cart from localStorage
try {
  const savedCart = JSON.parse(localStorage.getItem("tsa_cart") || "[]");
  cart = Array.isArray(savedCart) ? savedCart : [];
} catch (error) {
  cart = [];
}

// ----------------------------------------------------------
// Built-in Realistic Marketplace Catalog (Guaranteed Fallback)
// Ensures the storefront is always functional even without MySQL
// ----------------------------------------------------------
const FALLBACK_PRODUCTS = [
  {
    id: "fb-1",
    name: "5G Smartphone (8GB RAM, 128GB Storage)",
    brand: "Apex",
    cat: "Mobiles",
    category: "Mobiles",
    price: 18999,
    old: 24999,
    discount: 24,
    rate: "4.6",
    stock: 45,
    img: "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=600&q=80",
    description: "Next-gen 5G processing, 6.7-inch AMOLED 120Hz display, 5000mAh battery with 67W Turbo charging and 64MP AI triple camera."
  },
  {
    id: "fb-2",
    name: "Ultra Flagship Smartphone (12GB RAM, 256GB)",
    brand: "Zenith",
    cat: "Mobiles",
    category: "Mobiles",
    price: 34999,
    old: 44999,
    discount: 22,
    rate: "4.8",
    stock: 30,
    img: "https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?auto=format&fit=crop&w=600&q=80",
    description: "Premium curved display, 108MP OIS camera, cinematic night mode, 5000mAh battery and fast wireless charging support."
  },
  {
    id: "fb-3",
    name: "Active Noise Cancelling Wireless Headphones",
    brand: "SonicPro",
    cat: "Electronics",
    category: "Electronics",
    price: 3499,
    old: 7999,
    discount: 56,
    rate: "4.7",
    stock: 60,
    img: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80",
    description: "40mm titanium drivers, 40 hours battery life, plush memory foam ear cushions, multipoint Bluetooth 5.3 connection."
  },
  {
    id: "fb-4",
    name: "True Wireless Earbuds with Dual Mic ANC",
    brand: "EchoBeats",
    cat: "Electronics",
    category: "Electronics",
    price: 1699,
    old: 3999,
    discount: 57,
    rate: "4.4",
    stock: 85,
    img: "https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80",
    description: "36-hour total playback with sleek charging case, IPX5 sweat resistance, low latency gaming mode and deep bass profile."
  },
  {
    id: "fb-5",
    name: "1.4-inch AMOLED Smartwatch with Calling",
    brand: "Kronos",
    cat: "Electronics",
    category: "Electronics",
    price: 2299,
    old: 5499,
    discount: 58,
    rate: "4.5",
    stock: 70,
    img: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80",
    description: "Always-On display, continuous heart rate and SpO2 tracker, 100+ sports modes, 7-day battery life, zinc alloy metallic frame."
  },
  {
    id: "fb-6",
    name: "Men Slim Fit Oxford Cotton Casual Shirt",
    brand: "UrbanThread",
    cat: "Fashion",
    category: "Fashion",
    price: 899,
    old: 1999,
    discount: 55,
    rate: "4.3",
    stock: 120,
    img: "https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=600&q=80",
    description: "100% breathable combed cotton, button-down collar, curved hemline, versatile for office or weekend casual wear."
  },
  {
    id: "fb-7",
    name: "Women Embroidered Floral Anarkali Kurta",
    brand: "VogueVedic",
    cat: "Fashion",
    category: "Fashion",
    price: 1299,
    old: 2899,
    discount: 55,
    rate: "4.6",
    stock: 90,
    img: "https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=600&q=80",
    description: "Graceful rayon blend fabric, fine zari embroidery detailing, flared silhouette, paired with matching sheer dupatta."
  },
  {
    id: "fb-8",
    name: "Men Lightweight Cushion Running Shoes",
    brand: "AeroStride",
    cat: "Footwear",
    category: "Footwear",
    price: 1499,
    old: 3299,
    discount: 54,
    rate: "4.4",
    stock: 80,
    img: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80",
    description: "Breathable knit mesh upper, shock-absorbing responsive EVA sole, high-traction anti-slip grip for running and training."
  },
  {
    id: "fb-9",
    name: "Modern Minimalist Wooden Bedside Table",
    brand: "HearthCraft",
    cat: "Home",
    category: "Home",
    price: 2199,
    old: 4500,
    discount: 51,
    rate: "4.5",
    stock: 35,
    img: "https://images.unsplash.com/photo-1532372320572-cda25653a26d?auto=format&fit=crop&w=600&q=80",
    description: "Engineered solid wood with water-resistant walnut finish, smooth slide drawer and open bottom utility shelf."
  },
  {
    id: "fb-10",
    name: "Ultra Soft 100% Microfiber Queen Bed Sheet",
    brand: "ComfortNest",
    cat: "Home",
    category: "Home",
    price: 699,
    old: 1599,
    discount: 56,
    rate: "4.3",
    stock: 110,
    img: "https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?auto=format&fit=crop&w=600&q=80",
    description: "300 TC breathable, wrinkle-resistant microfiber bedsheet with two matching envelope closure pillowcases."
  },
  {
    id: "fb-11",
    name: "Vitamin C + Hyaluronic Acid Brightening Serum",
    brand: "AuraGlow",
    cat: "Beauty",
    category: "Beauty",
    price: 549,
    old: 1199,
    discount: 54,
    rate: "4.7",
    stock: 150,
    img: "https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=600&q=80",
    description: "Dermatologist tested, 10% pure active Vitamin C, boosts natural skin radiance, reduces dark spots and hydrates."
  },
  {
    id: "fb-12",
    name: "750W 3-Jar Stainless Steel Mixer Grinder",
    brand: "KitchenStar",
    cat: "Appliances",
    category: "Appliances",
    price: 2499,
    old: 4999,
    discount: 50,
    rate: "4.5",
    stock: 40,
    img: "https://images.unsplash.com/photo-1588854337236-6889d631faa8?auto=format&fit=crop&w=600&q=80",
    description: "Heavy-duty copper motor, 3 multi-purpose stainless steel jars with flow breakers, overload safety switch."
  },
  {
    id: "fb-13",
    name: "Organic Cold Pressed Virgin Coconut Oil (1L)",
    brand: "NaturePure",
    cat: "Grocery",
    category: "Grocery",
    price: 429,
    old: 650,
    discount: 34,
    rate: "4.6",
    stock: 100,
    img: "https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80",
    description: "100% pure and unrefined, extracted from fresh coconuts, ideal for healthy cooking, baking and hair care."
  },
  {
    id: "fb-14",
    name: "High-Density Anti-Tear Yoga Mat (6mm)",
    brand: "FitPulse",
    cat: "Sports",
    category: "Sports",
    price: 799,
    old: 1799,
    discount: 55,
    rate: "4.4",
    stock: 65,
    img: "https://images.unsplash.com/photo-1517649763962-0c623266ddc0?auto=format&fit=crop&w=600&q=80",
    description: "Eco-friendly TPE material with textured non-slip grip, alignment guide marks, carry strap included."
  }
];

const categories = [
  ["Mobiles", "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=500&q=80"],
  ["Electronics", "https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=500&q=80"],
  ["Fashion", "https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=500&q=80"],
  ["Footwear", "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=500&q=80"],
  ["Home", "https://images.unsplash.com/photo-1484101403633-562f891dc89a?auto=format&fit=crop&w=500&q=80"],
  ["Beauty", "https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=500&q=80"]
];

const navCats = ["Top Offers", "Mobiles", "Electronics", "Fashion", "Footwear", "Home", "Appliances", "Beauty", "Grocery", "Sports"];

// ----------------------------------------------------------
// Cart Helpers
// ----------------------------------------------------------
function saveCart() {
  localStorage.setItem("tsa_cart", JSON.stringify(cart));
}

function money(n) {
  return "₹" + Number(n || 0).toLocaleString("en-IN");
}

function cartCount() {
  return cart.reduce((sum, item) => sum + (Number(item.qty) || 1), 0);
}

function cartTotal() {
  return cart.reduce((sum, item) => sum + (Number(item.price) || 0) * (Number(item.qty) || 1), 0);
}

function cartSubtotal() {
  return cartTotal();
}

function normalizeCartItem(p, qty = 1) {
  return {
    id: String(p.id || Math.random()),
    name: p.name || "Product",
    brand: p.brand || "TSA",
    price: Number(p.price) || 0,
    old: Number(p.old || p.mrp || p.price || 0),
    img: p.img || p.image || "",
    cat: p.cat || p.category || p.category_name || "Catalog",
    qty: Math.max(1, Number(qty) || 1)
  };
}

function addToCart(product, qty = 1) {
  if (!product) return;

  const item = normalizeCartItem(product, qty);
  const existing = cart.find(x => String(x.id) === String(item.id));

  if (existing) {
    existing.qty = (Number(existing.qty) || 1) + (Number(qty) || 1);
  } else {
    cart.push(item);
  }

  saveCart();
  renderCart();
  openCart();
}

function addToCartById(id, qty = 1) {
  const p = allProducts.find(x => String(x.id) === String(id));
  if (p) {
    addToCart(p, qty);
  }
}

function changeCartQty(index, delta) {
  if (!cart[index]) return;

  cart[index].qty = (Number(cart[index].qty) || 1) + delta;

  if (cart[index].qty <= 0) {
    cart.splice(index, 1);
  }

  saveCart();
  renderCart();
}

function removeCart(index) {
  if (!cart[index]) return;

  cart.splice(index, 1);
  saveCart();
  renderCart();
}

function renderCart() {
  const countEl = document.getElementById("cartCount");
  if (countEl) countEl.textContent = cartCount();

  const itemsEl = document.getElementById("cartItems");
  const totalEl = document.getElementById("cartTotal");
  if (!itemsEl) return;

  if (!cart.length) {
    itemsEl.innerHTML = `
      <div style="text-align:center;padding:48px 16px;color:#7a8798;">
        <div style="font-size:42px;margin-bottom:12px;">🛒</div>
        <b style="color:#1e293b;display:block;margin-bottom:6px;">Your cart is empty</b>
        <span style="font-size:13px;">Add items to your cart to start checkout</span>
      </div>
    `;
  } else {
    itemsEl.innerHTML = cart.map((item, index) => `
      <div class="cart-item">
        <img src="${item.img || ''}" alt="${item.name || 'Product'}" onerror="this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=120&q=80'">
        <div class="cart-item-info">
          <b>${item.name || 'Product'}</b>
          <p>${money(item.price)} × ${item.qty}</p>
          <div class="cart-qty">
            <button type="button" data-cart-minus="${index}" aria-label="Decrease quantity">−</button>
            <span>${item.qty}</span>
            <button type="button" data-cart-plus="${index}" aria-label="Increase quantity">+</button>
          </div>
          <button class="remove" type="button" data-cart-remove="${index}">Remove</button>
        </div>
      </div>
    `).join("");
  }

  if (totalEl) totalEl.textContent = money(cartTotal());
}

function openCart() {
  const drawer = document.getElementById("cartDrawer");
  const overlay = document.getElementById("overlay");
  if (drawer) drawer.classList.add("open");
  if (overlay) overlay.classList.add("show");
}

function closeCart() {
  const drawer = document.getElementById("cartDrawer");
  const overlay = document.getElementById("overlay");
  if (drawer) drawer.classList.remove("open");
  if (overlay) overlay.classList.remove("show");
}

// ----------------------------------------------------------
// Catalog Loading (MySQL API with Resilient Fallback)
// ----------------------------------------------------------
function normalizeCatalogProduct(p) {
  const price = Number(p.price) || 0;
  const oldPrice = Number(p.mrp || p.old || price);
  const discount = Number(p.discount) || (oldPrice > price ? Math.round((1 - price / oldPrice) * 100) : 0);

  return {
    id: String(p.id),
    name: p.name || p.title || "Product",
    brand: p.brand || "TSA",
    cat: p.category_name || p.cat || p.category || "General",
    category: p.category_name || p.category || p.cat || "General",
    price: price,
    old: oldPrice,
    discount: discount,
    rate: Number(p.rating || 4.2).toFixed(1),
    stock: Number(p.stock || 20),
    img: p.image || p.img || "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80",
    description: p.description || `${p.name} from ${p.brand || 'TSA'}. Authentic quality assured.`
  };
}

async function loadCatalog() {
  try {
    const res = await fetch("api/products.php", { cache: "no-store" });
    if (res.ok) {
      const data = await res.json();
      if (data.success && Array.isArray(data.products) && data.products.length > 0) {
        allProducts = data.products.map(normalizeCatalogProduct);
        products = [...allProducts];
        window.products = products;
        renderNav();
        renderCategoryCards();
        filterCat("Top Offers");
        return;
      }
    }
  } catch (err) {
    console.warn("Live API unavailable, utilizing built-in catalog fallback:", err);
  }

  // Graceful fallback to built-in realistic catalog
  allProducts = FALLBACK_PRODUCTS.map(normalizeCatalogProduct);
  products = [...allProducts];
  window.products = products;
  renderNav();
  renderCategoryCards();
  filterCat("Top Offers");
}

// ----------------------------------------------------------
// Product Grid & Category Filters
// ----------------------------------------------------------
function renderProducts(list = products, append = false) {
  const grid = document.getElementById("productGrid");
  if (!grid) return;

  if (!append) visibleCount = 50;
  const safeList = Array.isArray(list) ? list : [];
  const shown = safeList.slice(0, visibleCount);

  if (!shown.length) {
    grid.innerHTML = `
      <div style="grid-column: 1 / -1; text-align: center; padding: 40px 10px; color: #64748b;">
        <h3>No matching products found</h3>
        <p>Try searching with another keyword or browse our trending categories.</p>
      </div>
    `;
    return;
  }

  const cardsHtml = shown.map(p => {
    const price = Number(p.price) || 0;
    const oldPrice = Number(p.old) || price;
    const off = Number(p.discount) || (oldPrice > price ? Math.round((1 - price / oldPrice) * 100) : 0);

    return `
      <article class="product" data-product-id="${p.id}" onclick="openProduct('${p.id}')">
        ${off > 0 ? `<span class="badge">${off}% OFF</span>` : ''}
        <button class="heart" type="button" onclick="event.stopPropagation();this.textContent=this.textContent==='♡'?'♥':'♡'" aria-label="Add to wishlist">♡</button>
        <div class="product-img">
          <img src="${p.img}" alt="${p.name}" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=400&q=80'">
        </div>
        <div class="product-info">
          <div class="product-cat">${p.brand} • ${p.cat}</div>
          <div class="product-name" title="${p.name}">${p.name}</div>
          <span class="rating">${p.rate} <span>★</span></span>
          <div class="price">
            ${money(price)}
            ${oldPrice > price ? `<span class="old">${money(oldPrice)}</span><span class="off">${off}% off</span>` : ''}
          </div>
          <button class="add" type="button" data-action="add-cart" data-id="${p.id}">Add to Cart</button>
        </div>
      </article>
    `;
  }).join("");

  grid.innerHTML = cardsHtml;
}

function renderNav() {
  const bar = document.getElementById("categoryBar");
  if (!bar) return;
  bar.innerHTML = navCats.map(cat => `
    <button class="cat-item ${cat === currentCategory ? 'active' : ''}" type="button" onclick="filterCat('${cat}')">
      ${cat}
    </button>
  `).join("");
}

function renderCategoryCards() {
  const cards = document.getElementById("categoryCards");
  if (!cards) return;
  cards.innerHTML = categories.map(c => `
    <div class="category-card" onclick="filterCat('${c[0]}')">
      <img src="${c[1]}" alt="${c[0]}" loading="lazy">
      <span>${c[0]} →</span>
    </div>
  `).join("");
}

function filterCat(cat) {
  currentCategory = cat;

  // Update active state on nav buttons
  document.querySelectorAll(".cat-item").forEach(btn => {
    if (btn.textContent.trim() === cat) {
      btn.classList.add("active");
    } else {
      btn.classList.remove("active");
    }
  });

  let found = [];
  if (cat === "Top Offers") {
    found = allProducts;
  } else {
    found = allProducts.filter(p => (p.cat || "").toLowerCase().includes(cat.toLowerCase()) || (p.category || "").toLowerCase().includes(cat.toLowerCase()));
  }

  const titleEl = document.querySelector("#productsSection h2");
  const metaEl = document.querySelector("#productsSection .section-head p");

  if (titleEl) titleEl.textContent = cat === "Top Offers" ? "Best Deals" : `${cat} Catalog`;
  if (metaEl) metaEl.textContent = cat === "Top Offers" ? "Top picks from our catalog at prices you'll love" : `Explore ${found.length} items in ${cat}`;

  renderProducts(found);
}

function showAll() {
  filterCat("Top Offers");
}

function scrollToProducts() {
  const section = document.getElementById("productsSection");
  if (section) section.scrollIntoView({ behavior: "smooth" });
}

// ----------------------------------------------------------
// Product Detail Modal
// ----------------------------------------------------------
function openProduct(id) {
  const p = allProducts.find(x => String(x.id) === String(id));
  if (!p) return;

  currentProduct = p;
  window.currentProduct = p;

  const detailImage = document.getElementById("detailImage");
  const detailCat = document.getElementById("detailCat");
  const detailName = document.getElementById("detailName");
  const detailRating = document.getElementById("detailRating");
  const detailPrice = document.getElementById("detailPrice");
  const detailOffer = document.getElementById("detailOffer");
  const detailDescription = document.getElementById("detailDescription");
  const deliveryMessage = document.getElementById("deliveryMessage");
  const pincodeInput = document.getElementById("pincodeInput");
  const productModal = document.getElementById("productModal");
  const overlay = document.getElementById("overlay");

  if (detailImage) {
    detailImage.src = p.img;
    detailImage.alt = p.name;
  }
  if (detailCat) detailCat.textContent = `${p.cat || 'Catalog'} • ${p.brand || 'TSA'}`;
  if (detailName) detailName.textContent = p.name;
  if (detailRating) detailRating.innerHTML = `${p.rate || '4.2'} <span>★</span>`;
  if (detailPrice) detailPrice.textContent = money(p.price);

  const discountVal = Number(p.discount) || (p.old > p.price ? Math.round((1 - p.price / p.old) * 100) : 0);
  if (detailOffer) {
    detailOffer.textContent = `${discountVal}% off • MRP ${money(p.old || p.price)} • Inclusive of all taxes`;
  }
  if (detailDescription) detailDescription.textContent = p.description || "";
  if (deliveryMessage) {
    deliveryMessage.textContent = "Enter your pincode to check estimated delivery date.";
    deliveryMessage.style.color = "#667487";
  }
  if (pincodeInput) pincodeInput.value = "";

  if (productModal) productModal.classList.add("show");
  if (overlay) overlay.classList.add("show");
}

function closeProduct() {
  const modal = document.getElementById("productModal");
  const overlay = document.getElementById("overlay");
  if (modal) modal.classList.remove("show");
  if (overlay) overlay.classList.remove("show");
}

function checkDelivery() {
  const pinEl = document.getElementById("pincodeInput");
  const box = document.getElementById("deliveryMessage");
  if (!pinEl || !box) return;

  const pin = pinEl.value.trim();
  if (!/^\d{6}$/.test(pin)) {
    box.textContent = "Please enter a valid 6-digit Indian pincode.";
    box.style.color = "#d93025";
    return;
  }

  const days = (parseInt(pin.slice(-1), 10) % 3) + 3;
  const d = new Date();
  d.setDate(d.getDate() + days);
  const date = d.toLocaleDateString("en-IN", { day: "numeric", month: "short" });

  box.textContent = `✓ Delivery available to ${pin}. Expected by ${date} (${days}–${days + 2} business days).`;
  box.style.color = "#168c47";
}

function buyCurrentProduct() {
  if (!currentProduct) return;
  addToCart(currentProduct, 1);
  closeProduct();
  setTimeout(openCheckout, 80);
}

// ----------------------------------------------------------
// Checkout & Order Processing
// ----------------------------------------------------------
function openCheckout() {
  if (!cart.length) {
    alert("Your cart is empty. Add a product first.");
    return;
  }
  closeCart();
  renderCheckout();

  const modal = document.getElementById("checkoutModal");
  const overlay = document.getElementById("overlay");
  if (modal) modal.classList.add("show");
  if (overlay) overlay.classList.add("show");
}

function renderCheckout() {
  const checkoutItems = document.getElementById("checkoutItems");
  if (checkoutItems) {
    checkoutItems.innerHTML = cart.map(item => `
      <div class="checkout-item">
        <img src="${item.img || ''}" alt="${item.name}" onerror="this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=120&q=80'">
        <div>
          <b>${item.name}</b>
          <small>${item.brand} • Qty ${item.qty} • ${money((Number(item.price) || 0) * Number(item.qty || 1))}</small>
        </div>
      </div>
    `).join("");
  }

  const subtotal = cartSubtotal();
  const fast = document.getElementById("fastDelivery")?.checked;
  const delivery = fast ? 99 : (subtotal >= 499 ? 0 : 49);

  const subtotalEl = document.getElementById("checkoutSubtotal");
  const deliveryEl = document.getElementById("checkoutDelivery");
  const totalEl = document.getElementById("checkoutTotal");

  if (subtotalEl) subtotalEl.textContent = money(subtotal);
  if (deliveryEl) deliveryEl.textContent = delivery ? money(delivery) : "FREE";
  if (totalEl) totalEl.textContent = money(subtotal + delivery);
}

function closeCheckout() {
  const modal = document.getElementById("checkoutModal");
  const overlay = document.getElementById("overlay");
  if (modal) modal.classList.remove("show");
  if (overlay) overlay.classList.remove("show");
}

function validateCheckout() {
  const fields = {
    customerName: "Full name",
    customerPhone: "10-digit mobile number",
    customerPin: "6-digit pincode",
    customerCity: "City",
    customerState: "State",
    customerHouse: "House / Flat / Building No.",
    customerAddress: "Full delivery address"
  };

  for (const [id, label] of Object.entries(fields)) {
    const el = document.getElementById(id);
    if (!el || !el.value.trim()) {
      if (el) el.focus();
      alert(`Please enter your ${label}.`);
      return false;
    }
  }

  const phone = document.getElementById("customerPhone").value.replace(/[^0-9]/g, "");
  if (phone.length !== 10) {
    alert("Please enter a valid 10-digit mobile number.");
    document.getElementById("customerPhone").focus();
    return false;
  }

  const pin = document.getElementById("customerPin").value.trim();
  if (!/^\d{6}$/.test(pin)) {
    alert("Please enter a valid 6-digit Indian pincode.");
    document.getElementById("customerPin").focus();
    return false;
  }

  return true;
}

function placeOrder() {
  if (!validateCheckout()) return;

  const subtotal = cartSubtotal();
  const fastDelivery = document.getElementById("fastDelivery")?.checked;
  const delivery = fastDelivery ? 99 : (subtotal >= 499 ? 0 : 49);
  const payment = document.querySelector('input[name="payment"]:checked')?.value || "Cash on Delivery";
  const orderId = "TSA" + Date.now().toString().slice(-8);
  const days = fastDelivery ? 2 : 5;
  const deliveryDate = new Date();
  deliveryDate.setDate(deliveryDate.getDate() + days);
  const formattedDate = deliveryDate.toLocaleDateString("en-IN", { day: "numeric", month: "short", year: "numeric" });

  const orderRecord = {
    id: orderId,
    total: subtotal + delivery,
    payment: payment,
    date: formattedDate,
    items: [...cart],
    placedAt: new Date().toISOString()
  };

  // Save to persistent orders array in localStorage
  try {
    const existingOrders = JSON.parse(localStorage.getItem("tsa_orders") || "[]");
    const updated = Array.isArray(existingOrders) ? existingOrders : [];
    updated.push(orderRecord);
    localStorage.setItem("tsa_orders", JSON.stringify(updated));
  } catch (e) {
    console.warn("Could not write order history:", e);
  }

  // Also save last order
  localStorage.setItem("tsaLastOrder", JSON.stringify(orderRecord));

  // Populate success view
  const successOrderId = document.getElementById("successOrderId");
  const successDelivery = document.getElementById("successDelivery");
  const successTotal = document.getElementById("successTotal");
  const successPayment = document.getElementById("successPayment");

  if (successOrderId) successOrderId.textContent = "#" + orderId;
  if (successDelivery) successDelivery.textContent = formattedDate;
  if (successTotal) successTotal.textContent = money(orderRecord.total);
  if (successPayment) successPayment.textContent = payment;

  const checkoutModal = document.getElementById("checkoutModal");
  const orderSuccess = document.getElementById("orderSuccess");
  const overlay = document.getElementById("overlay");

  if (checkoutModal) checkoutModal.classList.remove("show");
  if (orderSuccess) orderSuccess.classList.add("show");
  if (overlay) overlay.classList.add("show");

  // Reset cart
  cart = [];
  saveCart();
  renderCart();
}

function closeSuccess() {
  const modal = document.getElementById("orderSuccess");
  const overlay = document.getElementById("overlay");
  if (modal) modal.classList.remove("show");
  if (overlay) overlay.classList.remove("show");
}

function continueShopping() {
  closeSuccess();
  window.scrollTo({ top: 0, behavior: "smooth" });
}

// ----------------------------------------------------------
// Search Engine
// ----------------------------------------------------------
function search() {
  const searchInput = document.getElementById("searchInput");
  if (!searchInput) return;

  const q = searchInput.value.trim().toLowerCase();

  let found = allProducts;
  if (q) {
    found = allProducts.filter(p => {
      const matchName = (p.name || "").toLowerCase().includes(q);
      const matchBrand = (p.brand || "").toLowerCase().includes(q);
      const matchCat = (p.cat || "").toLowerCase().includes(q);
      const matchDesc = (p.description || "").toLowerCase().includes(q);
      return matchName || matchBrand || matchCat || matchDesc;
    });
  }

  const titleEl = document.querySelector("#productsSection h2");
  const metaEl = document.querySelector("#productsSection .section-head p");

  if (titleEl) titleEl.textContent = q ? `Search: "${q}"` : "Best Deals";
  if (metaEl) metaEl.textContent = `${found.length} product${found.length === 1 ? '' : 's'} found`;

  renderProducts(found);
  scrollToProducts();
}

// ----------------------------------------------------------
// Event Listeners & Initialization
// ----------------------------------------------------------
function initShoppingUI() {
  const cartBtn = document.getElementById("cartBtn");
  const detailAdd = document.getElementById("detailAdd");
  const detailBuy = document.getElementById("detailBuy");
  const closeCartBtn = document.getElementById("closeCart");
  const overlay = document.getElementById("overlay");
  const searchBtn = document.getElementById("searchBtn");
  const searchInput = document.getElementById("searchInput");
  const checkoutTrigger = document.querySelector(".checkout");

  if (cartBtn) cartBtn.onclick = openCart;
  if (detailAdd) {
    detailAdd.onclick = () => {
      if (currentProduct) {
        addToCart(currentProduct, 1);
        closeProduct();
      }
    };
  }
  if (detailBuy) detailBuy.onclick = buyCurrentProduct;
  if (checkoutTrigger) checkoutTrigger.onclick = openCheckout;
  if (closeCartBtn) closeCartBtn.onclick = closeCart;

  if (overlay) {
    overlay.onclick = () => {
      closeCart();
      closeProduct();
      closeCheckout();
      closeSuccess();
    };
  }

  if (searchBtn) searchBtn.onclick = search;
  if (searchInput) {
    searchInput.addEventListener("keydown", e => {
      if (e.key === "Enter") {
        e.preventDefault();
        search();
      }
    });
  }
}

document.addEventListener("DOMContentLoaded", function () {
  initShoppingUI();
  renderCart();
  loadCatalog();
});

// Event delegation for cart actions & product adding
document.addEventListener("click", function(event) {
  const addButton = event.target.closest('[data-action="add-cart"]');
  if (addButton) {
    event.preventDefault();
    event.stopPropagation();
    const productId = addButton.dataset.id;
    if (productId) {
      addToCartById(productId, 1);
    }
    return;
  }

  const plus = event.target.closest("[data-cart-plus]");
  if (plus) {
    event.preventDefault();
    changeCartQty(Number(plus.dataset.cartPlus), 1);
    return;
  }

  const minus = event.target.closest("[data-cart-minus]");
  if (minus) {
    event.preventDefault();
    changeCartQty(Number(minus.dataset.cartMinus), -1);
    return;
  }

  const remove = event.target.closest("[data-cart-remove]");
  if (remove) {
    event.preventDefault();
    removeCart(Number(remove.dataset.cartRemove));
    return;
  }
});
