
      // Estado de la aplicación
      const state = {
        cart: [],
        orders: [],
        currentView: 'customer',
        selectedCategory: 'all',
        orderCounter: 1001
      };

      // Productos del menú
      let products = [];

      // Inicialización
        
        document.addEventListener('DOMContentLoaded', () => {
  loadFromLocalStorage();
  setupEventListeners();
  updateAdminStats();
  renderAdminOrders();
  loadProducts(); // 👈 ahora carga desde BD
});
    
      async function loadProducts() {
  const res = await fetch("../controllers/api.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      accion: "obtener_productos"
    })
  });

  const data = await res.json();
  products = data;
  renderProducts();
}

      // Event Listeners
      function setupEventListeners() {
        // Toggle de vista
        document.getElementById('customerViewBtn').addEventListener('click', () => switchView('customer'));
        document.getElementById('adminViewBtn').addEventListener('click', () => switchView('admin'));

        // Carrito
        document.getElementById('cartBtn').addEventListener('click', toggleCart);

        // Filtros de categoría
        document.querySelectorAll('.category-filter').forEach(btn => {
          btn.addEventListener('click', (e) => filterCategory(e.target.dataset.category));
        });

        // Checkout
        document.getElementById('checkoutBtn').addEventListener('click', openCheckout);
        document.getElementById('checkoutForm').addEventListener('submit', handleCheckout);

        // Tabs de admin
        document.querySelectorAll('.tab').forEach(tab => {
          tab.addEventListener('click', (e) => switchTab(e.target.dataset.tab));
        });

        // Filtro de órdenes
        document.getElementById('orderFilter').addEventListener('change', (e) => {
          renderAdminOrders(e.target.value);
        });
      }

      // Renderizar productos
      function renderProducts() {
        const grid = document.getElementById('productGrid');
        const filtered = state.selectedCategory === 'all' 
          ? products 
          : products.filter(p => p.category === state.selectedCategory);

        grid.innerHTML = filtered.map(product => `
          <div class="product-card">
            <div class="product-content">
              <h3 class="text-lg mb-2" style="font-weight: 600;">${product.name}</h3>
              <p class="text-sm text-gray-600 mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${product.description}</p>
              <div class="flex items-center justify-between">
                <span class="text-2xl" style="color: var(--orange-500); font-weight: 600;">${product.price.toLocaleString('es-CO')}</span>
                <button onclick="addToCart('${product.id}')" class="btn btn-primary">Agregar</button>
              </div>
            </div>
          </div>
        `).join('');
      }

      // Filtrar por categoría
      function filterCategory(category) {
        state.selectedCategory = category;
        
        document.querySelectorAll('.category-filter').forEach(btn => {
          btn.classList.remove('active');
          btn.style.background = 'white';
          btn.style.color = 'var(--gray-700)';
          btn.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
        });
        
        const activeBtn = document.querySelector(`[data-category="${category}"]`);
        activeBtn.classList.add('active');
        activeBtn.style.background = 'var(--orange-500)';
        activeBtn.style.color = 'white';
        activeBtn.style.boxShadow = '0 4px 6px -1px rgba(0,0,0,0.1)';
        activeBtn.style.transform = 'scale(1.05)';
        
        renderProducts();
      }

      // Agregar al carrito
      function addToCart(productId) {
        const product = products.find(p => p.id == productId);  
        const existing = state.cart.find(item => item.id === productId);
        
        if (existing) {
          existing.quantity++;
          showToast('success', 'Cantidad actualizada', `${product.name} x${existing.quantity}`);
        } else {
          state.cart.push({ ...product, quantity: 1 });
          showToast('success', 'Agregado al carrito', product.name);
        }
        
        updateCartUI();
        saveToLocalStorage();
      }

      // Actualizar UI del carrito
      function updateCartUI() {
        const count = state.cart.reduce((sum, item) => sum + item.quantity, 0);
        const countEl = document.getElementById('cartCount');
        
        if (count > 0) {
          countEl.textContent = count;
          countEl.style.display = 'flex';
          countEl.style.alignItems = 'center';
          countEl.style.justifyContent = 'center';
        } else {
          countEl.style.display = 'none';
        }

        renderCartItems();
        updateCartTotals();
      }

      // Renderizar items del carrito
      function renderCartItems() {
        const container = document.getElementById('cartContent');
        
        if (state.cart.length === 0) {
          container.innerHTML = `
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: var(--gray-500);">
              <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem;">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
              </svg>
              <p class="text-xl">Tu carrito está vacío</p>
              <p class="text-sm mt-2">¡Agrega algunos chuzos deliciosos!</p>
            </div>
          `;
          return;
        }

        container.innerHTML = state.cart.map(item => `
          <div style="background: var(--orange-50); border-radius: var(--radius); padding: 1rem; margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem;">
              <div style="flex: 1;">
                <h3 style="color: var(--gray-900); font-weight: 500;">${item.name}</h3>
                <p class="text-sm text-gray-600" style="margin-top: 0.25rem;">${item.price.toLocaleString('es-CO')} c/u</p>
              </div>
              <button onclick="removeFromCart('${item.id}')" style="background: none; border: none; color: var(--red-500); cursor: pointer;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <line x1="18" y1="6" x2="6" y2="18"></line>
                  <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
              </button>
            </div>
            
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <div style="display: flex; align-items: center; gap: 0.75rem;">
                <button onclick="updateQuantity('${item.id}', ${item.quantity - 1})" style="width: 2rem; height: 2rem; background: white; border: 1px solid var(--orange-200); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                  </svg>
                </button>
                <span style="width: 2rem; text-align: center; font-weight: 500;">${item.quantity}</span>
                <button onclick="updateQuantity('${item.id}', ${item.quantity + 1})" style="width: 2rem; height: 2rem; background: white; border: 1px solid var(--orange-200); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                  </svg>
                </button>
              </div>
              <p style="color: var(--orange-600); font-weight: 500;">${(item.price * item.quantity).toLocaleString('es-CO')}</p>
            </div>
          </div>
        `).join('');
      }

      // Actualizar cantidad
      function updateQuantity(productId, newQuantity) {
        if (newQuantity === 0) {
          removeFromCart(productId);
          return;
        }
        
        const item = state.cart.find(i => i.id === productId);
        if (item) {
          item.quantity = newQuantity;
          updateCartUI();
          saveToLocalStorage();
        }
      }

      // Remover del carrito
      function removeFromCart(productId) {
        state.cart = state.cart.filter(item => item.id !== productId);
        showToast('info', 'Producto eliminado', 'El producto fue removido del carrito');
        updateCartUI();
        saveToLocalStorage();
      }

      // Actualizar totales
      function updateCartTotals() {
        const subtotal = state.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const delivery = state.cart.length > 0 ? 3000 : 0;
        const total = subtotal + delivery;

        document.getElementById('cartSubtotal').textContent = `${subtotal.toLocaleString('es-CO')}`;
        document.getElementById('cartDelivery').textContent = `${delivery.toLocaleString('es-CO')}`;
        document.getElementById('cartTotal').textContent = `${total.toLocaleString('es-CO')}`;
        document.getElementById('checkoutTotal').textContent = `${total.toLocaleString('es-CO')}`;
      }

      // Toggle carrito
      function toggleCart() {
        const modal = document.getElementById('cartModal');
        modal.classList.toggle('hidden');
      }

      // Abrir checkout
      function openCheckout() {
        if (state.cart.length === 0) {
          showToast('error', 'Carrito vacío', 'Agrega productos antes de continuar');
          return;
        }
        toggleCart();
        document.getElementById('checkoutModal').classList.remove('hidden');
      }

      // Cerrar checkout
      function closeCheckout() {
        document.getElementById('checkoutModal').classList.add('hidden');
        document.getElementById('checkoutForm').reset();
      }

      // Procesar checkout
      function handleCheckout(e) {
        e.preventDefault();
        
        const formData = {
          name: document.getElementById('customerName').value,
          phone: document.getElementById('customerPhone').value,
          email: document.getElementById('customerEmail').value,
          address: document.getElementById('customerAddress').value,
          paymentMethod: document.querySelector('input[name="paymentMethod"]:checked').value
        };

        const subtotal = state.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const deliveryFee = 3000;
        const total = subtotal + deliveryFee;

        const order = {
          id: `ORD-${Date.now()}`,
          orderNumber: state.orderCounter++,
          invoiceNumber: `INV-2025-${String(state.orderCounter - 1).padStart(3, '0')}`,
          customerName: formData.name,
          customerPhone: formData.phone,
          customerEmail: formData.email,
          customerAddress: formData.address,
          items: [...state.cart],
          subtotal,
          deliveryFee,
          tax: 0,
          total,
          status: 'pendiente',
          paymentMethod: formData.paymentMethod,
          paymentStatus: formData.paymentMethod === 'tarjeta' ? 'pagado' : 'pendiente',
          createdAt: new Date().toISOString(),
          updatedAt: new Date().toISOString(),
          deliveryTime: '30-45 min'
        };

        state.orders.unshift(order);

        
        
        // Limpiar carrito
        state.cart = [];
        updateCartUI();
        
        // Cerrar checkout
        closeCheckout();
        
        // Mostrar confirmación
        document.getElementById('confirmedOrderNumber').textContent = `#${order.orderNumber}`;
        document.getElementById('confirmedInvoiceNumber').textContent = `Factura: ${order.invoiceNumber}`;
        document.getElementById('confirmationModal').classList.remove('hidden');
        
        showToast('success', '🎉 Nuevo pedido recibido', `Pedido #${order.orderNumber} de ${order.customerName}`);
        
        setTimeout(() => {
          showToast('info', '📋 Factura generada', `Factura ${order.invoiceNumber} lista`);
        }, 2000);
        
        saveToLocalStorage();
        updateAdminStats();
        renderAdminOrders();
      }

      // Cerrar confirmación
      function closeConfirmation() {
        document.getElementById('confirmationModal').classList.add('hidden');
      }

      // Switch de vista
      function switchView(view) {
        state.currentView = view;
        
        document.getElementById('customerView').classList.toggle('hidden', view !== 'customer');
        document.getElementById('adminView').classList.toggle('hidden', view !== 'admin');
        
        document.getElementById('customerViewBtn').classList.toggle('active', view === 'customer');
        document.getElementById('adminViewBtn').classList.toggle('active', view === 'admin');
        
        if (view === 'admin') {
          updateAdminStats();
          renderAdminOrders();
        }
      }

      // Switch de tabs
      function switchTab(tabName) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
        
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
        document.getElementById(`${tabName}Tab`).classList.remove('hidden');
        
        if (tabName === 'invoices') {
          renderInvoicesTable();
        }
      }

      // Actualizar estadísticas de admin
      function updateAdminStats() {
        const today = new Date().toDateString();
        const todayOrders = state.orders.filter(o => new Date(o.createdAt).toDateString() === today);
        const pending = state.orders.filter(o => o.status === 'pendiente');
        const revenue = todayOrders.reduce((sum, o) => sum + o.total, 0);
        const average = todayOrders.length > 0 ? revenue / todayOrders.length : 0;

        document.getElementById('statOrders').textContent = todayOrders.length;
        document.getElementById('statRevenue').textContent = `${revenue.toLocaleString('es-CO')}`;
        document.getElementById('statPending').textContent = pending.length;
        document.getElementById('statAverage').textContent = `$${Math.round(average).toLocaleString('es-CO')}`;

        // Actualizar badge de pendientes
        const badge = document.getElementById('pendingBadge');
        if (pending.length > 0) {
          badge.textContent = pending.length;
          badge.style.display = 'inline-block';
        } else {
          badge.style.display = 'none';
        }

        // Tabla de órdenes recientes
        const tbody = document.querySelector('#recentOrdersTable tbody');
        tbody.innerHTML = state.orders.slice(0, 5).map(order => `
          <tr>
            <td>#${order.orderNumber}</td>
            <td>${order.customerName}</td>
            <td>$${order.total.toLocaleString('es-CO')}</td>
            <td><span class="badge ${getStatusClass(order.status)}">${order.status}</span></td>
            <td>
              <button onclick="viewOrderDetails('${order.id}')" class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">Ver</button>
            </td>
          </tr>
        `).join('');
      }

      // Renderizar órdenes en admin
      function renderAdminOrders(filter = 'all') {
        const filtered = filter === 'all' 
          ? state.orders 
          : state.orders.filter(o => o.status === filter);

        const container = document.getElementById('ordersList');
        container.innerHTML = filtered.map(order => `
          <div class="bg-white rounded-lg shadow p-6 mb-4">
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
              <div>
                <h3 class="text-lg mb-1">Pedido #${order.orderNumber}</h3>
                <p class="text-sm text-gray-600">${order.customerName}</p>
                <p class="text-sm text-gray-600">📞 ${order.customerPhone}</p>
                <p class="text-sm text-gray-600">📍 ${order.customerAddress}</p>
                <p class="text-sm text-gray-500 mt-2">${new Date(order.createdAt).toLocaleString('es-CO')}</p>
              </div>
              <div style="text-align: right;">
                <p class="text-2xl mb-2" style="color: var(--orange-500); font-weight: 600;">$${order.total.toLocaleString('es-CO')}</p>
                <p class="text-sm mb-2">${order.paymentMethod === 'efectivo' ? '💵 Efectivo' : order.paymentMethod === 'tarjeta' ? '💳 Tarjeta' : '📱 Transferencia'}</p>
                <span class="badge ${getPaymentStatusClass(order.paymentStatus)}">${order.paymentStatus}</span>
              </div>
            </div>

            <div style="border-top: 1px solid var(--border); padding-top: 1rem; margin-bottom: 1rem;">
              <p class="text-sm mb-2" style="font-weight: 500;">Productos:</p>
              ${order.items.map(item => `
                <div style="display: flex; justify-content: space-between; font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.25rem;">
                  <span>${item.quantity}x ${item.name}</span>
                  <span>$${(item.price * item.quantity).toLocaleString('es-CO')}</span>
                </div>
              `).join('')}
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem;">
              <div>
                <label style="display: block; font-size: 0.75rem; color: var(--gray-600); margin-bottom: 0.25rem;">Estado del Pedido</label>
                <select onchange="updateOrderStatus('${order.id}', this.value)" style="width: 100%; padding: 0.5rem; font-size: 0.875rem;">
                  <option value="pendiente" ${order.status === 'pendiente' ? 'selected' : ''}>Pendiente</option>
                  <option value="en-preparacion" ${order.status === 'en-preparacion' ? 'selected' : ''}>En Preparación</option>
                  <option value="en-camino" ${order.status === 'en-camino' ? 'selected' : ''}>En Camino</option>
                  <option value="entregado" ${order.status === 'entregado' ? 'selected' : ''}>Entregado</option>
                  <option value="cancelado" ${order.status === 'cancelado' ? 'selected' : ''}>Cancelado</option>
                </select>
              </div>

              <div>
                <label style="display: block; font-size: 0.75rem; color: var(--gray-600); margin-bottom: 0.25rem;">Estado de Pago</label>
                <select onchange="updatePaymentStatus('${order.id}', this.value)" style="width: 100%; padding: 0.5rem; font-size: 0.875rem;">
                  <option value="pendiente" ${order.paymentStatus === 'pendiente' ? 'selected' : ''}>Pendiente</option>
                  <option value="pagado" ${order.paymentStatus === 'pagado' ? 'selected' : ''}>Pagado</option>
                  <option value="reembolsado" ${order.paymentStatus === 'reembolsado' ? 'selected' : ''}>Reembolsado</option>
                </select>
              </div>

              <div style="display: flex; align-items: flex-end;">
                <button onclick="viewInvoice('${order.id}')" class="btn btn-primary" style="width: 100%; font-size: 0.875rem;">Ver Factura</button>
              </div>
            </div>
          </div>
        `).join('');
      }

      // Actualizar estado de orden
      function updateOrderStatus(orderId, newStatus) {
        const order = state.orders.find(o => o.id === orderId);
        if (order) {
          order.status = newStatus;
          order.updatedAt = new Date().toISOString();
          
          const statusMessages = {
            'pendiente': '⏳ Pedido en espera',
            'en-preparacion': '👨‍🍳 Preparando pedido',
            'en-camino': '🚚 Pedido en camino',
            'entregado': '✅ Pedido entregado',
            'cancelado': '❌ Pedido cancelado'
          };
          
          showToast('success', statusMessages[newStatus], `Pedido #${order.orderNumber} - ${order.customerName}`);
          saveToLocalStorage();
          updateAdminStats();
          renderAdminOrders(document.getElementById('orderFilter').value);
        }
      }

      // Actualizar estado de pago
      function updatePaymentStatus(orderId, newStatus) {
        const order = state.orders.find(o => o.id === orderId);
        if (order) {
          order.paymentStatus = newStatus;
          order.updatedAt = new Date().toISOString();
          
          showToast('success', '💰 Estado de pago actualizado', `Pedido #${order.orderNumber} - ${newStatus}`);
          saveToLocalStorage();
          updateAdminStats();
          renderAdminOrders(document.getElementById('orderFilter').value);
        }
      }

      // Ver detalles de orden
      function viewOrderDetails(orderId) {
        const order = state.orders.find(o => o.id === orderId);
        if (order) {
          viewInvoice(orderId);
        }
      }
  // Ver factura
      function viewInvoice(orderId) {
        const order = state.orders.find(o => o.id === orderId);
        if (!order) return;

        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
          <div class="modal-content" style="max-width: 800px;" onclick="event.stopPropagation()">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
              <h2 class="text-xl">Factura de Venta</h2>
              <div style="display: flex; gap: 0.5rem;">
                <button onclick="printInvoice()" class="btn btn-secondary" style="padding: 0.5rem;">🖨️ Imprimir</button>
                <button onclick="this.closest('.modal-overlay').remove()" style="background: none; border: none; cursor: pointer; font-size: 1.5rem;">×</button>
              </div>
            </div>
            
            <div style="padding: 2rem; max-height: 70vh; overflow-y: auto;">
              <div style="display: flex; justify-content: space-between; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 2px solid var(--orange-500);">
                <div>
                  <h1 style="font-size: 1.875rem; color: var(--orange-500); margin-bottom: 0.5rem; font-weight: 700;">Chuzos El Sabor</h1>
                  <p class="text-sm text-gray-600">NIT: 900.123.456-7</p>
                  <p class="text-sm text-gray-600">Calle Principal #123-45</p>
                  <p class="text-sm text-gray-600">Tel: +57 300 123 4567</p>
                  <p class="text-sm text-gray-600">info@chuzoselsabor.com</p>
                </div>
                <div style="text-align: right;">
                  <div style="background: var(--orange-500); color: white; padding: 0.5rem 1rem; border-radius: var(--radius); margin-bottom: 0.5rem;">
                    <p class="text-sm">FACTURA DE VENTA</p>
                  </div>
                  <p class="text-sm mb-1"><span class="text-gray-600">No. Factura:</span> ${order.invoiceNumber}</p>
                  <p class="text-sm mb-1"><span class="text-gray-600">No. Pedido:</span> #${order.orderNumber}</p>
                  <p class="text-sm"><span class="text-gray-600">Fecha:</span> ${new Date(order.createdAt).toLocaleDateString('es-CO')}</p>
                </div>
              </div>

              <div style="margin-bottom: 2rem;">
                <h3 style="font-size: 0.875rem; text-transform: uppercase; color: var(--gray-600); margin-bottom: 0.75rem;">Información del Cliente</h3>
                <div style="background: var(--gray-50); padding: 1rem; border-radius: var(--radius);">
                  <p class="mb-1"><span class="text-gray-600">Nombre:</span> ${order.customerName}</p>
                  <p class="mb-1"><span class="text-gray-600">Teléfono:</span> ${order.customerPhone}</p>
                  ${order.customerEmail ? `<p class="mb-1"><span class="text-gray-600">Email:</span> ${order.customerEmail}</p>` : ''}
                  <p><span class="text-gray-600">Dirección:</span> ${order.customerAddress}</p>
                </div>
              </div>

              <div style="margin-bottom: 2rem;">
                <table style="width: 100%; border-collapse: collapse;">
                  <thead>
                    <tr style="border-bottom: 2px solid var(--gray-300);">
                      <th style="text-align: left; padding: 0.75rem 0; font-size: 0.875rem; color: var(--gray-600);">Producto</th>
                      <th style="text-align: center; padding: 0.75rem 0; font-size: 0.875rem; color: var(--gray-600);">Cant.</th>
                      <th style="text-align: right; padding: 0.75rem 0; font-size: 0.875rem; color: var(--gray-600);">Precio Unit.</th>
                      <th style="text-align: right; padding: 0.75rem 0; font-size: 0.875rem; color: var(--gray-600);">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${order.items.map(item => `
                      <tr style="border-bottom: 1px solid var(--gray-200);">
                        <td style="padding: 0.75rem 0;">${item.name}</td>
                        <td style="text-align: center; padding: 0.75rem 0;">${item.quantity}</td>
                        <td style="text-align: right; padding: 0.75rem 0;">$${item.price.toLocaleString('es-CO')}</td>
                        <td style="text-align: right; padding: 0.75rem 0;">$${(item.price * item.quantity).toLocaleString('es-CO')}</td>
                      </tr>
                    `).join('')}
                  </tbody>
                </table>
              </div>

              <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
                <div style="width: 16rem;">
                  <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--gray-200);">
                    <span class="text-gray-600">Subtotal:</span>
                    <span>$${order.subtotal.toLocaleString('es-CO')}</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--gray-200);">
                    <span class="text-gray-600">Domicilio:</span>
                    <span>$${order.deliveryFee.toLocaleString('es-CO')}</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-top: 2px solid var(--gray-300); font-size: 1.125rem; font-weight: 600;">
                    <span>Total:</span>
                    <span style="color: var(--orange-500);">$${order.total.toLocaleString('es-CO')}</span>
                  </div>
                </div>
              </div>

              <div style="background: var(--gray-50); padding: 1rem; border-radius: var(--radius); margin-bottom: 2rem;">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; font-size: 0.875rem;">
                  <div>
                    <p class="text-gray-600 mb-1">Método de Pago:</p>
                    <p class="capitalize">${order.paymentMethod === 'efectivo' ? '💵 Efectivo' : order.paymentMethod === 'tarjeta' ? '💳 Tarjeta' : '📱 Transferencia'}</p>
                  </div>
                  <div>
                    <p class="text-gray-600 mb-1">Estado de Pago:</p>
                    <p><span class="badge ${getPaymentStatusClass(order.paymentStatus)}">${order.paymentStatus.toUpperCase()}</span></p>
                  </div>
                </div>
              </div>

              <div style="text-align: center; font-size: 0.875rem; color: var(--gray-600); padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
                <p style="margin-bottom: 0.5rem;">¡Gracias por su compra!</p>
                <p>Esta es una factura generada electrónicamente.</p>
                <p style="margin-top: 1rem;">Chuzos El Sabor - Los mejores chuzos de la ciudad</p>
              </div>
            </div>
          </div>
        `;
        
        modal.addEventListener('click', (e) => {
          if (e.target === modal) modal.remove();
        });
        
        document.body.appendChild(modal);
      }

      // Imprimir factura
      function printInvoice() {
        window.print();
      }

      // Renderizar tabla de facturas
      function renderInvoicesTable() {
        const tbody = document.querySelector('#invoicesTable tbody');
        tbody.innerHTML = state.orders.map(order => `
          <tr>
            <td>${order.invoiceNumber}</td>
            <td>#${order.orderNumber}</td>
            <td>${order.customerName}</td>
            <td>${new Date(order.createdAt).toLocaleDateString('es-CO')}</td>
            <td>$${order.total.toLocaleString('es-CO')}</td>
            <td><span class="badge ${getPaymentStatusClass(order.paymentStatus)}">${order.paymentStatus}</span></td>
            <td>
              <button onclick="viewInvoice('${order.id}')" class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">Ver</button>
            </td>
          </tr>
        `).join('');
      }

      // Obtener clase de estado
      function getStatusClass(status) {
        const classes = {
          'pendiente': 'badge-yellow',
          'en-preparacion': 'badge-blue',
          'en-camino': 'badge-purple',
          'entregado': 'badge-green',
          'cancelado': 'badge-red'
        };
        return classes[status] || 'badge-yellow';
      }

      // Obtener clase de estado de pago
      function getPaymentStatusClass(status) {
        const classes = {
          'pendiente': 'badge-yellow',
          'pagado': 'badge-green',
          'reembolsado': 'badge-red'
        };
        return classes[status] || 'badge-yellow';
      }

      // Sistema de notificaciones Toast
      function showToast(type, title, description) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const icons = {
          success: '✅',
          error: '❌',
          info: 'ℹ️'
        };
        
        toast.innerHTML = `
          <div style="font-size: 1.5rem;">${icons[type]}</div>
          <div style="flex: 1;">
            <p style="font-weight: 500; margin-bottom: 0.25rem;">${title}</p>
            <p style="font-size: 0.875rem; color: var(--gray-600);">${description}</p>
          </div>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
          toast.style.animation = 'slideOut 0.3s ease';
          setTimeout(() => toast.remove(), 300);
        }, 5000);
      }

      // LocalStorage
      function saveToLocalStorage() {
        localStorage.setItem('chuzosApp', JSON.stringify({
          cart: state.cart,
          orders: state.orders,
          orderCounter: state.orderCounter
        }));
      }

      function loadFromLocalStorage() {
        const saved = localStorage.getItem('chuzosApp');
        if (saved) {
          const data = JSON.parse(saved);
          state.cart = data.cart || [];
          state.orders = data.orders || [];
          state.orderCounter = data.orderCounter || 1001;
          updateCartUI();
        }
      }

      // Animación de salida para toast
      const style = document.createElement('style');
      style.textContent = `
        @keyframes slideOut {
          to {
            transform: translateX(120%);
            opacity: 0;
          }
        }
        
        @media print {
          body * {
            visibility: hidden;
          }
          .modal-content, .modal-content * {
            visibility: visible;
          }
          .modal-overlay {
            position: static;
            background: white;
          }
          .modal-content {
            position: absolute;
            left: 0;
            top: 0;
            margin: 0;
            padding: 0;
            box-shadow: none;
          }
          .btn, button {
            display: none !important;
          }
        }
      `;
      document.head.appendChild(style);

      // Datos de ejemplo iniciales
      if (state.orders.length === 0) {
        const sampleOrders = [
          
          {
            id: 'ORD-002',
            orderNumber: 1002,
            invoiceNumber: 'INV-2025-002',
            customerName: 'Carlos Ramírez',
            customerPhone: '310 987 6543',
            customerEmail: 'carlos@example.com',
            customerAddress: 'Carrera 45 #23-11',
            items: [{id: '7', name: 'Chuzo Mixto Tradicional', price: 10000, quantity: 3}],
            subtotal: 30000,
            deliveryFee: 3000,
            tax: 0,
            total: 33000,
            status: 'en-preparacion',
            paymentMethod: 'tarjeta',
            paymentStatus: 'pagado',
            createdAt: new Date(Date.now() - 900000).toISOString(),
            updatedAt: new Date(Date.now() - 600000).toISOString(),
            deliveryTime: '30-45 min'
          },
          
        ];
        
        state.orders = sampleOrders;
        state.orderCounter = 1001;
        saveToLocalStorage();
      }
