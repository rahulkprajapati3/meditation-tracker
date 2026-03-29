@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4 fw-bold text-secondary">Create an Account</h3>

                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" onsubmit="return validateRegister()">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Please enter full name">
                        <small id="name_err" class="text-danger d-none">Name is required.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Email Address</label>
                        <input type="email" id="reg_email" name="email" class="form-control" placeholder="Please enter email">
                        <small id="reg_email_err" class="text-danger d-none">Valid email is required.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Password</label>
                        <input type="password" id="reg_password" name="password" class="form-control" placeholder="Please enter password">
                        <small id="reg_password_err" class="text-danger d-none">Password must be at least 8 characters.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Confirm Password</label>
                        <input type="password" id="reg_confirm" name="password_confirmation" class="form-control" placeholder="Please confirm password">
                        <small id="reg_confirm_err" class="text-danger d-none">Passwords do not match.</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">Register</button>
                </form>
                
                <div class="mt-4 text-center">
                    <small class="text-muted">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Login here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    function validateRegister() {
        let isValid = true;
        let name = document.getElementById('name').value;
        let email = document.getElementById('reg_email').value;
        let password = document.getElementById('reg_password').value;
        let confirmPass = document.getElementById('reg_confirm').value;

        if(name.trim() === "") {
            document.getElementById('name_err').classList.remove('d-none');
            isValid = false;
        }

        // Email Regex Validation
        if(email.trim() === "" || !emailPattern.test(email)) {
            document.getElementById('reg_email_err').innerText = "Please enter a valid email address.";
            document.getElementById('reg_email_err').classList.remove('d-none');
            isValid = false;
        }

        if(password.length < 8) {
            document.getElementById('reg_password_err').classList.remove('d-none');
            isValid = false;
        }

        if(password !== confirmPass || confirmPass === "") {
            document.getElementById('reg_confirm_err').classList.remove('d-none');
            isValid = false;
        }

        return isValid;
    }

    // --- validation error remove ---
    document.getElementById('name').addEventListener('input', function() {
        if(this.value.trim() !== "") {
            document.getElementById('name_err').classList.add('d-none');
        }
    });

    document.getElementById('reg_email').addEventListener('input', function() {
        if(emailPattern.test(this.value)) {
            document.getElementById('reg_email_err').classList.add('d-none');
        } else {
            document.getElementById('reg_email_err').innerText = "Keep typing a valid email...";
            document.getElementById('reg_email_err').classList.remove('d-none');
        }
    });

    document.getElementById('reg_password').addEventListener('input', function() {
        if(this.value.length >= 8) {
            document.getElementById('reg_password_err').classList.add('d-none');
        }
        let confirmPass = document.getElementById('reg_confirm').value;
        if(confirmPass !== "" && this.value === confirmPass) {
            document.getElementById('reg_confirm_err').classList.add('d-none');
        }
    });

    document.getElementById('reg_confirm').addEventListener('input', function() {
        let password = document.getElementById('reg_password').value;
        if(this.value === password && this.value !== "") {
            document.getElementById('reg_confirm_err').classList.add('d-none');
        }
    });
</script>
@endsection