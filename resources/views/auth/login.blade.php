@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4 fw-bold text-secondary">Welcome Back</h3>

                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" onsubmit="return validateLogin()">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Please enter email">
                        <small id="email_err" class="text-danger d-none">Please enter a valid email address.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Please enter password">
                        <small id="password_err" class="text-danger d-none">Password field cannot be empty.</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Login</button>
                </form>
                
                <div class="mt-4 text-center">
                    <small class="text-muted">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    function validateLogin() {
        let isValid = true;
        let email = document.getElementById('email').value;
        let password = document.getElementById('password').value;

        if(email.trim() === "" || !emailPattern.test(email)) {
            document.getElementById('email_err').innerText = "Please enter a valid email address (e.g., name@example.com).";
            document.getElementById('email_err').classList.remove('d-none');
            isValid = false;
        }

        if(password === "") {
            document.getElementById('password_err').classList.remove('d-none');
            isValid = false;
        }

        return isValid; 
    }

    // --- REAL-TIME ERROR REMOVAL LOGIC ---
    document.getElementById('email').addEventListener('input', function() {
        if(emailPattern.test(this.value)) {
            document.getElementById('email_err').classList.add('d-none');
        } else {
            document.getElementById('email_err').innerText = "Keep typing a valid email...";
            document.getElementById('email_err').classList.remove('d-none');
        }
    });

    document.getElementById('password').addEventListener('input', function() {
        if(this.value !== "") {
            document.getElementById('password_err').classList.add('d-none');
        }
    });
</script>
@endsection