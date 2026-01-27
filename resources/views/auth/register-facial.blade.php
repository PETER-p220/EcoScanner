@extends('layouts.app')

@section('title', 'Register - EcoScanner Pro')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Register New Account</h3>
                    
                    <div id="errorMessage" class="alert alert-danger" style="display:none;"></div>
                    <div id="successMessage" class="alert alert-success" style="display:none;"></div>

                    <form id="registerForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control" placeholder="+255...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                            Register
                        </button>
                    </form>

                    <p class="text-center mt-3">
                        Already have an account? <a href="{{ route('login') }}">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Registering...';
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('{{ route("register.facial") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            document.getElementById('successMessage').textContent = result.message;
            document.getElementById('successMessage').style.display = 'block';
            document.getElementById('errorMessage').style.display = 'none';
            
            // Redirect to dashboard
            setTimeout(() => {
                window.location.href = result.redirect;
            }, 1000);
        } else {
            document.getElementById('errorMessage').textContent = result.message || 'Registration failed';
            document.getElementById('errorMessage').style.display = 'block';
            document.getElementById('successMessage').style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.textContent = 'Register';
        }
    } catch (error) {
        document.getElementById('errorMessage').textContent = 'Network error. Please try again.';
        document.getElementById('errorMessage').style.display = 'block';
        submitBtn.disabled = false;
        submitBtn.textContent = 'Register';
        console.error('Error:', error);
    }
});
</script>
@endpush