<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note Overlay - {{  Auth::user()->name }}</title>
    @if(Auth::user()->logo)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . Auth::user()->logo) }}" />
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background 0.3s, color 0.3s; 
            background-color: #0b0f19; 
            color: #ffffff; 
            font-size: 24px;
            overflow: hidden;
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
            width: 240px;
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

        /* CARD DISPLAY MINIMALIS */
        .display-container { 
            padding: 25px; 
            max-width: 800px;
            margin: 40px auto;
        }

        .note-header-title {
            font-size: 0.65em;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #ffc107;
            font-weight: 800;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
        }

        .note-body-content {
            font-weight: 500;
            line-height: 1.5;
            white-space: pre-wrap;
            color: inherit;
        }

        .animate-fade-in { 
            animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1); 
        }
        @keyframes fadeIn { 
            from { opacity: 0; transform: translateY(6px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
    </style>
</head>
<body>

    <div class="config-panel" id="configPanel" style="opacity: 0.15;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.15">
        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary">
            <h6 class="fw-bold m-0"><i class="fas fa-sliders-h me-1 text-warning"></i> Note Settings</h6>
            <i class="fas fa-eye text-muted" style="font-size: 10px;"></i>
        </div>
        <div class="mb-2">
            <label class="form-label m-0">Font Size (px)</label>
            <input type="number" id="fontSizeInput" class="form-control form-control-sm" value="24" oninput="changeFontSize(this.value)">
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
        <div class="note-header-title animate-fade-in" id="noteTitleArea">
            <i class="fas fa-thumbtack me-2" style="font-size: 10px;"></i>{{ $note->title }}
        </div>
        <div class="note-body-content animate-fade-in" id="noteDescArea">{{ $note->description }}</div>
    </div>

    @vite(['resources/js/app.js'])
    
    <script type="text/javascript">
        const noteId = "{{ $note->id }}";

        document.addEventListener('DOMContentLoaded', () => {
            if(localStorage.getItem('poke_note_fs')) changeFontSize(localStorage.getItem('poke_note_fs'));
            if(localStorage.getItem('poke_note_fc')) changeFontColor(localStorage.getItem('poke_note_fc'));
            if(localStorage.getItem('poke_note_bg')) changeBgColor(localStorage.getItem('poke_note_bg'));

            if (window.Echo) {
                window.Echo.channel('poke-stream-channel')
                    .listen('.stream.updated', (e) => {
                        axios.get('/api/live-stream-data-snapshot').then(res => {
                            location.reload();
                        });
                    });
            }
        });

        function changeFontSize(val) {
            document.getElementById('displayArea').style.fontSize = val + 'px';
            document.getElementById('fontSizeInput').value = val;
            localStorage.setItem('poke_note_fs', val);
        }

        function changeFontColor(val) {
            document.getElementById('displayArea').style.color = val;
            document.getElementById('fontColorInput').value = val;
            localStorage.setItem('poke_note_fc', val);
        }

        function changeBgColor(val) {
            document.body.style.backgroundColor = val;
            document.getElementById('bgColorInput').value = val;
            localStorage.setItem('poke_note_bg', val);
        }
    </script>
</body>
</html>