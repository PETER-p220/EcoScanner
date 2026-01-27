@extends('layouts.app')

@section('title', 'Login - EcoScanner Pro')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Login with Face Recognition</h3>
                    
                    <div class="alert alert-info">
                        <strong>Note:</strong> Facial recognition login will be available after you register.
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>
                    </form>

                    <p class="text-center mt-3">
                        Don't have an account? <a href="{{ route('register') }}">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection