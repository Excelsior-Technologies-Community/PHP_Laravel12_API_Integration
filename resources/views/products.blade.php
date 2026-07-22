<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f8fafc; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; }
        .btn { padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-success { background: #10b981; color: white; }
        .btn-success:hover { background: #059669; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }
        .btn-secondary { background: #f3f4f6; color: #374151; }
        .btn-secondary:hover { background: #e5e7eb; }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; }
        input, select, textarea { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; margin-bottom: 16px; font-size: 14px; transition: all 0.2s; background: white; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #374151; }
        .form-group { margin-bottom: 20px; }
        .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e5e7eb; }
        .badge { padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500; display: inline-block; }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .product-image { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; background: #f3f4f6; border: 1px solid #e5e7eb; }
        .empty-state { text-align: center; padding: 80px 20px; color: #9ca3af; }
        .feature-tag { background: #eff6ff; color: #1d4ed8; padding: 3px 10px; border-radius: 6px; font-size: 11px; margin: 2px; display: inline-block; font-weight: 500; }
        .modal-content { background: white; border-radius: 16px; width: 100%; max-width: 700px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px rgba(0,0,0,0.25); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 640px) { .form-grid { grid-template-columns: 1fr; } }
        .loading-spinner { display: inline-block; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-radius: 50%; border-top-color: white; animation: spin 1s ease-in-out infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .toast { position: fixed; bottom: 20px; right: 20px; padding: 12px 20px; border-radius: 8px; color: white; font-size: 14px; z-index: 100; transform: translateY(100px); transition: transform 0.3s; }
        .toast.show { transform: translateY(0); }
        .toast-success { background: #10b981; }
        .toast-error { background: #ef4444; }
        .navbar { background: white; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; z-index: 40; }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900">ProductHub</span>
                </div>
                <a href="/" class="text-gray-500 hover:text-gray-700 font-medium text-sm">Home</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-1">Products</h1>
                <p class="text-gray-500">Manage your product catalog and inventory</p>
            </div>
            <button onclick="openModal()" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Product
            </button>
        </div>

        <div class="card p-4 mb-6 flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-[200px] relative">
                <svg class="w-5 h-5 absolute left-3 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="searchInput" placeholder="Search by name, SKU, category..." class="pl-10 mb-0" oninput="searchProducts()">
            </div>
            <select id="categoryFilter" onchange="searchProducts()" class="mb-0 w-48">
                <option value="">All Categories</option>
            </select>
            <select id="statusFilter" onchange="searchProducts()" class="mb-0 w-40">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productsTable"></tbody>
                </table>
            </div>
            <div id="emptyState" class="empty-state hidden">
                <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <p class="text-lg font-semibold text-gray-600">No products found</p>
                <p class="text-sm text-gray-400 mt-1">Add your first product to get started</p>
            </div>
            <div id="pagination" class="px-4 py-3 border-t border-gray-100 flex justify-between items-center bg-gray-50"></div>
        </div>
    </div>

    <div id="productModal" class="modal-overlay">
        <div class="modal-content">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Add Product</h3>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="productForm" class="p-6">
                <input type="hidden" id="productId">
                <div class="form-grid">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Product Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" required placeholder="e.g., iPhone 15 Pro">
                    </div>
                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" id="sku" placeholder="e.g., PROD-001">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" id="category" placeholder="e.g., Electronics">
                    </div>
                    <div class="form-group">
                        <label>Price (Rs.) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" id="price" required placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Discount Price (Rs.)</label>
                        <input type="number" step="0.01" id="discount_price" placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Stock <span class="text-red-500">*</span></label>
                        <input type="number" id="stock" required placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Min Order Qty</label>
                        <input type="number" id="min_order_qty" value="1" min="1">
                    </div>
                    <div class="form-group">
                        <label>Status <span class="text-red-500">*</span></label>
                        <select id="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Image URL</label>
                        <input type="url" id="image" placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Description</label>
                        <textarea id="description" rows="3" placeholder="Product description..."></textarea>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Features</label>
                        <textarea id="features" rows="2" placeholder="Enter features separated by commas"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Separate each feature with a comma</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-2 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" id="submitBtn" class="btn btn-success">
                        <span id="submitText">Save Product</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="viewModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 800px;">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Product Details</h3>
                <button onclick="closeViewModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="viewModalContent" class="p-6"></div>
        </div>
    </div>

    <div id="toast" class="toast"></div>

    <script>
        const API_BASE = '/api/products';
        let currentPage = 1;
        let allProducts = [];

        document.addEventListener('DOMContentLoaded', function() {
            fetchCategories();
            fetchProducts();
        });

        function showToast(message, type) {
            type = type || 'success';
            var toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast toast-' + type + ' show';
            setTimeout(function() { toast.classList.remove('show'); }, 3000);
        }

        function setLoading(btnId, textId, isLoading) {
            var btn = document.getElementById(btnId);
            var text = document.getElementById(textId);
            if (isLoading) {
                btn.disabled = true;
                text.innerHTML = '<span class="loading-spinner mr-2"></span>Saving...';
            } else {
                btn.disabled = false;
                text.textContent = 'Save Product';
            }
        }

        async function apiCall(url, options) {
            options = options || {};
            var res = await fetch(url, {
                ...options,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    ...options.headers
                }
            });
            var data = await res.json().catch(function() { return {}; });
            if (!res.ok) throw new Error(data.message || 'Request failed');
            return data;
        }

        async function fetchCategories() {
            try {
                var categories = await apiCall(API_BASE + '/categories');
                var select = document.getElementById('categoryFilter');
                categories.forEach(function(cat) {
                    var option = document.createElement('option');
                    option.value = cat;
                    option.textContent = cat;
                    select.appendChild(option);
                });
            } catch (e) { console.error('Failed to load categories', e); }
        }

        function buildUrl() {
            var search = document.getElementById('searchInput').value;
            var category = document.getElementById('categoryFilter').value;
            var status = document.getElementById('statusFilter').value;
            var url = API_BASE + '?page=' + currentPage;
            if (search) url += '&search=' + encodeURIComponent(search);
            if (category) url += '&category=' + encodeURIComponent(category);
            if (status) url += '&status=' + encodeURIComponent(status);
            return url;
        }

        async function fetchProducts() {
            try {
                var data = await apiCall(buildUrl());
                allProducts = data.data || [];
                renderProducts(allProducts);
                renderPagination(data);
            } catch (e) { showToast(e.message, 'error'); }
        }

        function renderProducts(products) {
            var tbody = document.getElementById('productsTable');
            var emptyState = document.getElementById('emptyState');
            if (products.length === 0) {
                tbody.innerHTML = '';
                emptyState.classList.remove('hidden');
                document.getElementById('pagination').innerHTML = '';
                return;
            }
            emptyState.classList.add('hidden');
            tbody.innerHTML = products.map(function(p) {
                var features = p.features ? (Array.isArray(p.features) ? p.features : String(p.features).split(',')) : [];
                var featuresHtml = features.slice(0, 2).map(function(f) { return '<span class="feature-tag">' + escapeHtml(f.trim()) + '</span>'; }).join('');
                var imgHtml = p.image ? '<img src="' + escapeHtml(p.image) + '" class="product-image mr-3" onerror="this.style.display=\'none\'">' : '<div class="product-image mr-3 flex items-center justify-center text-gray-400 text-xs font-medium">IMG</div>';
                return '<tr class="hover:bg-gray-50 transition-colors">' +
                    '<td><div class="flex items-center">' + imgHtml + '<div><div class="font-semibold text-gray-900">' + escapeHtml(p.name) + '</div><div class="text-sm text-gray-500 mt-0.5 max-w-xs truncate">' + escapeHtml(p.description || 'No description') + '</div>' + (featuresHtml ? '<div class="flex flex-wrap gap-1 mt-1">' + featuresHtml + '</div>' : '') + '</div></div></td>' +
                    '<td class="font-mono text-sm text-gray-600">' + escapeHtml(p.sku || '-') + '</td>' +
                    '<td><span class="badge bg-gray-100 text-gray-700">' + escapeHtml(p.category || 'N/A') + '</span></td>' +
                    '<td><div class="font-semibold text-gray-900">Rs. ' + parseFloat(p.price).toLocaleString() + '</div>' + (p.discount_price ? '<div class="text-sm text-red-600 font-medium">Rs. ' + parseFloat(p.discount_price).toLocaleString() + '</div>' : '') + '</td>' +
                    '<td><span class="font-semibold ' + (p.stock > 0 ? 'text-green-600' : 'text-red-600') + '">' + p.stock + ' units</span></td>' +
                    '<td><span class="badge ' + (p.status === 'active' ? 'badge-active' : 'badge-inactive') + '">' + p.status + '</span></td>' +
                    '<td class="text-right">' +
                        '<button onclick="viewProductById(' + p.id + ')" class="btn btn-secondary btn-sm mr-1" title="View"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>' +
                        '<button onclick="editProductById(' + p.id + ')" class="btn btn-primary btn-sm mr-1" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>' +
                        '<button onclick="deleteProduct(' + p.id + ')" class="btn btn-danger btn-sm" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>' +
                    '</td>' +
                '</tr>';
            }).join('');
        }

        function renderPagination(data) {
            var container = document.getElementById('pagination');
            if (data.last_page <= 1) { container.innerHTML = ''; return; }
            var html = '<span class="text-sm text-gray-600">Page ' + data.current_page + ' of ' + data.last_page + '</span><div class="flex gap-2">';
            if (data.prev_page_url) html += '<button onclick="changePage(' + (data.current_page - 1) + ')" class="btn btn-secondary btn-sm">Previous</button>';
            if (data.next_page_url) html += '<button onclick="changePage(' + (data.current_page + 1) + ')" class="btn btn-secondary btn-sm">Next</button>';
            html += '</div>';
            container.innerHTML = html;
        }

        function changePage(page) { currentPage = page; fetchProducts(); }
        function searchProducts() { currentPage = 1; fetchProducts(); }

        function openModal(product) {
            product = product || null;
            document.getElementById('productForm').reset();
            document.getElementById('productId').value = '';
            document.getElementById('modalTitle').textContent = 'Add Product';
            document.getElementById('submitText').textContent = 'Save Product';
            document.getElementById('productModal').classList.add('open');
            if (product) {
                document.getElementById('modalTitle').textContent = 'Edit Product';
                document.getElementById('submitText').textContent = 'Update Product';
                document.getElementById('productId').value = product.id;
                document.getElementById('name').value = product.name || '';
                document.getElementById('sku').value = product.sku || '';
                document.getElementById('category').value = product.category || '';
                document.getElementById('price').value = product.price || '';
                document.getElementById('discount_price').value = product.discount_price || '';
                document.getElementById('stock').value = product.stock || '';
                document.getElementById('min_order_qty').value = product.min_order_qty || 1;
                document.getElementById('status').value = product.status || 'active';
                document.getElementById('image').value = product.image || '';
                document.getElementById('description').value = product.description || '';
                document.getElementById('features').value = Array.isArray(product.features) ? product.features.join(', ') : (product.features || '');
            }
        }

        function closeModal() { document.getElementById('productModal').classList.remove('open'); }
        function closeViewModal() { document.getElementById('viewModal').classList.remove('open'); }

        function viewProductById(id) {
            apiCall(API_BASE + '/' + id).then(function(p) {
                var features = p.features ? (Array.isArray(p.features) ? p.features : String(p.features).split(',')) : [];
                var featuresHtml = features.map(function(f) { return '<span class="feature-tag">' + escapeHtml(f.trim()) + '</span>'; }).join('');
                document.getElementById('viewModalContent').innerHTML =
                    '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">' +
                        '<div>' +
                            (p.image ? '<img src="' + escapeHtml(p.image) + '" class="w-full h-64 object-cover rounded-xl mb-4 border border-gray-200" onerror="this.style.display=\'none\'">' : '<div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center text-gray-400 font-medium">No Image Available</div>') +
                            '<div class="flex flex-wrap gap-2 mt-4">' + (featuresHtml || '<span class="text-gray-400 text-sm">No features listed</span>') + '</div>' +
                        '</div>' +
                        '<div>' +
                            '<h2 class="text-2xl font-bold text-gray-900 mb-2">' + escapeHtml(p.name) + '</h2>' +
                            '<div class="flex items-center gap-2 mb-4">' +
                                '<span class="badge ' + (p.status === 'active' ? 'badge-active' : 'badge-inactive') + '">' + p.status + '</span>' +
                                (p.sku ? '<span class="text-sm text-gray-500 font-mono bg-gray-100 px-2 py-1 rounded">SKU: ' + escapeHtml(p.sku) + '</span>' : '') +
                            '</div>' +
                            '<div class="space-y-3">' +
                                '<div class="flex justify-between py-3 border-b"><span class="text-gray-600 font-medium">Price</span><span class="font-bold text-xl text-gray-900">Rs. ' + parseFloat(p.price).toLocaleString() + '</span></div>' +
                                (p.discount_price ? '<div class="flex justify-between py-3 border-b"><span class="text-gray-600 font-medium">Discount Price</span><span class="font-bold text-lg text-red-600">Rs. ' + parseFloat(p.discount_price).toLocaleString() + '</span></div>' : '') +
                                '<div class="flex justify-between py-3 border-b"><span class="text-gray-600 font-medium">Stock</span><span class="font-bold ' + (p.stock > 0 ? 'text-green-600' : 'text-red-600') + '">' + p.stock + ' units</span></div>' +
                                '<div class="flex justify-between py-3 border-b"><span class="text-gray-600 font-medium">Min Order Qty</span><span class="font-medium text-gray-900">' + (p.min_order_qty || 1) + '</span></div>' +
                                '<div class="flex justify-between py-3 border-b"><span class="text-gray-600 font-medium">Category</span><span class="badge bg-gray-100 text-gray-700">' + escapeHtml(p.category || 'N/A') + '</span></div>' +
                            '</div>' +
                            (p.description ? '<div class="mt-6 p-4 bg-gray-50 rounded-lg"><h4 class="font-semibold mb-2 text-gray-900">Description</h4><p class="text-gray-600 text-sm leading-relaxed">' + escapeHtml(p.description) + '</p></div>' : '') +
                        '</div>' +
                    '</div>';
                document.getElementById('viewModal').classList.add('open');
            }).catch(function(e) { showToast(e.message, 'error'); });
        }

        function editProductById(id) {
            apiCall(API_BASE + '/' + id).then(function(p) {
                openModal(p);
            }).catch(function(e) { showToast(e.message, 'error'); });
        }

        function editProduct(product) { openModal(product); }

        function deleteProduct(id) {
            if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) return;
            apiCall(API_BASE + '/' + id, { method: 'DELETE' }).then(function() {
                showToast('Product deleted successfully');
                fetchProducts();
            }).catch(function(e) { showToast(e.message, 'error'); });
        }

        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var id = document.getElementById('productId').value;
            var featuresText = document.getElementById('features').value;
            var featuresArray = featuresText ? featuresText.split(',').map(function(f) { return f.trim(); }).filter(function(f) { return f; }) : [];
            var data = {
                name: document.getElementById('name').value,
                sku: document.getElementById('sku').value,
                category: document.getElementById('category').value,
                price: document.getElementById('price').value,
                discount_price: document.getElementById('discount_price').value,
                stock: document.getElementById('stock').value,
                min_order_qty: document.getElementById('min_order_qty').value,
                status: document.getElementById('status').value,
                image: document.getElementById('image').value,
                description: document.getElementById('description').value,
                features: featuresArray,
            };
            setLoading('submitBtn', 'submitText', true);
            var promise = id ? apiCall(API_BASE + '/' + id, { method: 'PUT', body: JSON.stringify(data) }) : apiCall(API_BASE, { method: 'POST', body: JSON.stringify(data) });
            promise.then(function() {
                showToast(id ? 'Product updated successfully' : 'Product created successfully');
                closeModal();
                fetchProducts();
            }).catch(function(err) {
                showToast(err.message, 'error');
            }).finally(function() {
                setLoading('submitBtn', 'submitText', false);
            });
        });

        function escapeHtml(text) {
            if (!text) return '';
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) overlay.classList.remove('open');
            });
        });
    </script>
</body>
</html>
