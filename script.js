let cart = [];

try {
  const savedCart = JSON.parse(localStorage.getItem("tsa_cart") || "[]");
  cart = Array.isArray(savedCart) ? savedCart : [];
} catch (error) {
  cart = [];
}

function saveCart() {
  localStorage.setItem("tsa_cart", JSON.stringify(cart));
}

function productKey(p) {
  return String(p && (p.id ?? p.name ?? Math.random()));
}

function normalizeCartItem(p) {
  return {
    id: productKey(p),
    name: p.name || "Product",
    brand: p.brand || "",
    price: Number(p.price) || 0,
    old: Number(p.old) || Number(p.price) || 0,
    img: p.img || "",
    cat: p.cat || p.category || "",
    qty: Math.max(1, Number(p.qty) || 1)
  };
}

function addToCart(product) {
  if (!product) return;

  const item = normalizeCartItem(product);
  const existing = cart.find(x => String(x.id) === String(item.id));

  if (existing) {
    existing.qty = (Number(existing.qty) || 1) + 1;
  } else {
    cart.push(item);
  }

  saveCart();
  renderCart();
  openCart();
}

function addToCartByName(name, qty = 1) {
  const catalog = window.products || products || [];
  const product = catalog.find(p => p.name === name);

  if (!product) return;

  const normalized = normalizeCartItem({
    ...product,
    id: product.id,
    name: product.name,
    price: product.price ?? product.old ?? 0,
    old: product.old ?? product.price ?? 0,
    img: product.img || product.image || product.thumbnail || "",
    cat: product.cat || product.category || product.apiCategory || "",
    qty
  });

  const existing = cart.find(item => String(item.id) === String(normalized.id));
  if (existing) {
    existing.qty = (Number(existing.qty) || 1) + (Number(qty) || 1);
  } else {
    cart.push({ ...normalized, qty: Math.max(1, Number(qty) || 1) });
  }

  saveCart();
  renderCart();
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

function cartCount() {
  return cart.reduce((sum, item) => sum + (Number(item.qty) || 1), 0);
}

function cartTotal() {
  return cart.reduce((sum, item) => sum + (Number(item.price) || 0) * (Number(item.qty) || 1), 0);
}

function renderCart(){
  const countEl = document.getElementById("cartCount");
  if(countEl) countEl.textContent = cartCount();

  const itemsEl = document.getElementById("cartItems");
  const totalEl = document.getElementById("cartTotal");
  if(!itemsEl) return;

  if(!cart.length){
    itemsEl.innerHTML = '<p style="color:#7a8798;text-align:center;margin-top:40px">Your cart is empty.</p>';
  }else{
    itemsEl.innerHTML = cart.map((item,index)=>`
      <div class="cart-item">
        <img src="${item.img || ""}" alt="${item.name || "Product"}">
        <div class="cart-item-info">
          <b>${item.name || "Product"}</b>
          <p>${money(item.price)} × ${item.qty}</p>
          <div class="cart-qty">
            <button type="button" data-cart-minus="${index}">−</button>
            <span>${item.qty}</span>
            <button type="button" data-cart-plus="${index}">+</button>
          </div>
          <button class="remove" type="button" data-cart-remove="${index}">Remove</button>
        </div>
      </div>`).join("");
  }

  if(totalEl) totalEl.textContent = money(cartTotal());
}

function money(n) {
  return "₹" + Number(n || 0).toLocaleString("en-IN");
}

async function loadProductsFromDatabase() {
    const productGrid = document.getElementById("productGrid");

    if (!productGrid) {
        return;
    }

    productGrid.innerHTML = "<p>Loading products...</p>";

    try {
        const response = await fetch("api/products.php");
        const data = await response.json();

        if (!data.success) {
            productGrid.innerHTML = "<p>Unable to load products.</p>";
            console.error(data.message);
            return;
        }

        window.products = data.products;
        products = data.products;
        renderDatabaseProducts();
    } catch (error) {
        console.error("Product loading error:", error);
        productGrid.innerHTML = "<p>Unable to connect to the product server.</p>";
    }
}

function renderDatabaseProducts() {
    const productGrid = document.getElementById("productGrid");

    if (!productGrid) {
        return;
    }

    productGrid.innerHTML = "";

    products.forEach(product => {
        const card = document.createElement("div");
        card.className = "product-card";

        card.innerHTML = `
            <div class="product-image">
                <img src="${product.image}" alt="${product.name}">
            </div>

            <div class="product-info">
                <div class="product-cat">${product.category_name || "TSA Shop"}</div>
                <h3>${product.name}</h3>
                <span class="rating">⭐ ${product.rating}</span>

                <div class="price-row">
                    <strong>₹${Number(product.price).toLocaleString("en-IN")}</strong>
                    <del>₹${Number(product.mrp).toLocaleString("en-IN")}</del>
                    <span class="discount">${product.discount}% OFF</span>
                </div>

                <button class="add" onclick="openDatabaseProduct(${product.id})">View Product</button>
            </div>
        `;

        productGrid.appendChild(card);
    });
}

function openDatabaseProduct(id) {
    const product = products.find(p => Number(p.id) === Number(id));

    if (!product) {
        return;
    }

    const modal = document.getElementById("productModal");
    if (!modal) return;

    document.getElementById("detailImage").src = product.image;
    document.getElementById("detailName").textContent = product.name;
    document.getElementById("detailCat").textContent = product.category_name || "TSA Shop";
    document.getElementById("detailRating").textContent = "⭐ " + product.rating;
    document.getElementById("detailPrice").textContent = "₹" + Number(product.price).toLocaleString("en-IN");
    document.getElementById("detailOffer").textContent = product.discount + "% OFF";
    document.getElementById("detailDescription").textContent = product.description;

    modal.classList.add("show");
    window.currentDatabaseProduct = product;
}

function openCart(){
  const drawer=document.getElementById("cartDrawer");
  const overlay=document.getElementById("overlay");
  if(drawer) drawer.classList.add("open");
  if(overlay) overlay.classList.add("show");
}

function closeCart(){
  const drawer=document.getElementById("cartDrawer");
  const overlay=document.getElementById("overlay");
  if(drawer) drawer.classList.remove("open");
  if(overlay) overlay.classList.remove("show");
}

const PRODUCT_API = "api/products.php";
let products=[];
let allProducts=[];

function normalizeCatalogProduct(product, fallbackCategory = "Other") {
  const name = product.name || product.title || "Product";
  const category = product.category_name || product.category || fallbackCategory;
  const price = Number(product.price) || 0;
  const oldPrice = Number(product.mrp) || Number(product.old) || price;
  const rating = Number(product.rating) || 4.2;
  const image = product.image || product.thumbnail || (Array.isArray(product.images) ? product.images[0] : "");

  return {
    id: product.id,
    title: name,
    name,
    brand: product.brand || "TSA",
    category,
    cat: category,
    apiCategory: category,
    price,
    old: oldPrice,
    rate: Number(rating).toFixed(1),
    img: image,
    image,
    thumbnail: image,
    images: image ? [image] : [],
    description: product.description || "",
    discount: Number(product.discount) || 0,
    rating,
    stock: Number(product.stock) || 0,
    shippingInformation: "Delivery available",
    tags: []
  };
}

// Each navigation category is built from real product records returned by DummyJSON.
// The exact title, brand and image URL stay together, so a product never receives a random photo.
const navPools={
  "Mobiles":["smartphones","mobile-accessories","tablets","laptops"],
  "Fashion":["mens-shirts","mens-shoes","womens-dresses","womens-shoes","tops","womens-bags","sunglasses","mens-watches","womens-watches"],
  "Electronics":["laptops","mobile-accessories","tablets","smartphones"],
  "Home":["furniture","home-decoration","kitchen-accessories"],
  "Appliances":["kitchen-accessories","home-decoration","furniture"],
  "Beauty":["beauty","skin-care","fragrances","womens-jewellery"],
  "Grocery":["groceries","kitchen-accessories"],
  "Travel":["womens-bags","sunglasses","mens-watches","womens-watches"],
  "Toys":["sports-accessories","tops","womens-dresses"],
  "Sports":["sports-accessories","mens-shoes","womens-shoes"]
};

function normalizeProduct(p,cat){
  const img=(p.images&&p.images[0])||p.thumbnail;
  const price=Math.max(99,Math.round(Number(p.price||0)*83));
  const discount=Number(p.discountPercentage||0);
  return {
    id:`api-${p.id}-${cat}`,
    sourceId:p.id,
    name:p.title,
    brand:p.brand||"Marketplace Seller",
    cat,
    apiCategory:p.category,
    price,
    old:Math.round(price/(1-Math.min(discount,70)/100)),
    rate:Number(p.rating||4.2).toFixed(1),
    img,
    description:p.description||`${p.title} from ${p.brand||"Marketplace Seller"}. Product details and the matching product image are supplied by the catalog record.`,
    stock:p.stock||10,
    shipping:p.shippingInformation||"Delivery available"
  };
}

function uniqueById(arr){
  const seen=new Set();return arr.filter(p=>!seen.has(p.id)&&(seen.add(p.id),true));
}
function buildPool(cat){
  const slugs=navPools[cat]||[];
  let exact=[];
  slugs.forEach(slug=>exact.push(...allProducts.filter(p=>p.category===slug)));
  exact=uniqueById(exact);
  // If a navigation has fewer than 50 exact-category records, fill only with closely related
  // records from the same marketplace dataset; the product's own title/image/brand are unchanged.
  if(exact.length<50){
    const remaining=allProducts.filter(p=>!exact.some(x=>x.id===p.id));
    const scored=remaining.map(p=>({p,score:relatedScore(p,cat)})).sort((a,b)=>b.score-a.score);
    exact.push(...scored.map(x=>x.p).slice(0,50-exact.length));
  }
  return exact.slice(0,50).map(p=>normalizeProduct(p,cat));
}
function relatedScore(p,cat){
  const t=(p.title+" "+p.category+" "+(p.tags||[]).join(" ")).toLowerCase();
  const words={
    Mobiles:["phone","smartphone","iphone","android","tablet","charger","airpods","mobile"],
    Fashion:["shirt","dress","jeans","shoe","sneaker","bag","watch","sunglass","top"],
    Electronics:["laptop","phone","tablet","airpods","charger","computer","keyboard","mouse","speaker"],
    Home:["bed","sofa","table","chair","lamp","kitchen","cabinet","shelf","decor"],
    Appliances:["kitchen","mixer","cooker","blender","appliance","microwave","iron","fan"],
    Beauty:["beauty","skin","cream","serum","perfume","fragrance","lipstick","mascara","jewellery"],
    Grocery:["grocer","food","oil","rice","sugar","spice","flour","snack","tea","coffee"],
    Travel:["bag","backpack","suitcase","luggage","watch","sunglass"],
    Toys:["toy","doll","ball","puzzle","game","sport","kid","children"],
    Sports:["sport","football","cricket","helmet","shoe","basketball","volleyball","golf"]
  }[cat]||[];
  return words.reduce((n,w)=>n+(t.includes(w)?3:0),0);
}

async function loadCatalog() {

    try {

        const res = await fetch(PRODUCT_API, {
            cache: "no-store"
        });

        if (!res.ok) {
            throw new Error("Product API request failed");
        }

        const data = await res.json();

        if (!data.success) {
            throw new Error(data.message || "Unable to load products");
        }

        const databaseProducts = data.products || [];

        allProducts = databaseProducts.map(normalizeCatalogProduct);
        products = [...allProducts];
        window.products = products;

        console.log(
            "Products loaded from MySQL:",
            allProducts
        );

        renderNav();

        renderCategoryCards();

        filterCat("Top Offers");

    } catch (err) {

        console.error(
            "Database product loading error:",
            err
        );

        const grid = document.getElementById("productGrid");

        if (grid) {

            grid.innerHTML = `
                <div style="
                    padding:40px;
                    text-align:center;
                    width:100%;
                ">
                    <h3>Unable to load products</h3>
                    <p>Please check the database connection.</p>
                </div>
            `;

        }

    }

}

const categories=[
["Mobiles","https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=500&q=80"],
["Electronics","https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=500&q=80"],
["Fashion","https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=500&q=80"],
["Footwear","https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=500&q=80"],
["Home","https://images.unsplash.com/photo-1484101403633-562f891dc89a?auto=format&fit=crop&w=500&q=80"],
["Beauty","https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=500&q=80"]
];
const navCats=["Top Offers","Mobiles","Fashion","Electronics","Home","Appliances","Beauty","Grocery","Travel","Toys","Sports"];
const grid=document.getElementById("productGrid");


function isMeatProduct(p){
  const text=`${p.name||""} ${p.category||""} ${p.cat||""} ${p.description||""}`.toLowerCase();
  return /\b(beef|meat|mutton|chicken|fish|pork|lamb|seafood|steak|bacon|sausage|salami|ham|prawn|shrimp)\b/.test(text);
}
function safeProducts(list){
  return list.filter(p=>!isMeatProduct(p));
}
function findProductByName(name){
  return products.find(p=>p.name===name);
}
function cartSubtotal(){ return cartTotal(); }
function renderProducts(list=products, append=false){
  if(!grid) return;
  if(!append) visibleCount=50;
  const safeList=(Array.isArray(list)?list:[]).filter(p=>!isMeatProduct(p));
  const shown=safeList.slice(0,visibleCount);
  const discounted = shown.map((p)=>{
    const price = Number(p.price) || 0;
    const oldPrice = Number(p.old) || price || 1;
    const off = oldPrice > 0 ? Math.max(0, Math.round((1 - price / oldPrice) * 100)) : 0;
    return `<article class="product" onclick="openProductByName(${JSON.stringify(p.name)})">
      <span class="badge">${off}% OFF</span>
      <button class="heart" onclick="event.stopPropagation();this.textContent=this.textContent==='♡'?'♥':'♡'">♡</button>
      <div class="product-img"><img src="${p.img || ""}" alt="${p.name}" loading="lazy"></div>
      <div class="product-info"><div class="product-cat">${p.brand || "Marketplace"} • ${p.cat || p.category || "Product"}</div><div class="product-name">${p.name}</div>
      <span class="rating">${p.rate || "4.2"} <span>★</span></span>
      <div class="price">${money(price)} <span class="old">${money(oldPrice)}</span><span class="off">${off}% off</span></div>
      <button class="add" type="button" data-action="add-cart">Add to Cart</button>
      </div>
    </article>`;
  }).join("");
  grid.innerHTML=discounted;

  const more=safeList.length>visibleCount;
  let moreBtn=document.getElementById("loadMoreBtn");
  if(more){
    if(!moreBtn){moreBtn=document.createElement("button");moreBtn.id="loadMoreBtn";moreBtn.className="load-more";if(grid.parentElement) grid.parentElement.appendChild(moreBtn)}
    moreBtn.textContent=`Load More (${safeList.length-visibleCount} products left)`;
    moreBtn.onclick=()=>{visibleCount+=50;renderProducts(list)};
  }else if(moreBtn) moreBtn.remove();
}
function renderNav(){const bar=document.getElementById("categoryBar"); if(!bar) return; bar.innerHTML=navCats.map(x=>`<button class="cat-item" onclick="filterCat('${x}')">${x}</button>`).join("")}
function renderCategoryCards(){const cards=document.getElementById("categoryCards"); if(!cards) return; cards.innerHTML=categories.map(c=>`<div class="category-card"><img src="${c[1]}" alt="${c[0]}"><span>${c[0]} →</span></div>`).join("")}

function filterCat(cat){
  currentCategory=cat;
  const found=cat==="Top Offers"?products.slice(0,50):buildPool(cat);
  const titleEl=document.querySelector(".section h2");
  const metaEl=document.querySelector(".section-head p");
  if(titleEl) titleEl.textContent=cat==="Top Offers"?"Best Deals":`${cat} — 50 Products`;
  if(metaEl) metaEl.textContent=cat==="Top Offers"?"Top picks from the marketplace catalog":"Browse 50 individually matched products in this category";
  renderProducts(found);
  scrollToProducts();
}
function showAll(){filterCat("Top Offers")}
function scrollToProducts(){const section=document.getElementById("productsSection"); if(section) section.scrollIntoView({behavior:"smooth"})}


function openProductByName(name){
  const p=products.find(x=>x.name===name); if(!p)return;
  window.currentProduct=p;
  const detailImage=document.getElementById("detailImage");
  const detailCat=document.getElementById("detailCat");
  const detailName=document.getElementById("detailName");
  const detailRating=document.getElementById("detailRating");
  const detailPrice=document.getElementById("detailPrice");
  const detailOffer=document.getElementById("detailOffer");
  const detailDescription=document.getElementById("detailDescription");
  const deliveryMessage=document.getElementById("deliveryMessage");
  const pincodeInput=document.getElementById("pincodeInput");
  const productModal=document.getElementById("productModal");
  const overlay=document.getElementById("overlay");

  if(detailImage){ detailImage.src=p.img; detailImage.alt=p.name; }
  if(detailCat) detailCat.textContent=`${p.cat || p.category || "Product"} • ${p.brand || "Marketplace"}`;
  if(detailName) detailName.textContent=p.name;
  if(detailRating) detailRating.innerHTML=`${p.rate || "4.2"} <span>★</span>`;
  if(detailPrice) detailPrice.textContent=money(p.price);
  if(detailOffer) detailOffer.textContent=`${Math.round((1-p.price/(p.old||p.price||1))*100)}% off • MRP ${money(p.old||p.price||0)} • Inclusive of all taxes`;
  if(detailDescription) detailDescription.textContent=p.description || "";
  if(deliveryMessage) deliveryMessage.textContent="Enter your pincode to check estimated delivery.";
  if(pincodeInput) pincodeInput.value="";
  if(productModal) productModal.classList.add("show");
  if(overlay) overlay.classList.add("show");
}
function closeProduct(){
  const modal=document.getElementById("productModal");
  const overlay=document.getElementById("overlay");
  if(modal) modal.classList.remove("show");
  if(overlay) overlay.classList.remove("show");
}
function checkDelivery(){
  const pin=document.getElementById("pincodeInput").value.trim();
  const box=document.getElementById("deliveryMessage");
  if(!/^\d{6}$/.test(pin)){box.textContent="Please enter a valid 6-digit Indian pincode.";box.style.color="#d93025";return}
  const days=(parseInt(pin.slice(-1),10)%3)+3;
  const d=new Date(); d.setDate(d.getDate()+days);
  const date=d.toLocaleDateString("en-IN",{day:"numeric",month:"short"});
  box.textContent=`✓ Delivery available to ${pin}. Expected by ${date} (${days}–${days+2} business days).`;
  box.style.color="#168c47";
}
function buyCurrentProduct(){
  if(!window.currentProduct)return;
  addToCartByName(window.currentProduct.name, 1);
  closeProduct();
  setTimeout(openCheckout,50);
}
function openCheckout(){
  if(!cart.length){alert("Your cart is empty. Add a product first.");return}
  closeCart();
  renderCheckout();
  const modal = document.getElementById("checkoutModal");
  const overlay = document.getElementById("overlay");
  if(modal) modal.classList.add("show");
  if(overlay) overlay.classList.add("show");
}
function renderCheckout(){
  const checkoutItems = document.getElementById("checkoutItems");
  if (checkoutItems) {
    checkoutItems.innerHTML = cart.map(item => `
      <div class="checkout-item">
        <img src="${item.img || ""}" alt="${item.name}">
        <div>
          <b>${item.name}</b>
          <small>${item.brand || "Marketplace"} • Qty ${item.qty} • ${money((Number(item.price)||0)*Number(item.qty||1))}</small>
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
function closeCheckout(){
  const modal=document.getElementById("checkoutModal");
  const overlay=document.getElementById("overlay");
  if(modal) modal.classList.remove("show");
  if(overlay) overlay.classList.remove("show");
}
function validateCheckout(){
  const fields={
    customerName:"Full name",customerPhone:"mobile number",customerPin:"6-digit pincode",
    customerCity:"city",customerState:"state",customerHouse:"house/flat",customerAddress:"full address"
  };
  for(const [id,label] of Object.entries(fields)){
    const el=document.getElementById(id);
    if(!el.value.trim()){el.focus();alert(`Please enter your ${label}.`);return false}
  }
  if(!/^\d{10}$/.test(document.getElementById("customerPhone").value.trim())){alert("Please enter a valid 10-digit mobile number.");return false}
  if(!/^\d{6}$/.test(document.getElementById("customerPin").value.trim())){alert("Please enter a valid 6-digit pincode.");return false}
  if(!document.querySelector('input[name="payment"]:checked')){alert("Please select a payment method.");return false}
  return true;
}
function placeOrder(){
  if(!validateCheckout())return;
  const subtotal=cartSubtotal();
  const fastDelivery = document.getElementById("fastDelivery");
  const delivery=fastDelivery?.checked ? 99 : (subtotal>=499?0:49);
  const payment=document.querySelector('input[name="payment"]:checked')?.value || "Cash on Delivery";
  const orderId="TSA"+Date.now().toString().slice(-8);
  const days=fastDelivery?.checked?2:5;
  const deliveryDate=new Date();deliveryDate.setDate(deliveryDate.getDate()+days);
  currentOrder={id:orderId,total:subtotal+delivery,payment,date:deliveryDate.toLocaleDateString("en-IN",{day:"numeric",month:"short",year:"numeric"}),items:cart};
  localStorage.setItem("tsaLastOrder",JSON.stringify(currentOrder));
  const successOrderId = document.getElementById("successOrderId");
  const successDelivery = document.getElementById("successDelivery");
  const successTotal = document.getElementById("successTotal");
  const successPayment = document.getElementById("successPayment");
  if (successOrderId) successOrderId.textContent = orderId;
  if (successDelivery) successDelivery.textContent = currentOrder.date;
  if (successTotal) successTotal.textContent = money(currentOrder.total);
  if (successPayment) successPayment.textContent = payment;
  const checkoutModal = document.getElementById("checkoutModal");
  const orderSuccess = document.getElementById("orderSuccess");
  const overlay = document.getElementById("overlay");
  if (checkoutModal) checkoutModal.classList.remove("show");
  if (orderSuccess) orderSuccess.classList.add("show");
  if (overlay) overlay.classList.add("show");
  cart=[];saveCart();renderCart();
}
function closeSuccess(){
  const modal=document.getElementById("orderSuccess");
  const overlay=document.getElementById("overlay");
  if(modal) modal.classList.remove("show");
  if(overlay) overlay.classList.remove("show");
}
function continueShopping(){closeSuccess();window.scrollTo({top:0,behavior:"smooth"})}

function closeLogin(){
  const modal=document.getElementById("loginModal");
  const overlay=document.getElementById("overlay");
  if(modal) modal.classList.remove("show");
  if(overlay) overlay.classList.remove("show");
}

function initShoppingUI() {
  const cartBtn = document.getElementById("cartBtn");
  const detailAdd = document.getElementById("detailAdd");
  const detailBuy = document.getElementById("detailBuy");
  const closeCartBtn = document.getElementById("closeCart");
  const overlay = document.getElementById("overlay");
  const loginBtn = document.getElementById("loginBtn");
  const closeLoginBtn = document.getElementById("closeLogin");
  const searchBtn = document.getElementById("searchBtn");
  const searchInput = document.getElementById("searchInput");
  const checkoutTrigger = document.querySelector(".checkout");

  if (cartBtn) cartBtn.onclick = openCart;
  if (detailAdd) detailAdd.onclick = () => { if (window.currentProduct) { addToCartByName(window.currentProduct.name); closeProduct(); } };
  if (detailBuy) detailBuy.onclick = buyCurrentProduct;
  if (checkoutTrigger) checkoutTrigger.onclick = openCheckout;
  if (closeCartBtn) closeCartBtn.onclick = closeCart;
  if (overlay) overlay.onclick = () => { closeCart(); closeLogin(); closeProduct(); closeCheckout(); closeSuccess(); };
  if (loginBtn) loginBtn.onclick = () => { const loginModal = document.getElementById("loginModal"); if (loginModal) loginModal.classList.add("show"); if (overlay) overlay.classList.add("show"); };
  if (closeLoginBtn) closeLoginBtn.onclick = closeLogin;
  if (searchBtn) searchBtn.onclick = search;
  if (searchInput) searchInput.addEventListener("keydown", e => { if (e.key === "Enter") search(); });
}

function search(){
  const searchInput = document.getElementById("searchInput");
  if (!searchInput) return;

  const q = searchInput.value.trim().toLowerCase();
  const aliases = {
    phone:["mobiles"],mobile:["mobiles"],smartphone:["mobiles"],
    shoes:["sports"],sneakers:["sports"],footwear:["sports"],
    grocery:["grocery"],rice:["grocery"],atta:["grocery"],oil:["grocery"],
    electronics:["electronics"],laptop:["electronics"],headphones:["electronics"],earbuds:["electronics"],tv:["electronics"],
    beauty:["beauty"],makeup:["beauty"],shampoo:["beauty"],clothes:["fashion"],shirt:["fashion"],jeans:["fashion"],
    home:["home"],sports:["sports"],appliances:["appliances"],travel:["travel"],toys:["toys"]
  };

  let found = products;
  if (q) {
    if (aliases[q]) {
      const cats = aliases[q];
      found = products.filter(p => cats.some(c => (p.cat || "").toLowerCase() === c));
      if (!found.length) {
        found = products.filter(p => cats.some(c => ((p.name + " " + (p.apiCategory || "")).toLowerCase().includes(c))));
      }
    } else {
      found = products.filter(p => ((p.name || "") + " " + (p.cat || "") + " " + (p.brand || "") + " " + (p.apiCategory || "")).toLowerCase().includes(q));
    }
  }

  const titleEl = document.querySelector(".section h2");
  const metaEl = document.querySelector(".section-head p");
  if (titleEl) titleEl.textContent = q ? `Search results for "${q}"` : "Best Deals";
  if (metaEl) metaEl.textContent = `${found.length} products found`;

  renderProducts(safeProducts(found));
  scrollToProducts();
}

document.addEventListener("DOMContentLoaded", function () {
  initShoppingUI();
  renderNav();
  renderCategoryCards();
  renderCart();
  loadCatalog();
});

document.addEventListener("click", function(event){
  const addButton = event.target.closest('[data-action="add-cart"]');
  if(addButton){
    event.preventDefault();
    event.stopPropagation();
    const card = addButton.closest(".product");
    if(card){
      const nameEl = card.querySelector(".product-name");
      const name = nameEl ? nameEl.textContent.trim() : "";
      if(name) addToCartByName(name);
    }
    return;
  }

  const plus = event.target.closest("[data-cart-plus]");
  if(plus){ event.preventDefault(); changeCartQty(Number(plus.dataset.cartPlus), 1); return; }

  const minus = event.target.closest("[data-cart-minus]");
  if(minus){ event.preventDefault(); changeCartQty(Number(minus.dataset.cartMinus), -1); return; }

  const remove = event.target.closest("[data-cart-remove]");
  if(remove){ event.preventDefault(); removeCart(Number(remove.dataset.cartRemove)); return; }
});

