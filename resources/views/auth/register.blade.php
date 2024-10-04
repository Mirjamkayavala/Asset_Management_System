<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management Systems</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-register-style />
</head>
<body>
    <div class="container">
        <div class="title">Registration</div>
        <div class="content">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Role</span>
                        <select name="role_id" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                               
                            @endforeach
                        </select>
                    </div>
                    <div class="input-box">
                        <span class="details">User Name</span>
                        <input type="text" placeholder="Enter your username" name="username" value="{{ old('username') }}" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Full Name</span>
                        <input type="text" placeholder="Enter your name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Email</span>
                        <input type="email" placeholder="Enter your email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Contact Number</span>
                        <input type="text" placeholder="Enter your number" name="contact_number" value="{{ old('contact_number') }}" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Password</span>
                        <input type="password" placeholder="Enter your password" name="password" value="{{ old('password') }}" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Confirm Password</span>
                        <input type="password" placeholder="Confirm your password" name="password_confirmation" value="{{ old('confirm_password') }}" required>
                    </div>
                </div>
                <div class="button">
                    <input type="submit" value="Register">
                </div>
            </form>
            <div class="signup">
                <span class="signup">Already have an account?
                    <a href="{{ route('login') }}">Login</a>
                </span>
            </div>
        </div>
    </div>
    <script>
        function toggleEmploymentFields() {
            var isBusinessOwner = document.getElementById('is_business_owner').value;
            var businessInfo = document.getElementById('business_info');
            var employmentInfo = document.getElementById('employment_info');

            if (isBusinessOwner === '1') {
                businessInfo.style.display = 'block';
                employmentInfo.style.display = 'none';
            } else {
                businessInfo.style.display = 'none';
                employmentInfo.style.display = 'block';
            }
        }
    </script>
</body>
</html>
