<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realtime Catalog - {{ Auth::user()->name }}</title>
    @if(Auth::user()->logo)
        <link class="icon" type="image/png" href="{{ asset('storage/' . Auth::user()->logo) }}" />
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        :root {
            --primary-glow: #10b981;
            --row-bg-alpha: rgba(255, 255, 255, 0.04);
            --border-alpha: rgba(255, 255, 255, 0.08);
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background 0.3s, color 0.3s; 
            background-color: #0b0f19; 
            color: #ffffff; 
            font-size: 22px;
            overflow-x: hidden;
        }

        .config-panel { 
            position: fixed; 
            bottom: 20px; 
            left: 20px; 
            background: rgba(15, 23, 42, 0.96); 
            backdrop-filter: blur(12px);
            padding: 18px; 
            border-radius: 12px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.6); 
            z-index: 9999; 
            font-size: 12px; 
            color: #f1f5f9; 
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.3s ease;
            width: 250px;
        }
        .config-panel .form-label {
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 3px;
        }
        .config-panel .form-control {
            background-color: #1e293b;
            border: 1px solid #334155;
            color: #fff;
            font-size: 12px;
        }
        .config-panel .form-control:focus {
            background-color: #1e293b;
            color: #fff;
            border-color: var(--primary-glow);
            box-shadow: none;
        }

        .display-container { 
            padding: 30px; 
            max-width: 1300px;
            margin: 0 auto;
        }
        
        .stream-title {
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: inherit;
            display: inline-flex;
            align-items: center;
            opacity: 0.9;
        }

        .poke-stream-table {
            width: 100%;
            border-collapse: collapse;
            color: inherit;
        }

        .category-divider-row td {
            padding: 20px 0px 6px 0px !important;
            font-size: 0.65em;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748b !important;
            border: none !important;
            background: transparent !important;
        }

        .product-row {
            background: transparent;
            transition: all 0.2s ease;
        }

        .product-row td {
            padding: 6px 0px;
            vertical-align: middle;
            border: none;
            color: inherit; 
        }

        .product-row td:nth-child(1) {
            font-weight: 500;
            text-align: left;
        }
        
        .product-row td:nth-child(2) {
            font-weight: 700;
            text-align: right;
            opacity: 0.95;
        }

        .stock-label {
            opacity: 0.7;
            font-size: 0.9em;
            margin-left: 8px;
            font-weight: 400;
        }

        .animate-fade-in { 
            animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1); 
        }
        @keyframes fadeIn { 
            from { opacity: 0; transform: translateY(8px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
    </style>
</head>
<body>

    <div class="config-panel" id="configPanel" style="opacity: 0.15;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.15">
        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary">
            <h6 class="fw-bold m-0"><i class="fas fa-sliders-h me-1 text-success"></i> Overlay Settings</h6>
            <i class="fas fa-eye text-muted" style="font-size: 10px;"></i>
        </div>
        <div class="row g-2 mb-2">
            <div class="col-6">
                <label class="form-label m-0">Font Size (px)</label>
                <input type="number" id="fontSizeInput" class="form-control form-control-sm" value="22" oninput="changeFontSize(this.value)">
            </div>
            <div class="col-6">
                <label class="form-label m-0">Max Items</label>
                <input type="number" id="maxRowsInput" class="form-control form-control-sm" value="10" min="1" max="100" oninput="changeMaxRows(this.value)">
            </div>
        </div>
        <div class="mb-2">
            <label class="form-label m-0">Font Color</label>
            <input type="color" id="fontColorInput" class="form-control form-control-sm form-control-color w-100" value="#ffffff" oninput="changeFontColor(this.value)">
        </div>
        <div class="mb-3">
            <label class="form-label m-0">Background Color</label>
            <input type="color" id="bgColorInput" class="form-control form-control-sm form-control-color w-100" value="#0b0f19" oninput="changeBgColor(this.value)">
        </div>
        <button class="btn btn-sm btn-danger w-100 fw-bold" style="font-size:10px;" onclick="document.getElementById('configPanel').remove()">
            <i class="fas fa-eye-slash me-1"></i> Hide Overlay Config
        </button>
    </div>

    <div class="display-container" id="displayArea">
        <h4 class="stream-title">
            <i class="fas fa-box-open text-success me-2 small animate-pulse" style="font-size: 14px; vertical-align: middle;"></i>Live Catalog
        </h4>
        
        <table class="poke-stream-table">
            <tbody id="productFullList">
                <tr id="initialLoadingRow">
                    <td colspan="2" class="text-center py-5 opacity-50 small">
                        <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
                        Synchronizing product records...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @vite(['resources/js/app.js'])
    
    <script type="text/javascript">
        let currentProductData = [];
        let maxDisplayRows = 10;

        document.addEventListener('DOMContentLoaded', () => {
            if(localStorage.getItem('poke_prod_fs')) changeFontSize(localStorage.getItem('poke_prod_fs'));
            if(localStorage.getItem('poke_prod_fc')) changeFontColor(localStorage.getItem('poke_prod_fc'));
            if(localStorage.getItem('poke_prod_bg')) changeBgColor(localStorage.getItem('poke_prod_bg'));
            
            if(localStorage.getItem('poke_prod_mx')) {
                maxDisplayRows = parseInt(localStorage.getItem('poke_prod_mx'));
                document.getElementById('maxRowsInput').value = maxDisplayRows;
            }

            fetchInitialProducts();

            if (window.Echo) {
                window.Echo.channel('poke-stream-channel')
                    .listen('.stream.updated', (e) => {
                        currentProductData = e.products; 
                        renderProductTable();
                    });
            }
        });

        function fetchInitialProducts() {
            axios.get('/api/live-stream-data-snapshot')
                .then(res => {
                    // Pastikan struktur respon data sesuai (res.data.products)
                    currentProductData = res.data.products || [];
                    renderProductTable();
                })
                .catch(err => {
                    console.error("Failed to fetch initial product data:", err);
                    document.getElementById('productFullList').innerHTML = 
                        `<tr><td colspan="2" class="text-center text-danger small py-4"><i class="fas fa-times-circle me-1"></i> Failed to fetch initial product data. Status: ${err.response ? err.response.status : 'Network Error'}</td></tr>`;
                });
        }

        function renderProductTable() {
            const tbody = document.getElementById('productFullList');
            
            if(!currentProductData || currentProductData.length === 0) {
                tbody.innerHTML = `
                    <tr class="animate-fade-in">
                        <td colspan="2" class="text-center py-5 opacity-50 italic" style="font-size: 0.8em;">
                            <i class="fas fa-cubes me-1"></i> Out of Stock / No items registered.
                        </td>
                    </tr>`;
                return;
            }

            const filteredData = currentProductData.slice(0, maxDisplayRows);
            
            let htmlContent = '';
            let lastCategory = '';

            filteredData.forEach(p => {
                if (p.category !== lastCategory) {
                    lastCategory = p.category;
                    htmlContent += `
                        <tr class="category-divider-row animate-fade-in">
                            <td colspan="2"> <i class="fas fa-minus me-1 opacity-20"></i> ${p.category}</td>
                        </tr>`;
                }

                // Bersihkan string harga dari karakter non-angka
                let cleanNumber = parseInt(String(p.price).replace(/[^0-9]/g, ''));
                
                // Format menggunakan standard id-ID agar menghasilkan pemisah titik (misal: 2.000.000)
                let normalPrice = !isNaN(cleanNumber) 
                    ? new Intl.NumberFormat('id-ID').format(cleanNumber) 
                    : p.price;

                htmlContent += `
                    <tr class="product-row animate-fade-in">
                        <td>${p.name} <span class="stock-label">(${p.stock} pcs)</span></td>
                        <td>${normalPrice}</td>
                    </tr>`;
            });
            
            tbody.innerHTML = htmlContent;
        }

        function changeFontSize(val) {
            document.getElementById('displayArea').style.fontSize = val + 'px';
            document.getElementById('fontSizeInput').value = val;
            localStorage.setItem('poke_prod_fs', val);
        }

        function changeFontColor(val) {
            document.getElementById('displayArea').style.color = val;
            document.getElementById('fontColorInput').value = val;
            localStorage.setItem('poke_prod_fc', val);
        }

        function changeMaxRows(val) {
            let num = parseInt(val);
            if(isNaN(num) || num < 1) num = 10;
            maxDisplayRows = num;
            localStorage.setItem('poke_prod_mx', num);
            renderProductTable();
        }

        function changeBgColor(val) {
            document.body.style.backgroundColor = val;
            document.getElementById('bgColorInput').value = val;
            localStorage.setItem('poke_prod_bg', val);
        }
    </script>
</body>
</html>