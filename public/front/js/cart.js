// // CART BADGE & BUTTONS
// const cartBadge = document.getElementById("cartItems");
// const addToCartButtons = document.querySelectorAll(".addToCart");

// // Safely get product key
// function getProductKey(product) {
//     if (!product) return null;
//     return product.dataset.id || product.dataset.name || product.innerText;
// }

// // Load & save cart with error handling
// function loadCart() {
//     try {
//         const saved = localStorage.getItem("cartItemsObj");
//         return saved ? JSON.parse(saved) : {};
//     } catch {
//         return {};
//     }
// }

// function saveCart(cartObj) {
//     try {
//         localStorage.setItem("cartItemsObj", JSON.stringify(cartObj));
//     } catch {}
// }

// // Update cart badge
// function updateCartBadge() {
//     if (!cartBadge) return;
//     const cart = loadCart();
//     // Count unique products (number of items in cart), not total quantity
//     const uniqueProducts = Object.keys(cart).filter((key) => {
//         const item = cart[key];
//         const qty =
//             item && typeof item === "object"
//                 ? item.quantity || 0
//                 : parseInt(item) || 0;
//         return qty > 0;
//     }).length;
//     cartBadge.textContent = uniqueProducts;
// }

// // Update button state safely
// function updateButtonState(product) {
//     if (!product) return;
//     const key = getProductKey(product);
//     const btn = product.querySelector(".addToCart");
//     if (!btn) return;

//     const cart = loadCart();
//     const item = cart[key];
//     const qty =
//         item && typeof item === "object"
//             ? item.quantity || 0
//             : parseInt(item) || 0;

//     if (qty > 0) {
//         btn.textContent = "Remove from Cart";
//         btn.classList.add("inCart");
//     } else {
//         btn.textContent = "Add to Cart";
//         btn.classList.remove("inCart");
//     }
// }

// // Initialize products safely
// function initializeProducts() {
//     const products = document.querySelectorAll(".product");
//     if (!products) return;

//     const cart = loadCart();
//     products.forEach((product) => {
//         const key = getProductKey(product);
//         const amountEl = product.querySelector(".amount");
//         if (!amountEl) return;

//         const item = cart[key];
//         const qty =
//             item && typeof item === "object"
//                 ? item.quantity || 0
//                 : parseInt(item) || 0;
//         amountEl.textContent = qty;
//         updateButtonState(product);
//     });
//     updateCartBadge();
// }

// // PRODUCT BUTTON HANDLER
// addToCartButtons.forEach((btn) => {
//     btn.addEventListener("click", (e) => {
//         const product = e.target.closest(".product");
//         if (!product) return;

//         // Auth gate: redirect to login if not authenticated
//         if (
//             typeof window.isAuthenticated !== "undefined" &&
//             !window.isAuthenticated
//         ) {
//             if (typeof Flasher !== "undefined") {
//                 Flasher.add({
//                     type: "error",
//                     message: "Please sign in to add items to your cart.",
//                 });
//             }
//             window.location.href = window.loginUrl || "/login";
//             return;
//         }

//         const key = getProductKey(product);
//         const amountEl = product.querySelector(".amount");
//         if (!amountEl) return;

//         const cart = loadCart();
//         let qty = parseInt(amountEl.textContent) || 1;

//         const name =
//             product.dataset.name ||
//             product.querySelector(".productName")?.textContent ||
//             "Unnamed";
//         const price =
//             parseFloat(product.dataset.price) ||
//             parseFloat(product.querySelector(".productPrice")?.textContent) ||
//             0;
//         const img = product.querySelector("img")?.src || "";

//         if (cart[key] && cart[key].quantity > 0) {
//             // Remove from cart
//             delete cart[key];
//             amountEl.textContent = 0;

//             if (typeof Flasher !== "undefined") {
//                 Flasher.add({
//                     type: "info",
//                     message: `${name} removed from cart.`,
//                 });
//             }
//         } else {
//             // Add to cart
//             cart[key] = { name, price, img, quantity: qty };
//             amountEl.textContent = qty;

//             if (typeof Flasher !== "undefined") {
//                 Flasher.add({
//                     type: "success",
//                     message: `${name} added to cart successfully!`,
//                 });
//             }
//         }

