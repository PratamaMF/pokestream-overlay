<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realtime Queue - {{  Auth::user()->name }}</title>
    @if(Auth::user()->logo)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . Auth::user()->logo) }}" />
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        :root {
            --primary-glow: #ffc107;
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
            border-collapse: separate;
            border-spacing: 0 10px;
            color: inherit;
        }

        .queue-row {
            background: var(--row-bg-alpha);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .queue-row td {
            padding: 14px 20px;
            vertical-align: middle;
            border-top: 1px solid var(--border-alpha);
            border-bottom: 1px solid var(--border-alpha);
            color: inherit;
        }

        .queue-row td:nth-child(1) {
            width: 85px;
            font-weight: 800;
            color: var(--primary-glow) !important;
            border-left: 1px solid var(--border-alpha);
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }
        
        .queue-row td:nth-child(2) {
            width: 260px;
        }

        .queue-row td:nth-child(4) {
            width: 140px;
            border-right: 1px solid var(--border-alpha);
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .customer-info {
            font-weight: 700;
        }

        .items-detail {
            font-size: 0.85em;
            font-weight: 500;
            opacity: 0.85;
            max-width: 550px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        .time-badge {
            font-size: 0.75em;
            font-weight: 600;
            opacity: 0.7;
            text-align: right;
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
            <h6 class="fw-bold m-0"><i class="fas fa-sliders-h me-1 text-warning"></i> Overlay Settings</h6>
            <i class="fas fa-eye text-muted" style="font-size: 10px;"></i>
        </div>
        <div class="row g-2 mb-2">
            <div class="col-6">
                <label class="form-label m-0">Font Size (px)</label>
                <input type="number" id="fontSizeInput" class="form-control form-control-sm" value="22" oninput="changeFontSize(this.value)">
            </div>
            <div class="col-6">
                <label class="form-label m-0">Max Rows</label>
                <input type="number" id="maxRowsInput" class="form-control form-control-sm" value="5" min="1" max="50" oninput="changeMaxRows(this.value)">
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
        <h4 class="stream-title mb-2">
            <i class="fas fa-circle text-danger me-2 small animate-pulse" style="font-size: 12px; vertical-align: middle;"></i>Live Queue
        </h4>
        
        <table class="poke-stream-table">
            <tbody id="queueFullList">
                <tr id="initialLoadingRow">
                    <td colspan="4" class="text-center py-5 opacity-50 small">
                        <div class="spinner-border spinner-border-sm text-warning me-2" role="status"></div>
                        Synchronizing queue records...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @vite(['resources/js/app.js'])
    
    <script type="text/javascript">
        let currentQueueData = [];
        let maxDisplayRows = 5;

        document.addEventListener('DOMContentLoaded', () => {
            if(localStorage.getItem('poke_fs')) changeFontSize(localStorage.getItem('poke_fs'));
            if(localStorage.getItem('poke_fc')) changeFontColor(localStorage.getItem('poke_fc'));
            if(localStorage.getItem('poke_bg')) changeBgColor(localStorage.getItem('poke_bg'));
            
            if(localStorage.getItem('poke_mx')) {
                maxDisplayRows = parseInt(localStorage.getItem('poke_mx'));
                document.getElementById('maxRowsInput').value = maxDisplayRows;
            }

            fetchInitialQueue();

            if (window.Echo) {
                window.Echo.channel('poke-stream-channel')
                    .listen('.stream.updated', (e) => {
                        currentQueueData = e.queue;
                        renderQueueTable();
                    });
            }
        });

        function fetchInitialQueue() {
            axios.get('/api/live-stream-data-snapshot')
                .then(res => {
                    currentQueueData = res.data.queue;
                    renderQueueTable();
                })
                .catch(err => {
                    console.error("Gagal sinkronisasi data:", err);
                    document.getElementById('queueFullList').innerHTML = 
                        `<tr><td colspan="4" class="text-center text-danger small py-4"><i class="fas fa-times-circle me-1"></i> Gagal memuat log antrean dari database.</td></tr>`;
                });
        }

        function renderQueueTable() {
            const tbody = document.getElementById('queueFullList');
            
            if(!currentQueueData || currentQueueData.length === 0) {
                tbody.innerHTML = `
                    <tr class="animate-fade-in">
                        <td colspan="4" class="text-center py-5 opacity-50 italic" style="font-size: 0.8em;">
                            <i class="fas fa-clipboard-check me-1"></i> All orders cleared. Nothing in queue.
                        </td>
                    </tr>`;
                return;
            }

            const filteredData = currentQueueData.slice(0, maxDisplayRows);
            
            tbody.innerHTML = filteredData.map(q => `
                <tr class="queue-row animate-fade-in">
                    <td>#${q.no}</td>
                    <td><div class="customer-info text-truncate">${q.customer_name}</div></td>
                    <td><div class="time-badge"><i class="far fa-clock me-1"></i>${q.time}</div></td>
                </tr>
            `).join('');
        }

        function changeFontSize(val) {
            document.getElementById('displayArea').style.fontSize = val + 'px';
            document.getElementById('fontSizeInput').value = val;
            localStorage.setItem('poke_fs', val);
        }

        function changeFontColor(val) {
            document.getElementById('displayArea').style.color = val;
            document.getElementById('fontColorInput').value = val;
            localStorage.setItem('poke_fc', val);
        }

        function changeMaxRows(val) {
            let num = parseInt(val);
            if(isNaN(num) || num < 1) num = 5;
            maxDisplayRows = num;
            localStorage.setItem('poke_mx', num);
            renderQueueTable();
        }

        function changeBgColor(val) {
            document.body.style.backgroundColor = val;
            document.getElementById('bgColorInput').value = val;
            localStorage.setItem('poke_bg', val);
            
            if(val.toLowerCase() === '#00ff00') {
                document.documentElement.style.setProperty('--row-bg-alpha', 'rgba(0, 0, 0, 0.9)');
                document.documentElement.style.setProperty('--border-alpha', '#000000');
            } else {
                document.documentElement.style.setProperty('--row-bg-alpha', 'rgba(255, 255, 255, 0.04)');
                document.documentElement.style.setProperty('--border-alpha', 'rgba(255, 255, 255, 0.08)');
            }
        }
    </script>
</body>
</html>