    <?php
    require_once "../config/database.php";

    $conn = Database::conectar();

    // usa el nombre real de tu tabla
    $productos = $conn->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    
    
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/script.js"></script>
        <title>Mi Chuzito</title>
    
    </head> 
    <body>
        <!-- Toggle de Vista -->
        <div class="view-toggle">
        <button id="customerViewBtn" class="active">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
            <span>Cliente</span>
        </button>
        <button id="adminViewBtn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7"></rect>
            <rect x="14" y="3" width="7" height="7"></rect>
            <rect x="14" y="14" width="7" height="7"></rect>
            <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <span>Admin</span>
        </button>
        </div>

        <!-- Vista de Cliente -->
        <div id="customerView">
        <!-- Header -->
        <header class="header">
            <div class="max-w-7xl mx-auto px-4 py-6 flex items-center justify-between">
            <div>
                <img src="img/logoM.png" alt="" class="logoM">
                <h1 class="text-3xl font-medium">Mi Chuzito</h1>
                <p style="color: rgba(255,255,255,0.9); margin-top: 0.25rem;">Las mejores comidas de la ciudad</p>
            </div>
            <button id="cartBtn" class="btn btn-primary" style="position: relative;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <span class="hidden sm:inline">Carrito</span>
                <span id="cartCount" class="badge" style="position: absolute; top: -8px; right: -8px; background: var(--red-500); color: white; min-width: 20px; height: 20px; display: none;">0</span>
            </button>
            </div>
        </header>

        <!-- Info Banner -->
        <div style="background: var(--orange-100); border-bottom: 1px solid var(--orange-200);">
            <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap items-center justify-center gap-6" style="font-size: 0.875rem;">
            <div class="flex items-center gap-2">
                 <span>+57 300 123 4567</span>
            </div>
            <div class="flex items-center gap-2">
                 <span>Lun-Dom: 11:00 AM - 10:00 PM</span>
            </div>
            <div class="flex items-center gap-2">
                 <span>Domicilios en toda la ciudad</span>
            </div>
            </div>
        </div>

        <!-- Hero Section -->
        <div style="background: linear-gradient(to bottom, var(--orange-50), white); padding: 3rem 1rem;">
            <div class="max-w-7xl mx-auto" style="text-align: center;">
            <h2 class="text-4xl mb-4" style="font-weight: 700;">¡Deliciosa comida a Domicilio!</h2>
            <p class="text-xl text-gray-600" style="max-width: 42rem; margin: 0 auto;">Ordenar es rápido, fácil y seguro.</p>
            </div>
        </div>

        <!-- Menú -->
        <div class="max-w-7xl mx-auto px-4 py-12">
            <h2 class="text-3xl mb-6">Nuestro Menú</h2>
            
            <!-- Filtros de categoría -->
            <div id="categoryFilters" class="flex gap-3 overflow-x-auto pb-4 mb-8">
            <button class="category-filter active" data-category="all" style="padding: 0.75rem 1.5rem; border: none; border-radius: 9999px; background: var(--orange-500); color: white; cursor: pointer; white-space: nowrap; font-weight: 500;">Todos</button>
            <button class="category-filter" data-category="res" style="padding: 0.75rem 1.5rem; border: none; border-radius: 9999px; background: white; color: var(--gray-700); cursor: pointer; white-space: nowrap; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">Chuzo de Res</button>
            <button class="category-filter" data-category="cerdo" style="padding: 0.75rem 1.5rem; border: none; border-radius: 9999px; background: white; color: var(--gray-700); cursor: pointer; white-space: nowrap; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">Chuzo de Cerdo</button>
            <button class="category-filter" data-category="pollo" style="padding: 0.75rem 1.5rem; border: none; border-radius: 9999px; background: white; color: var(--gray-700); cursor: pointer; white-space: nowrap; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">Chuzo de Pollo</button>
            <button class="category-filter" data-category="mixto" style="padding: 0.75rem 1.5rem; border: none; border-radius: 9999px; background: white; color: var(--gray-700); cursor: pointer; white-space: nowrap; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">Mixto</button>
            <button class="category-filter" data-category="especial" style="padding: 0.75rem 1.5rem; border: none; border-radius: 9999px; background: white; color: var(--gray-700); cursor: pointer; white-space: nowrap; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">Especiales</button>
            </div>

            <!-- Grid de productos -->
            <div id="productGrid" class="product-grid"></div>
        </div>
        </div>

        <!-- Vista de Admin -->
        <div id="adminView" class="hidden">
        <header style="background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div class="max-w-7xl mx-auto px-4 py-4">
            <h1 class="text-2xl text-gray-900">Panel de Administración - Chuzos El Sabor</h1>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-4 py-8">
            <!-- Tabs de navegación -->
            <div class="tabs">
            <button class="tab active" data-tab="dashboard"> Dashboard</button>
            <button class="tab" data-tab="orders"> Pedidos <span id="pendingBadge" class="badge badge-red" style="margin-left: 0.5rem; display: none;">0</span></button>
            <button class="tab" data-tab="invoices"> Facturas</button>
            <button class="tab" data-tab="inventory"> Inventario</button>
            </div>

            <!-- Dashboard Tab -->
            <div id="dashboardTab" class="tab-content">
            <div class="grid gap-6" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); margin-bottom: 1.5rem;">
                <div class="stat-card">
                <p class="stat-label">Pedidos Hoy</p>
                <p class="stat-value" id="statOrders">0</p>
                <p class="text-sm" style="color: var(--green-600); margin-top: 0.25rem;">+12% vs ayer</p>
                </div>
                <div class="stat-card">
                <p class="stat-label">Ingresos Hoy</p>
                <p class="stat-value" id="statRevenue">$0</p>
                <p class="text-sm" style="color: var(--green-600); margin-top: 0.25rem;">+8% vs ayer</p>
                </div>
                <div class="stat-card">
                <p class="stat-label">Pedidos Pendientes</p>
                <p class="stat-value" id="statPending">0</p>
                <p class="text-sm" style="color: var(--orange-600); margin-top: 0.25rem;">Requieren atención</p>
                </div>
                <div class="stat-card">
                <p class="stat-label">Ticket Promedio</p>
                <p class="stat-value" id="statAverage">$0</p>
                <p class="text-sm" style="color: var(--green-600); margin-top: 0.25rem;">+5% vs ayer</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h2 class="text-xl mb-6">Ventas de la Semana</h2>
                <div class="chart-placeholder">Gráfica de ventas (Chart.js o similar)</div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                <h2 class="text-xl">Pedidos Recientes</h2>
                </div>
                <div style="overflow-x: auto;">
                <table id="recentOrdersTable">
                    <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
            </div>
            </div>

            <!-- Orders Tab -->
            <div id="ordersTab" class="tab-content hidden">
            <div class="bg-white p-4 rounded-lg shadow mb-4">
                <select id="orderFilter" style="width: auto;">
                <option value="all">Todos los pedidos</option>
                <option value="pendiente">Pendientes</option>
                <option value="en-preparacion">En Preparación</option>
                <option value="en-camino">En Camino</option>
                <option value="entregado">Entregados</option>
                <option value="cancelado">Cancelados</option>
                </select>
            </div>
            <div id="ordersList"></div>
            </div>

            <!-- Invoices Tab -->
            <div id="invoicesTab" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b flex items-center justify-between">
                <h2 class="text-xl">Gestión de Facturas</h2>
                <button class="btn btn-primary"> Descargar Reporte</button>
                </div>
                <div style="overflow-x: auto;">
                <table id="invoicesTable">
                    <thead>
                    <tr>
                        <th>Factura</th>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado Pago</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
            </div>
            </div>

            <!-- Inventory Tab -->
            <div id="inventoryTab" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                <h2 class="text-xl">Control de Inventario</h2>
                </div>
                <div style="overflow-x: auto;">
                <table>
                    <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Chuzo de Res Tradicional</td>
                        <td>Res</td>
                        <td>50</td>
                        <td>$8,000</td>
                        <td><span class="badge badge-green">Disponible</span></td>
                    </tr>
                    <tr>
                        <td>Chuzo de Cerdo Clásico</td>
                        <td>Cerdo</td>
                        <td>45</td>
                        <td>$7,500</td>
                        <td><span class="badge badge-green">Disponible</span></td>
                    </tr>
                    <tr>
                        <td>Chuzo Especial de la Casa</td>
                        <td>Especial</td>
                        <td style="color: var(--orange-600);">8</td>
                        <td>$15,000</td>
                        <td><span class="badge badge-yellow">Stock Bajo</span></td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
        </div>

        <!-- Modal Carrito -->
        <div id="cartModal" class="hidden">
        <div class="modal-overlay" onclick="if(event.target === this) toggleCart()">
            <div class="cart-sidebar">
            <div class="cart-header flex items-center justify-between">
                <h2 class="text-2xl">Tu Pedido</h2>
                <button onclick="toggleCart()" style="background: none; border: none; color: white; cursor: pointer;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                </button>
            </div>

            <div id="cartContent" class="cart-items">
                <!-- Items del carrito se insertarán aquí -->
            </div>

            <div class="cart-footer">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--gray-600);">
                <span>Subtotal</span>
                <span id="cartSubtotal">$0</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--gray-600);">
                <span>Domicilio</span>
                <span id="cartDelivery">$3,000</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 600; padding-top: 0.75rem; border-top: 1px solid var(--border); margin-bottom: 1rem;">
                <span>Total</span>
                <span id="cartTotal" style="color: var(--orange-500);">$0</span>
                </div>
                <button id="checkoutBtn" class="btn btn-primary w-full" style="width: 100%;">Continuar con el pedido</button>
            </div>
            </div>
        </div>
        </div>

        <!-- Modal Checkout -->
        <div id="checkoutModal" class="hidden">
        <div class="modal-overlay" onclick="if(event.target === this) closeCheckout()">
            <div class="modal-content">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h2 class="text-2xl">Finalizar Pedido</h2>
                <button onclick="closeCheckout()" style="background: none; border: none; cursor: pointer;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                </button>
            </div>
            
            <form id="checkoutForm" style="padding: 1.5rem;">
                <div style="margin-bottom: 1rem;">
                <label>Nombre completo *</label>
                <input type="text" id="customerName" required placeholder="Juan Pérez">
                </div>

                <div style="margin-bottom: 1rem;">
                <label>Teléfono *</label>
                <input type="tel" id="customerPhone" required placeholder="300 123 4567">
                </div>

                <div style="margin-bottom: 1rem;">
                <label>Email (opcional)</label>
                <input type="email" id="customerEmail" placeholder="tu@email.com">
                </div>

                <div style="margin-bottom: 1rem;">
                <label>Dirección de entrega *</label>
                <textarea id="customerAddress" required rows="3" placeholder="Calle 123 #45-67, Apto 501"></textarea>
                </div>

                <div style="margin-bottom: 1rem;">
                <label>Método de pago *</label>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius); cursor: pointer;">
                    <input type="radio" name="paymentMethod" value="efectivo" checked>
                    <span> Efectivo contra entrega</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius); cursor: pointer;">
                    <input type="radio" name="paymentMethod" value="tarjeta">
                    <span> Tarjeta débito/crédito</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius); cursor: pointer;">
                    <input type="radio" name="paymentMethod" value="transferencia">
                    <span> Transferencia / Nequi</span>
                    </label>
                </div>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 600; padding: 1rem 0; border-top: 1px solid var(--border); margin-top: 1rem;">
                <span>Total a pagar</span>
              <span style="color: var(--orange-500);" id="checkoutTotal">$0</span>
            </div>

            <div style="display: flex; gap: 0.5rem;">
              <button type="button" onclick="closeCheckout()" class="btn btn-secondary" style="flex: 1;">Volver</button>
              <button type="submit" class="btn btn-primary" style="flex: 1;">Confirmar pedido</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Confirmación -->
    <div id="confirmationModal" class="hidden">
      <div class="modal-overlay" onclick="if(event.target === this) closeConfirmation()">
        <div class="modal-content" style="max-width: 28rem;">
          <div style="padding: 2rem; text-align: center;">
            <div style="width: 5rem; height: 5rem; background: var(--green-100); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--green-500)" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
            </div>
            
            <h1 class="text-3xl mb-2" style="font-weight: 600;">¡Pedido Confirmado!</h1>
            <p class="text-gray-600 mb-4">Tu pedido ha sido recibido exitosamente</p>

            <div style="background: var(--orange-50); padding: 1.5rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
              <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Número de pedido</p>
              <p class="text-3xl" style="color: var(--orange-500); font-weight: 700;" id="confirmedOrderNumber">#0000</p>
              <p style="font-size: 0.75rem; color: var(--gray-500); margin-top: 0.5rem;" id="confirmedInvoiceNumber">Factura: INV-2025-000</p>
            </div>

            <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1.5rem;">
              Tiempo estimado de entrega: <span style="color: var(--orange-500); font-weight: 500;">30-45 minutos</span>
            </p>

            <button onclick="closeConfirmation()" class="btn btn-primary w-full">Volver al inicio</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

  </body>
  </html>