//         saveCart(cart);
//         updateCartBadge();
//         renderSideCart();
//         updateButtonState(product);
//         renderCart(); // update cart page if present
//         renderCheckout(); // update checkout page if present
//     });
// });

// // PRODUCT COUNTER HANDLER
// document.addEventListener("click", (e) => {
//     const product = e.target.closest(".product");
//     if (!product) return;

//     const amountEl = product.querySelector(".amount");
//     if (!amountEl) return;

//     let value = parseInt(amountEl.textContent) || 1;

//     if (e.target.classList.contains("increase")) {
//         value++;
//     }

//     if (e.target.classList.contains("decrease")) {
//         if (value > 1) value--;
//     }

//     amountEl.textContent = value;
// });

// // INITIALIZE PRODUCTS
// initializeProducts();

// // CART CRUD (ONLY IF ELEMENTS EXIST)
// const cartContainer = document.querySelector(".cartCrudItems");
// const totalSpan = document.getElementById("cartCrudTotalNumber");
// const cartSection = document.getElementById("cartCrud");
// const emptyCartSection = document.getElementById("emptyCartState");

// // Safely toggle cart sections
// function toggleCartSections() {
//     const cartSection = document.getElementById("cartCrud");
//     const emptyCartSection = document.getElementById("emptyCartState");
//     if (!cartSection || !emptyCartSection) return; // exit if not present

//     const cart = loadCart();
//     const keys = Object.keys(cart).filter((k) => {
//         const item = cart[k];
//         const qty =
//             item && typeof item === "object"
//                 ? item.quantity || 0
//                 : parseInt(item) || 0;
//         return qty > 0;
//     });

//     if (keys.length === 0) {
//         cartSection.style.display = "none";
//         emptyCartSection.style.display = "flex";
//     } else {
//         cartSection.style.display = "flex";
//         emptyCartSection.style.display = "none";
//     }
// }

// function toggleSideCartSections() {
//     const fullSideCart = document.getElementById("fullSideCart");
//     const emptySideCart = document.getElementById("emptySideCart");
//     if (!fullSideCart || !emptySideCart) return;

//     const cart = loadCart();
//     const hasItems = Object.values(cart).some(
//         (item) => item && item.quantity > 0
//     );

//     if (hasItems) {
//         fullSideCart.style.display = "flex";
//         emptySideCart.style.display = "none";
//     } else {
//         fullSideCart.style.display = "none";
//         emptySideCart.style.display = "flex"; // or "flex", depending on your CSS
//     }
// }

// function renderCart() {
//     const cartContainer = document.querySelector(".cartCrudItems");
//     const totalSpan = document.getElementById("cartCrudTotalNumber");
//     if (!cartContainer || !totalSpan) return; // exit if cart page not present

//     const cart = loadCart();

//     // Remove items with 0 quantity
//     Object.keys(cart).forEach((key) => {
//         if (!cart[key] || cart[key].quantity === 0) delete cart[key];
//     });
//     saveCart(cart);

//     const keys = Object.keys(cart);
//     if (keys.length === 0) {
//         toggleCartSections();
//         updateCartBadge();
//         return;
//     } else {
//         toggleCartSections();
//     }

//     cartContainer.innerHTML = "";
//     let total = 0;

//     keys.forEach((key) => {
//         const item = cart[key];
//         if (!item) return;

//         const qty = item.quantity || 0;
//         const price = item.price || 0;
//         const subtotal = price * qty;
//         total += subtotal;

//         const article = document.createElement("article");
//         article.classList.add("cartItem");
//         article.dataset.key = key;

//         article.innerHTML = `
//             <span class="itemProfile">
//                 <img class="itemImage" src="${item.img || ""}" alt="${
//             item.name || "item"
//         }">
//                 <div class="itemDesc">
//                     <h4 class="productName">${item.name || "Unnamed"}</h4>
//                     <button type="button" class="removeBtn contact">Remove Item</button>
//                 </div>
//             </span>
//             <span class="itemPrice"><p class="itemPriceNumber">${price.toFixed(
//                 2
//             )}</p>$</span>
//             <span class="itemQuantity">
//                 <div class="counters">
//                     <button type="button" class="decrease">-</button>
//                     <span class="amount">${qty}</span>
//                     <button type="button" class="increase">+</button>
//                 </div>
//             </span>
//             <span class="itemSubtotal"><p class="itemSubtotalNumber">${subtotal.toFixed(
//                 2
//             )}</p>$</span>
//         `;

