<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Error - {{ \App\Models\User::first()->name }}</title>
    
    @if(($store = \App\Models\User::first()) && $store->logo)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $store->logo) }}" />
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <style>
      :root {
        --primary-color: #162d4d;
        --info-color: #0ea5e9;
        --bg-light: #f4f7fa;
      }
      body {
        font-family: "Plus Jakarta Sans", sans-serif;
        background-color: var(--bg-light);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        overflow: hidden;
      }
      .error-container {
        text-align: center;
        padding: 20px;
        max-width: 500px;
        z-index: 10;
      }
      .error-code {
        font-size: 10rem;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1;
        margin-bottom: 0;
        opacity: 0.06;
        position: absolute;
        left: 50%;
        top: 45%;
        transform: translate(-50%, -50%);
        z-index: 1;
        user-select: none;
      }
      .btn-home {
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 14px;
        padding: 12px 30px;
        font-weight: 700;
        transition: 0.3s;
      }
      .btn-home:hover {
        background-color: #0f1f35;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(14, 165, 233, 0.2);
        color: white;
      }
    </style>
  </head>
  <body>
    <div class="error-code">{{ method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : '500' }}</div>
    
    <div class="error-container">
      <h2 class="fw-800 text-dark mb-3">Oops! You're Lost.</h2>
      <p class="text-muted mb-4 px-lg-5">
        The page you are looking for might have been removed, had its name
        changed, or is temporarily unavailable.
      </p>
      <a href="{{ Auth::check() ? url('/') : route('login') }}" class="btn btn-home text-decoration-none">
        <i class="fas fa-arrow-left me-2"></i> 
        {{ Auth::check() ? 'Back to Dashboard' : 'Back to Login' }}
    </a>
    </div>
  </body>
</html>