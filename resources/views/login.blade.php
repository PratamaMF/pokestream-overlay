<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - {{ $store->name ?? 'Pokedel Shop' }}</title>
    @if(isset($store) && $store->logo)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $store->logo) }}" />
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        :root {
            --primary-glow: #ffc107;
            --bg-dark: #0b0f19;
            --glass-bg: rgba(15, 23, 42, 0.55);
            --glass-border: rgba(255, 255, 255, 0.08);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            margin: 0;
            padding: 20px;
        }

        .aurora-glow-1 {
            position: absolute;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(255, 193, 7, 0.15) 0%, rgba(0,0,0,0) 70%);
            top: -150px;
            left: -100px;
            z-index: 1;
            pointer-events: none;
        }
        .aurora-glow-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, rgba(0,0,0,0) 70%);
            bottom: -150px;
            right: -100px;
            z-index: 1;
            pointer-events: none;
        }

        .login-wrapper {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            width: 100%;
            max-width: 460px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            z-index: 10;
            position: relative;
        }

        .brand-section {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-logo {
            width: 85px;
            height: 85px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--glass-border);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.02);
            padding: 3px;
        }
        .brand-name {
            font-size: 1.15rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.5px;
            margin-top: 12px;
            text-transform: uppercase;
            opacity: 0.95;
        }

        .welcome-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: #ffffff;
            text-align: center;
        }
        .subtitle-text {
            font-size: 0.85rem;
            color: #94a3b8;
            text-align: center;
            margin-bottom: 24px;
        }

        .form-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .input-group-custom {
            display: flex;
            align-items: center;
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid #1e293b;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.2s ease-in-out;
        }
        .input-group-custom:focus-within {
            border-color: var(--primary-glow);
            box-shadow: 0 0 12px rgba(255, 193, 7, 0.2);
            background-color: rgba(15, 23, 42, 0.8);
        }
        .input-group-custom i {
            color: #475569;
            margin-right: 14px;
            font-size: 0.95rem;
            transition: color 0.2s;
        }
        .input-group-custom input {
            background: transparent;
            border: none;
            color: #ffffff;
            width: 100%;
            font-size: 0.9rem;
            font-weight: 500;
            outline: none;
        }
        .input-group-custom input::placeholder {
            color: #475569;
        }
        .input-group-custom:focus-within i {
            color: var(--primary-glow);
        }

        .btn-modern {
            background: var(--primary-glow);
            color: #0b0f19;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 1px;
            border: none;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.25);
        }
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
            background: #ffa000;
        }
        .btn-modern:active {
            transform: translateY(0);
        }

        .form-check-input {
            background-color: #0f172a;
            border-color: #334155;
        }
        .form-check-input:checked {
            background-color: var(--primary-glow);
            border-color: var(--primary-glow);
        }
        .form-check-label {
            cursor: pointer;
            user-select: none;
        }
    </style>
  </head>
  <body>
    <div class="aurora-glow-1"></div>
    <div class="aurora-glow-2"></div>

    <div class="login-wrapper">
        
        <div class="brand-section">
            @if(isset($store) && $store->logo)
                <img src="{{ asset('storage/' . $store->logo) }}" class="brand-logo" alt="Logo {{ $store->name }}" />
            @else
                <div class="brand-logo d-flex align-items-center justify-content-center mx-auto bg-secondary">
                    <i class="fas fa-store text-white fa-2x"></i>
                </div>
            @endif
            <div class="brand-name">{{ $store->name ?? 'Pokedel Shop' }}</div>
        </div>

        <h3 class="welcome-text mb-1">Login to Account</h3>
        <p class="subtitle-text">Please enter your details to continue.</p>

        @if (session('status'))
            <div class="alert alert-success border-0 small mb-4" style="background: rgba(16, 185, 129, 0.1); color: #10b981;" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label text-uppercase">Username</label>
                <div class="input-group-custom @error('username') border-danger @enderror">
                    <i class="far fa-user"></i>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Input your username" class="text-white" required autofocus />
                </div>
                @error('username')
                    <div class="text-danger small mt-1" style="font-size: 0.75rem;"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label text-uppercase">Password</label>
                <div class="input-group-custom @error('password') border-danger @enderror">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Input your password" required />
                </div>
                @error('password')
                    <div class="text-danger small mt-1" style="font-size: 0.75rem;"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check m-0">
                    <input class="form-check-input shadow-none" type="checkbox" name="remember" id="remember" />
                    <label class="form-check-label small fw-600 text-white" for="remember">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn-modern text-uppercase">
                Sign In <i class="fas fa-arrow-right ms-2" style="font-size: 0.8rem;"></i>
            </button>
        </form>
    </div>
  </body>
</html>