//         cartContainer.appendChild(article);
//     });

//     totalSpan.textContent = total.toFixed(2);
//     updateCartBadge();
// }

// // Cart CRUD actions
// document.addEventListener("click", (e) => {
//     const article = e.target.closest(".cartItem");
//     if (!article) return;

//     const key = article.dataset.key;
//     const cart = loadCart();

//     let item = cart[key];
//     if (item && typeof item !== "object") {
//         const name =
//             article.querySelector(".productName")?.textContent || "Unnamed";
//         const price =
//             parseFloat(
//                 article.querySelector(".itemPriceNumber")?.textContent
//             ) || 0;
//         const img = article.querySelector(".itemImage")?.src || "";
//         const qty = parseInt(item) || 0;
//         item = { name, price, img, quantity: qty };
//     }

//     if (!item) {
//         const name =
//             article.querySelector(".productName")?.textContent || "Unnamed";
//         const price =
//             parseFloat(
//                 article.querySelector(".itemPriceNumber")?.textContent
//             ) || 0;
//         const img = article.querySelector(".itemImage")?.src || "";
//         item = { name, price, img, quantity: 0 };
//     }

//     if (e.target.classList.contains("increase")) {
//         item.quantity += 1;
//     }

//     if (e.target.classList.contains("decrease")) {
//         if (item.quantity === 0) return;
//         item.quantity -= 1;
//     }

//     if (e.target.classList.contains("removeBtn")) {
//         delete cart[key];
//     } else {
//         if (item.quantity > 0) {
//             cart[key] = item;
//         } else {
//             delete cart[key];
//         }
//     }

//     saveCart(cart);
//     renderCart();
//     renderSideCart();
//     updateCartBadge();
//     renderCheckout();
// });

// renderCart();

// // Checkout Elements
// const checkoutContainer = document.getElementById("checkOutCartItemsHolder");
// const checkoutSubtotalEl = document.getElementById("checkoutSubtotal");
// const checkoutShippingEl = document.getElementById("checkoutShippingFee");
// const checkoutTaxEl = document.getElementById("checkoutTax");
// const checkoutTotalEl = document.getElementById("checkoutTotalPrice");
// const checkoutTotalMobileEl = document.getElementById("checkoutTotalShower");

// // Render checkout items dynamically from cart
// function renderCheckout() {
//     if (!checkoutContainer) return; // exit if checkout page not present

//     const cart = loadCart();
//     checkoutContainer.innerHTML = "";

//     let subtotal = 0;

//     Object.keys(cart).forEach((key) => {
//         const item = cart[key];
//         if (!item || !item.quantity) return;

//         const qty = item.quantity || 0;
//         const price = item.price || 0;
//         const subtotalItem = price * qty;
//         subtotal += subtotalItem;

//         const article = document.createElement("article");
//         article.classList.add("checkoutCartItem");
//         article.dataset.key = key;

//         article.innerHTML = `
//             <img class="checkoutCartItemImage" src="${item.img || ""}" alt="${
//             item.name || "item"
//         }">
//             <span class="productName">${item.name || "Unnamed"}</span>
//             <div class="checkoutCartItemAdjust">
//                 <p class="productPrice"><span class="productPriceNumber">${price.toFixed(
//                     2
//                 )}</span>$</p>
//                 <span class="counters">
//                     <button type="button" class="decrease">-</button>
//                     <span class="amount">${qty}</span>
//                     <button type="button" class="increase">+</button>
//                 </span>
//                 <button class="removeItem" type="button"><i class="fa-regular fa-trash-can"></i></button>
//             </div>
//         `;

//         checkoutContainer.appendChild(article);
//     });

//     // Calculate fees safely
//     const shippingFee = subtotal ? 10 : 0;
//     const tax = subtotal * 0.08;
//     const total = subtotal + shippingFee + tax;

//     if (checkoutSubtotalEl)
//         checkoutSubtotalEl.textContent = subtotal.toFixed(2);
//     if (checkoutShippingEl)
//         checkoutShippingEl.textContent = shippingFee.toFixed(2);
//     if (checkoutTaxEl) checkoutTaxEl.textContent = tax.toFixed(2);
//     if (checkoutTotalEl) checkoutTotalEl.textContent = total.toFixed(2);
//     if (checkoutTotalMobileEl)
//         checkoutTotalMobileEl.textContent = total.toFixed(2);
// }

