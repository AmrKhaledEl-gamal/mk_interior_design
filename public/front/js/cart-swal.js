/**
 * Cart SweetAlert Integration
 * Comprehensive real-time cart functionality with SweetAlert2
 * Handles add, update, remove operations with instant UI updates
 */

(function() {
    'use strict';

    // Global cart state
    window.cartState = {
        items: {},
        count: 0,
        total: 0,
        subtotal: 0,
        isLoading: false
    };

    // Configuration
    const CONFIG = {
        animations: {
            duration: 300,
            easing: 'ease-out'
        },
        notifications: {
            timer: 3000,
            showConfirmButton: false
        },
        debounce: {
            quantity: 500,
            search: 300
        }
    };

    // Utility functions
    const Utils = {
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        formatPrice(price) {
            return parseFloat(price || 0).toFixed(2);
        },

        formatCurrency(price) {
            const symbol = window.currencySymbol || '$';
            return `${symbol}${this.formatPrice(price)}`;
        },

        showLoading(element) {
            if (element) {
                element.style.opacity = '0.6';
                element.style.pointerEvents = 'none';
            }
        },

        hideLoading(element) {
            if (element) {
                element.style.opacity = '1';
                element.style.pointerEvents = 'auto';
            }
        },

        animateElement(element, animation = 'pulse') {
            if (!element) return;

            element.style.transition = `transform ${CONFIG.animations.duration}ms ${CONFIG.animations.easing}`;

            switch (animation) {
                case 'pulse':
                    element.style.transform = 'scale(1.05)';
                    setTimeout(() => {
                        element.style.transform = 'scale(1)';
                    }, CONFIG.animations.duration);
                    break;
                case 'shake':
                    element.style.transform = 'translateX(-5px)';
                    setTimeout(() => {
                        element.style.transform = 'translateX(5px)';
                        setTimeout(() => {
                            element.style.transform = 'translateX(0)';
                        }, 100);
                    }, 100);
                    break;
            }
        },

        getCSRFToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        }
    };

    // API Service
    const CartAPI = {
        async makeRequest(url, data = {}, method = 'POST') {
            try {
                const options = {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': Utils.getCSRFToken(),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                };

                if (method !== 'GET') {
                    options.body = JSON.stringify(data);
                }

                const response = await fetch(url, options);

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                return await response.json();
            } catch (error) {
                console.error('Cart API Error:', error);
                throw error;
            }
        },

        async add(productId, quantity = 1) {
            return await this.makeRequest(window.cartAddUrl, {
                id: productId,
                quantity: quantity
            });
        },

        async update(itemId, quantity) {
            return await this.makeRequest(window.cartUpdateUrl, {
                id: itemId,
                quantity: quantity
            });
        },

        async remove(itemId) {
            return await this.makeRequest(window.cartRemoveUrl, {
                id: itemId
            });
        }
    };

    // UI Updater
    const UIUpdater = {
        updateCartBadge(count) {
            const badges = document.querySelectorAll('.cart-badge, .cartCount, [data-cart-count], #cartItems');
            badges.forEach(badge => {
                // Use count directly (number of unique items, not total quantity)
                badge.textContent = count || '0';
                Utils.animateElement(badge, 'pulse');
            });
        },

        updateSideCart(cartData) {
            console.log('Updating side cart with:', cartData);

            const sideCart = document.getElementById('sideCart');
            const cartItemsContainer = document.getElementById('sideCartItemsHolder');
            const cartTotalElement = document.getElementById('sideCartTotal');
            const cartSubtotalElement = document.getElementById('sideCartSubtotal');
            const fullSideCart = document.getElementById('fullSideCart');
            const emptySideCart = document.getElementById('emptySideCart');

            console.log('Side cart elements found:', {
                sideCart: !!sideCart,
                cartItemsContainer: !!cartItemsContainer,
                cartTotalElement: !!cartTotalElement,
                cartSubtotalElement: !!cartSubtotalElement,
                fullSideCart: !!fullSideCart,
                emptySideCart: !!emptySideCart,
                checkoutCalc: !!document.getElementById('checkoutCalc'),
                checkoutButton: !!document.getElementById('checkoutButton')
            });

            if (!cartItemsContainer) {
                console.error('Side cart container not found');
                return;
            }

            // Clear existing items
            cartItemsContainer.innerHTML = '';

            if (cartData.cartItems && Object.keys(cartData.cartItems).length > 0) {
                console.log(`Rendering ${Object.keys(cartData.cartItems).length} cart items`);

                // Render cart items
                Object.values(cartData.cartItems).forEach((item, index) => {
                    console.log(`Creating element for item ${index + 1}:`, item);
                    const itemElement = this.createSideCartItemElement(item);
                    cartItemsContainer.appendChild(itemElement);
                    console.log(`Added item element:`, itemElement);
                });

                console.log('Final cart container HTML:', cartItemsContainer.innerHTML);

                // Show full cart, hide empty cart
                if (fullSideCart) {
                    fullSideCart.style.display = '';
                    console.log('Full side cart set to display: block');
                }
                if (emptySideCart) {
                    emptySideCart.style.display = 'none';
                    console.log('Empty side cart set to display: none');
                }
            } else {
                // Show empty cart, hide full cart
                if (fullSideCart) {
                    fullSideCart.style.display = 'none';
                    console.log('Full side cart set to display: none');
                }
                if (emptySideCart) {
                    emptySideCart.style.display = '';
                    console.log('Empty side cart set to display: block');
                }
            }

            // Update totals
            if (cartTotalElement) {
                cartTotalElement.textContent = Utils.formatPrice(cartData.cartTotal);
                Utils.animateElement(cartTotalElement, 'pulse');
                console.log('Updated cart total to:', cartData.cartTotal);
            } else {
                console.warn('Cart total element not found');
            }

            if (cartSubtotalElement) {
                cartSubtotalElement.textContent = Utils.formatPrice(cartData.cartSubTotal);
                console.log('Updated cart subtotal to:', cartData.cartSubTotal);
            } else {
                console.warn('Cart subtotal element not found');
            }

            // Update shipping and tax if they exist
            const shippingElement = document.getElementById('sideCartShipping');
            const taxElement = document.getElementById('sideCartTax');
            const checkoutCalc = document.getElementById('checkoutCalc');
            const checkoutButton = document.getElementById('checkoutButton');

            // Show/hide checkout calculation section and button based on cart content
            const hasItems = cartData.cartItems && Object.keys(cartData.cartItems).length > 0;

            if (checkoutCalc) {
                if (hasItems) {
                    checkoutCalc.style.display = 'block';
                    console.log('Checkout calc section made visible');
                } else {
                    checkoutCalc.style.display = 'none';
                    console.log('Checkout calc section hidden');
                }
            }

            if (checkoutButton) {
                if (hasItems) {
                    checkoutButton.style.display = 'block';
                    console.log('Checkout button made visible');
                } else {
                    checkoutButton.style.display = 'none';
                    console.log('Checkout button hidden');
                }
            }

            // Ensure checkout button is visible (fallback for different selectors)
            const checkoutButtonFallback = fullSideCart?.querySelector('.contact[href*="checkout"]');
            if (checkoutButtonFallback && hasItems) {
                checkoutButtonFallback.style.display = 'block';
                console.log('Checkout button fallback made visible');
            }

            if (shippingElement) {
                const shippingFee = cartData.cartSubTotal > 0 ? 10.0 : 0;
                shippingElement.textContent = Utils.formatPrice(shippingFee);
                console.log('Updated shipping fee to:', shippingFee);
            } else if (hasItems) {
                console.warn('Shipping element not found but cart has items');
            }

            if (taxElement) {
                const tax = cartData.cartSubTotal * 0.08;
                taxElement.textContent = Utils.formatPrice(tax);
                console.log('Updated tax to:', tax);
            } else if (hasItems) {
                console.warn('Tax element not found but cart has items');
            }

            // Update badge - show number of unique items, not total quantity
            const uniqueItemsCount = cartData.cartItems ? Object.keys(cartData.cartItems).length : 0;
            this.updateCartBadge(uniqueItemsCount);

            // Update global state
            window.cartState = {
                items: cartData.cartItems || {},
                count: uniqueItemsCount, // Number of unique items
                total: cartData.cartTotal || 0,
                subtotal: cartData.cartSubTotal || 0,
                isLoading: false
            };

            console.log('Cart state updated:', window.cartState);

            // Emit custom event for other components
            window.dispatchEvent(new CustomEvent('cartUpdated', {
                detail: {
                    cartCount: uniqueItemsCount, // Pass unique items count
                    cartItems: cartData.cartItems,
                    cartTotal: cartData.cartTotal,
                    cartSubTotal: cartData.cartSubTotal
                }
            }));
        },

        createSideCartItemElement(item) {
            const itemDiv = document.createElement('article');
            itemDiv.className = 'checkoutCartItem';
            itemDiv.setAttribute('data-id', item.id);
            itemDiv.setAttribute('data-key', item.id); // Add both for compatibility

            itemDiv.innerHTML = `
                <img class="checkoutCartItemImage"
                     src="${item.attributes?.image || item.image || ''}"
                     alt="item image"
                     onerror="this.src='${window.location.origin}/front/images/placeholder.png'">
                <span class="productName">${item.name}</span>
                <div class="checkoutCartItemAdjust">
                    <p class="productPrice">
                        <span class="productPriceNumber">${Utils.formatPrice(item.price)}</span>${window.currencySymbol || '$'}
                    </p>
                    <span class="counters">
                        <button type="button" class="decrease" data-id="${item.id}">-</button>
                        <span class="amount">${item.quantity}</span>
                        <button type="button" class="increase" data-id="${item.id}">+</button>
                    </span>
                    <button class="removeItem" type="button" data-id="${item.id}">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
            `;

            // Add debug info as data attribute
            itemDiv.setAttribute('data-debug-info', JSON.stringify({
                id: item.id,
                name: item.name,
                quantity: item.quantity,
                price: item.price
            }));

            return itemDiv;
        },

        createCartItemElement(item) {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'cart-item';
            itemDiv.setAttribute('data-id', item.id);

            itemDiv.innerHTML = `
                <div class="cart-item-image">
                    <img src="${item.attributes?.image || item.image || ''}"
                         alt="${item.name}"
                         onerror="this.src='${window.location.origin}/front/images/placeholder.png'">
                </div>
                <div class="cart-item-details">
                    <h4 class="cart-item-name">${item.name}</h4>
                    <div class="cart-item-price">${Utils.formatCurrency(item.price)}</div>
                    <div class="cart-item-controls">
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn qty-decrease" data-id="${item.id}">-</button>
                            <input type="number" class="qty-input" value="${item.quantity}" min="1" data-id="${item.id}">
                            <button type="button" class="qty-btn qty-increase" data-id="${item.id}">+</button>
                        </div>
                        <button type="button" class="remove-item" data-id="${item.id}" title="Remove item">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                </div>
                <div class="cart-item-total">
                    ${Utils.formatCurrency(item.price * item.quantity)}
                </div>
            `;

            // Add event listeners
            this.attachCartItemListeners(itemDiv);

            return itemDiv;
        },

        attachCartItemListeners(itemElement) {
            const itemId = itemElement.getAttribute('data-id');

            // Quantity controls
            const decreaseBtn = itemElement.querySelector('.qty-decrease');
            const increaseBtn = itemElement.querySelector('.qty-increase');
            const qtyInput = itemElement.querySelector('.qty-input');
            const removeBtn = itemElement.querySelector('.remove-item');

            if (decreaseBtn) {
                decreaseBtn.addEventListener('click', () => {
                    const currentQty = parseInt(qtyInput.value);
                    if (currentQty > 1) {
                        CartManager.updateQuantity(itemId, currentQty - 1);
                    } else {
                        CartManager.removeItem(itemId);
                    }
                });
            }

            if (increaseBtn) {
                increaseBtn.addEventListener('click', () => {
                    const currentQty = parseInt(qtyInput.value);
                    CartManager.updateQuantity(itemId, currentQty + 1);
                });
            }

            if (qtyInput) {
                const debouncedUpdate = Utils.debounce((qty) => {
                    if (qty > 0) {
                        CartManager.updateQuantity(itemId, qty);
                    }
                }, CONFIG.debounce.quantity);

                qtyInput.addEventListener('change', (e) => {
                    const qty = parseInt(e.target.value) || 1;
                    if (qty < 1) {
                        e.target.value = 1;
                        return;
                    }
                    debouncedUpdate(qty);
                });

                qtyInput.addEventListener('keypress', (e) => {
                    if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                        e.preventDefault();
                    }
                });
            }

            if (removeBtn) {
                removeBtn.addEventListener('click', () => {
                    CartManager.removeItem(itemId);
                });
            }
        },

        showNotification(type, title, message, options = {}) {
            const defaultOptions = {
                icon: type,
                title: title,
                text: message,
                timer: CONFIG.notifications.timer,
                showConfirmButton: CONFIG.notifications.showConfirmButton,
                toast: true,
                position: 'top-end',
                timerProgressBar: true,
                ...options
            };

            return Swal.fire(defaultOptions);
        }
    };

    // Cart Manager
    const CartManager = {
        async addToCart(productId, quantity = 1, options = {}) {
            console.log(`Adding product ${productId} to cart (qty: ${quantity})`);

            // Prevent duplicate calls
            if (window.cartState.isLoading) {
                console.log('Cart operation already in progress, skipping...');
                return;
            }

            // Check authentication
            if (!window.isAuthenticated) {
                await Swal.fire({
                    icon: 'warning',
                    title: 'Sign In Required',
                    text: 'Please sign in to add items to your cart.',
                    showCancelButton: true,
                    confirmButtonText: 'Sign In',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = window.loginUrl;
                    }
                });
                return;
            }

            // Set loading state
            window.cartState.isLoading = true;

            const button = options.button;
            if (button) {
                Utils.showLoading(button);
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';
            }

            try {
                const response = await CartAPI.add(productId, quantity);

                if (response.success) {
                    // Update UI
                    UIUpdater.updateSideCart(response);

                    // Show success notification
                    await UIUpdater.showNotification('success', 'Added to Cart!', response.message);

                    // Emit cartItemUpdated event for individual product pages
                    window.dispatchEvent(new CustomEvent('cartItemUpdated', {
                        detail: { itemId: productId, quantity: quantity, cartData: response }
                    }));

                    // Open side cart if it was the first item
                    if (response.cartItems && Object.keys(response.cartItems).length === 1) {
                        console.log('Opening side cart for first item');
                        const sideCartOverlay = document.getElementById('sideCartOverlay');
                        if (sideCartOverlay) {
                            sideCartOverlay.classList.add('show_sideCart');
                        }

                        // Force update of checkout elements visibility
                        setTimeout(() => {
                            const checkoutCalc = document.getElementById('checkoutCalc');
                            const checkoutButton = document.getElementById('checkoutButton');
                            if (checkoutCalc) {
                                checkoutCalc.style.display = 'block';
                                console.log('Force-enabled checkout calc for first item');
                            }
                            if (checkoutButton) {
                                checkoutButton.style.display = 'block';
                                console.log('Force-enabled checkout button for first item');
                            }
                        }, 100);
                    }

                    // Animate button
                    if (button) {
                        Utils.animateElement(button, 'pulse');
                    }
                } else {
                    throw new Error(response.message || 'Failed to add item to cart');
                }
            } catch (error) {
                console.error('Add to cart error:', error);
                UIUpdater.showNotification('error', 'Error', error.message || 'Failed to add item to cart');
            } finally {
                window.cartState.isLoading = false;

                if (button) {
                    Utils.hideLoading(button);
                    button.innerHTML = options.originalText || button.innerHTML;
                }
            }
        },

        async updateQuantity(itemId, quantity) {
            console.log(`Updating item ${itemId} quantity to ${quantity}`);

            if (!window.isAuthenticated) {
                UIUpdater.showNotification('warning', 'Sign In Required', 'Please sign in to update your cart.');
                return;
            }

            window.cartState.isLoading = true;

            try {
                const response = await CartAPI.update(itemId, quantity);

                if (response.success) {
                    UIUpdater.updateSideCart(response);
                    UIUpdater.showNotification('success', 'Cart Updated', 'Item quantity updated successfully');

                    // Emit cartItemUpdated event for individual product pages
                    window.dispatchEvent(new CustomEvent('cartItemUpdated', {
                        detail: { itemId: itemId, quantity: quantity, cartData: response }
                    }));
                } else {
                    throw new Error(response.message || 'Failed to update cart');
                }
            } catch (error) {
                console.error('Update cart error:', error);
                UIUpdater.showNotification('error', 'Error', error.message || 'Failed to update cart');
            } finally {
                window.cartState.isLoading = false;
            }
        },

        async removeItem(itemId) {
            console.log(`Removing item ${itemId} from cart`);

            if (!window.isAuthenticated) {
                UIUpdater.showNotification('warning', 'Sign In Required', 'Please sign in to manage your cart.');
                return;
            }
            // remove class show_sideCart from #sideCartOverlay
            document.getElementById('sideCartOverlay').classList.remove('show_sideCart');

            // Show confirmation
            const result = await Swal.fire({
                icon: 'question',
                title: 'Remove Item?',
                text: 'Are you sure you want to remove this item from your cart?',
                showCancelButton: true,
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#e30613',
                cancelButtonColor: '#6c757d'
            });

            if (!result.isConfirmed) return;

            window.cartState.isLoading = true;

            try {
                const response = await CartAPI.remove(itemId);

                if (response.success) {
                    UIUpdater.updateSideCart(response);
                    UIUpdater.showNotification('success', 'Item Removed', 'Item removed from cart successfully');

                    // Emit cartItemUpdated event for individual product pages
                    window.dispatchEvent(new CustomEvent('cartItemUpdated', {
                        detail: { itemId: itemId, quantity: 0, cartData: response }
                    }));
                } else {
                    throw new Error(response.message || 'Failed to remove item');
                }
            } catch (error) {
                console.error('Remove item error:', error);
                UIUpdater.showNotification('error', 'Error', error.message || 'Failed to remove item');
            } finally {
                window.cartState.isLoading = false;
            }
        }
    };

    // Event Handlers
    const EventHandlers = {
        init() {
            this.attachGlobalListeners();
            this.attachFormListeners();
            this.attachButtonListeners();
        },

        attachGlobalListeners() {
            // Remove existing global listener if it exists
            if (window.cartGlobalListenerAttached) {
                return;
            }
            window.cartGlobalListenerAttached = true;

            // Handle dynamic add to cart buttons
            document.addEventListener('click', (e) => {
                if (e.target.matches('.addToCart, .add-to-cart') || e.target.closest('.addToCart, .add-to-cart')) {
                    e.preventDefault();
                    e.stopPropagation();

                    const button = e.target.matches('.addToCart, .add-to-cart') ? e.target : e.target.closest('.addToCart, .add-to-cart');
                    const productId = button.getAttribute('data-id') ||
                                    button.closest('[data-id]')?.getAttribute('data-id');

                    if (productId) {
                        const quantity = parseInt(button.getAttribute('data-quantity')) || 1;
                        CartManager.addToCart(productId, quantity, { button });
                    }
                }
            });

            // Handle quantity controls in checkout/cart pages
            document.addEventListener('click', (e) => {
                if (e.target.matches('.increase, .decrease')) {
                    e.preventDefault();
                    e.stopPropagation();

                    console.log('Quantity button clicked:', e.target);

                    const button = e.target;

                    // Try multiple ways to find the item ID and quantity element
                    let itemId = button.getAttribute('data-id');
                    let itemElement = null;
                    let quantityElement = null;

                    // Method 1: Get ID directly from button
                    if (itemId) {
                        console.log(`Found item ID from button: ${itemId}`);
                        // Find the parent item container
                        itemElement = button.closest('[data-key], [data-id], .checkoutCartItem');
                        if (itemElement) {
                            quantityElement = itemElement.querySelector('.amount, .qty-input');
                        }
                    }

                    // Method 2: Get ID from parent element if not found
                    if (!itemId) {
                        itemElement = button.closest('[data-key], [data-id], .checkoutCartItem');
                        if (itemElement) {
                            itemId = itemElement.getAttribute('data-key') || itemElement.getAttribute('data-id');
                            quantityElement = itemElement.querySelector('.amount, .qty-input');
                        }
                    }

                    // Method 3: Look for sibling quantity element
                    if (!quantityElement) {
                        const countersDiv = button.closest('.counters');
                        if (countersDiv) {
                            quantityElement = countersDiv.querySelector('.amount, .qty-input');
                            // Try to find item ID from the counters parent
                            if (!itemId) {
                                const parentItem = countersDiv.closest('[data-key], [data-id], .checkoutCartItem');
                                if (parentItem) {
                                    itemId = parentItem.getAttribute('data-key') || parentItem.getAttribute('data-id');
                                }
                            }
                        }
                    }

                    console.log('Final search results:', {
                        itemId,
                        itemElement,
                        quantityElement,
                        quantityValue: quantityElement ? (quantityElement.textContent || quantityElement.value) : 'not found'
                    });

                    if (itemId && quantityElement) {
                        const currentQty = parseInt(quantityElement.textContent || quantityElement.value) || 0;
                        let newQty = currentQty;

                        if (button.classList.contains('increase')) {
                            newQty = currentQty + 1;
                        } else if (button.classList.contains('decrease')) {
                            newQty = Math.max(0, currentQty - 1);
                        }

                        console.log(`Quantity change: ${currentQty} → ${newQty} for item ${itemId}`);

                        if (newQty === 0) {
                            CartManager.removeItem(itemId);
                        } else {
                            CartManager.updateQuantity(itemId, newQty);
                        }
                    } else {
                        console.error('Could not find item ID or quantity element for button:', {
                            button: button,
                            itemId: itemId,
                            quantityElement: quantityElement,
                            buttonParents: {
                                closest_data_id: button.closest('[data-id]'),
                                closest_data_key: button.closest('[data-key]'),
                                closest_cart_item: button.closest('.checkoutCartItem'),
                                counters_parent: button.closest('.counters')
                            }
                        });
                    }
                }

                // Handle remove buttons
                if (e.target.matches('.removeItem, .remove-item') || e.target.closest('.removeItem, .remove-item')) {
                    e.preventDefault();
                    e.stopPropagation();

                    const button = e.target.matches('.removeItem, .remove-item') ? e.target : e.target.closest('.removeItem, .remove-item');
                    const itemElement = button.closest('[data-key], [data-id], .checkoutCartItem');
                    const itemId = itemElement?.getAttribute('data-key') ||
                                  itemElement?.getAttribute('data-id') ||
                                  button.getAttribute('data-id');

                    console.log('Remove button clicked for item:', itemId);

                    if (itemId) {
                        CartManager.removeItem(itemId);
                    } else {
                        console.warn('Could not find item ID for remove button:', button);
                    }
                }
            });
        },

        attachFormListeners() {
            // Handle quantity inputs on product pages
            document.addEventListener('change', (e) => {
                if (e.target.matches('.quantity-input, input[name="quantity"]')) {
                    const quantity = parseInt(e.target.value) || 1;
                    const addButton = e.target.closest('form, .product')?.querySelector('.addToCart, .add-to-cart');

                    if (addButton) {
                        addButton.setAttribute('data-quantity', quantity);
                    }
                }
            });
        },

        attachButtonListeners() {
            // Remove existing listeners to prevent duplicate events
            document.querySelectorAll('.addToCart, .add-to-cart').forEach(button => {
                // Clone the button to remove all event listeners
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);
            });

            // Attach to existing buttons on page load
            document.querySelectorAll('.addToCart, .add-to-cart').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();

                    const productId = button.getAttribute('data-id') ||
                                    button.closest('[data-id]')?.getAttribute('data-id');
                    const quantity = parseInt(button.getAttribute('data-quantity')) || 1;

                    if (productId) {
                        CartManager.addToCart(productId, quantity, { button });
                    }
                });
            });
        }
    };

    // Global functions for backwards compatibility
    window.addToCart = function(event, productId, quantity = 1) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        // Prevent double execution
        if (window.cartOperationInProgress) {
            return;
        }
        window.cartOperationInProgress = true;

        const button = event?.target;
        CartManager.addToCart(productId, quantity, {
            button,
            originalText: button?.innerHTML
        }).finally(() => {
            setTimeout(() => {
                window.cartOperationInProgress = false;
            }, 500);
        });
    };

    window.removeCartItem = function(itemId) {
        CartManager.removeItem(itemId);
    };

    window.updateCartItemQuantity = function(itemId, quantity) {
        CartManager.updateQuantity(itemId, quantity);
    };

    // Override or create updateSideCart for other scripts
    window.updateSideCart = function(cartItems, cartTotal, cartSubTotal) {
        const uniqueItemsCount = cartItems ? Object.keys(cartItems).length : 0;
        const cartData = {
            cartItems: cartItems || {},
            cartTotal: cartTotal || 0,
            cartSubTotal: cartSubTotal || 0,
            cartCount: uniqueItemsCount // Use unique items count instead of total quantity
        };

        UIUpdater.updateSideCart(cartData);

        // Emit cartUpdated event for other pages
        window.dispatchEvent(new CustomEvent('cartUpdated', {
            detail: cartData
        }));

        console.log('Cart updated via global updateSideCart function:', cartData);
    };    // Initialization
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Cart SweetAlert system initializing...');

        // Initialize event handlers
        EventHandlers.init();

        // Initialize existing cart buttons from Blade template
        initializeExistingCartButtons();

        // Check if SweetAlert2 is available
        if (typeof Swal === 'undefined') {
            console.error('SweetAlert2 is not loaded. Cart notifications will not work.');
            return;
        }

        // Check required global variables
        const requiredVars = ['cartAddUrl', 'cartUpdateUrl', 'cartRemoveUrl', 'isAuthenticated'];
        const missingVars = requiredVars.filter(varName => typeof window[varName] === 'undefined');

        if (missingVars.length > 0) {
            console.warn('Missing required variables:', missingVars);
        }

        console.log('Cart SweetAlert system ready!');

        // Emit ready event
        window.dispatchEvent(new CustomEvent('cartSystemReady'));
    });

    // Initialize existing cart buttons from server-side rendered content
    function initializeExistingCartButtons() {
        console.log('Initializing existing cart buttons...');

        // Find all existing cart items and add proper event handling
        const existingCartItems = document.querySelectorAll('#sideCartItemsHolder .checkoutCartItem, #checkOutCartItemsHolder .checkoutCartItem');

        existingCartItems.forEach(item => {
            const itemId = item.getAttribute('data-id') || item.getAttribute('data-key');

            if (itemId) {
                // Add event listeners to increase/decrease buttons
                const increaseBtn = item.querySelector('.increase');
                const decreaseBtn = item.querySelector('.decrease');
                const removeBtn = item.querySelector('.removeItem');
                const quantitySpan = item.querySelector('.amount');

                console.log(`Initializing buttons for item ${itemId}`);

                if (increaseBtn) {
                    increaseBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        const currentQty = parseInt(quantitySpan?.textContent) || 0;
                        console.log(`Increase clicked for item ${itemId}, current qty: ${currentQty}`);
                        CartManager.updateQuantity(itemId, currentQty + 1);
                    });
                }

                if (decreaseBtn) {
                    decreaseBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        const currentQty = parseInt(quantitySpan?.textContent) || 0;
                        console.log(`Decrease clicked for item ${itemId}, current qty: ${currentQty}`);
                        if (currentQty > 1) {
                            CartManager.updateQuantity(itemId, currentQty - 1);
                        } else {
                            CartManager.removeItem(itemId);
                        }
                    });
                }

                if (removeBtn) {
                    removeBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log(`Remove clicked for item ${itemId}`);
                        CartManager.removeItem(itemId);
                    });
                }
            }
        });

        console.log(`Initialized ${existingCartItems.length} existing cart items`);
    }

    // Export for module systems
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = { CartManager, UIUpdater, Utils };
    }

    // Global functions for product pages
    window.updateCartItemQuantity = function(itemId, quantity) {
        console.log(`Global updateCartItemQuantity called for item ${itemId} with quantity ${quantity}`);
        CartManager.updateQuantity(itemId, quantity);
    };

    window.removeCartItem = function(itemId) {
        console.log(`Global removeCartItem called for item ${itemId}`);
        CartManager.removeItem(itemId);
    };

})();
