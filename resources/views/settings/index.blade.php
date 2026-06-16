@extends('layout.main')

@section('title', 'Store Settings - ' . Auth::user()->name)
@section('namepage', 'Store Settings')
@section('route', route('settings.index'))
@section('namemenu', 'Form Settings')

@section('content')
<form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-xl-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-circle-info me-2"></i>Profile Overview</h6>
                </div>
                <div class="card-body text-center p-4">
                    <div class="profile-icon-wrapper mb-3 d-flex justify-content-center align-items-center bg-light rounded-circle mx-auto" style="width: 140px; height: 140px; overflow: hidden; border: 3px solid #f8f9fa; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                        <i class="fas fa-user fa-3x text-muted {{ $user->logo ? 'd-none' : '' }}" id="logoPlaceholder"></i>
                        <img src="{{ $user->logo ? asset('storage/' . $user->logo) : '' }}" 
                             alt="Logo Toko" 
                             class="img-fluid w-100 h-100 object-fit-cover {{ $user->logo ? '' : 'd-none' }}" 
                             id="logoPreview">
                    </div>

                    <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-circle-info me-2"></i>Form Profile Details</h6>
                </div>
                <div class="card-body p-4">
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-600">Store Logo / Avatar</label>
                            <input type="file" name="logo" id="uploadLogo" class="form-control @error('logo') is-invalid @enderror" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(this)" />
                            <div class="form-text text-muted small">Recommended: PNG/JPG no larger than 2 MB</div>
                            @error('logo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-600">Store / Owner Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name', $user->name) }}" placeholder="Enter full name" required />
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-600">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">@</span>
                                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                                    value="{{ old('username', $user->username) }}" placeholder="Enter username" required />
                                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-5" />

                    <h6 class="fw-bold mb-3 text-primary">
                        <i class="fas fa-lock me-2"></i>Change Password
                    </h6>
                    <p class="small text-muted mb-3">Leave the password field below blank if you don't want to change your old password.</p>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-600">New Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                placeholder="Input new secret password (min 6 characters)" autocomplete="new-password" />
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="my-4 opacity-5" />

                    <div class="bg-light p-3 rounded mb-3 border-start border-primary border-3">
                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-shield-alt me-2 text-primary"></i>Security Authentication</h6>
                        <p class="small text-muted mb-2">You are required to enter your <strong>current password</strong> if you change your <strong>username</strong> or enter a <strong>new password</strong>.</p>
                        <div>
                            <label class="form-label fw-600 text-danger">Current Password</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" 
                                placeholder="Confirm your active password to authenticate changes" />
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-outline-secondary px-4" onclick="resetPreview()">Reset</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    const originalSrc = document.getElementById('logoPreview').src;
    const hasOriginalLogo = !document.getElementById('logoPreview').classList.contains('d-none');

    function previewImage(input) {
        const file = input.files[0];
        const preview = document.getElementById('logoPreview');
        const placeholder = document.getElementById('logoPlaceholder');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            }
            reader.readAsDataURL(file);
        }
    }

    function resetPreview() {
        const preview = document.getElementById('logoPreview');
        const placeholder = document.getElementById('logoPlaceholder');

        if (hasOriginalLogo) {
            preview.src = originalSrc;
            preview.classList.remove('d-none');
            if (placeholder) placeholder.classList.add('d-none');
        } else {
            preview.src = '';
            preview.classList.add('d-none');
            if (placeholder) placeholder.classList.remove('d-none');
        }
    }
</script>
@endsection