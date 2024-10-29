<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management System</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-login-style />
</head>
<body style="background-image: url('images/i-am.jpg'); background-size: cover; background-position: center;">
    <div class="container">
       
        <h3>Welcome to Inventory Management System</h3>
        <br><br>
        <div class="title">Login</div>
        <div class="content">
            <form method="POST" action="{{ route('login') }}" style="background-color: transparent;">
                @csrf
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Username</span>
                        <input type="text" placeholder="Enter your username" name="username" value="{{ old('username') }}" required>
                        
                    </div>
                    <div class="input-box">
                        <span class="details">Password</span>
                        <input type="password" placeholder="Enter your password" name= "password" value="{{ old('password') }}" required>
                    </div>
                </div>

                @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li style="color:red; list-style:none; margin-bottom:10px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
                <!-- <div class="mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div> -->
                <div class="button">
                    <input type="submit" href = "dashboard.php" value="Login">
                </div>
            </form>
            <!-- <div class="signup">
                <span class="signup">Not yet registered?
                    <a href="{{ route('register') }}">Register</a>
                </span>
            </div> -->
        </div>
    </div>
</body>
</html>