// // Handle item interactions
// document.addEventListener("click", (e) => {
//     const article = e.target.closest(".checkoutCartItem");
//     if (!article) return;

//     const key = article.dataset.key;
//     const cart = loadCart();

//     if (!cart[key]) return;

//     // INCREASE
//     if (e.target.classList.contains("increase")) {
//         cart[key].quantity += 1;
//     }

//     // DECREASE
//     if (e.target.classList.contains("decrease")) {
//         if (cart[key].quantity > 1) {
//             cart[key].quantity -= 1;
//         } else {
//             delete cart[key];
//         }
//     }

//     // ✅ REMOVE BUTTON (trash icon)
//     if (e.target.closest(".removeItem")) {
//         delete cart[key];
//     }

//     saveCart(cart);
//     renderSideCart();
//     renderCheckout();
//     renderCart(); // keep cart page in sync
//     updateCartBadge(); // update badge
// });

// const sideCartItemsHolder = document.getElementById("sideCartItemsHolder");
// function renderSideCart() {
//     if (!sideCartItemsHolder) return;

//     const cart = loadCart();
//     sideCartItemsHolder.innerHTML = "";

//     let subtotal = 0;

//     Object.keys(cart).forEach((key) => {
//         const item = cart[key];
//         if (!item || item.quantity <= 0) return;

//         const price = item.price || 0;
//         const qty = item.quantity || 0;
//         subtotal += price * qty;

//         const article = document.createElement("article");
//         article.className = "checkoutCartItem";
//         article.dataset.key = key;

//         article.innerHTML = `
//       <img class="checkoutCartItemImage" src="${item.img}" alt="${item.name}">
//       <span class="productName">${item.name}</span>

//       <div class="checkoutCartItemAdjust">
//         <p class="productPrice">
//           <span class="productPriceNumber">${price.toFixed(2)}</span>${currencySymbol}
//         </p>

//         <span class="counters">
//           <button type="button" class="decrease">-</button>
//           <span class="amount">${qty}</span>
//           <button type="button" class="increase">+</button>
//         </span>

//         <button class="removeItem" type="button">
//           <i class="fa-regular fa-trash-can"></i>
//         </button>
//       </div>
//     `;

//         sideCartItemsHolder.appendChild(article);
//     });

//     // Sync totals with checkout
//     updateSideCartTotals();
//     toggleSideCartSections();
// }

// function updateSideCartTotals() {
//     const subtotalEl = document.getElementById("sideCartSubtotal");
//     const shippingEl = document.getElementById("sideCartShipping");
//     const taxEl = document.getElementById("sideCartTax");
//     const totalEl = document.getElementById("sideCartTotal");

//     if (!subtotalEl) return;

//     const cart = loadCart();
//     let subtotal = 0;

//     Object.values(cart).forEach((item) => {
//         if (!item || !item.quantity) return;
//         subtotal += item.price * item.quantity;
//     });

//     const shipping = subtotal ? 10 : 0;
//     const tax = subtotal * 0.08;
//     const total = subtotal + shipping + tax;

//     subtotalEl.textContent = subtotal.toFixed(2);
//     shippingEl.textContent = shipping.toFixed(2);
//     taxEl.textContent = tax.toFixed(2);
//     totalEl.textContent = total.toFixed(2);
// }
// document.addEventListener("click", (e) => {
//     const itemEl = e.target.closest("#sideCart .checkoutCartItem");
//     if (!itemEl) return;

//     const key = itemEl.dataset.key;
//     const cart = loadCart();
//     if (!cart[key]) return;

//     if (e.target.classList.contains("increase")) {
//         cart[key].quantity += 1;
//     }

//     if (e.target.classList.contains("decrease")) {
//         cart[key].quantity -= 1;
//         if (cart[key].quantity <= 0) delete cart[key];
//     }

//     if (e.target.closest(".removeItem")) {
//         delete cart[key];
//     }

//     saveCart(cart);

//     renderSideCart();
//     renderCart();
//     renderCheckout();
//     updateCartBadge();
// });

// // Initial render
// renderSideCart();
// renderCheckout();
