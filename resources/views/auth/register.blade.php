<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Buttercloud Bakery</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFB6A3 0%, #FF8E7E 50%, #FF6B9D 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .auth-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .auth-left {
            background: linear-gradient(135deg, rgba(236, 238, 223, 0.9) 0%, rgba(187, 220, 229, 0.9) 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('{{ asset('images/products/pastries-2.jpg') }}') center/cover;
            opacity: 0.3;
            z-index: 0;
        }

        .auth-left-content {
            position: relative;
            z-index: 1;
        }

        .auth-left h1 {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        }

        .auth-left p {
            color: white;
            font-size: 1rem;
            line-height: 1.6;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
            margin-bottom: 1.5rem;
        }

        .auth-left a {
            color: white;
            text-decoration: underline;
            font-weight: 600;
        }

        .auth-right {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-header {
            margin-bottom: 2rem;
        }

        .auth-header h2 {
            color: #2c2c2c;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            color: #555;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s;
            background: #f8fafc;
        }

        .form-group input:focus {
            outline: none;
            border-color: #FFB6A3;
            background: white;
        }

        .input-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1.1rem;
        }

        .btn-register {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #FFB6A3 0%, #FF8E7E 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(255, 182, 163, 0.4);
            margin-top: 0.5rem;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 182, 163, 0.6);
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            color: #999;
            font-size: 0.875rem;
        }

        .social-login {
            text-align: center;
            margin-top: 1rem;
        }

        .social-login a {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .auth-container {
                grid-template-columns: 1fr;
            }
            
            .auth-left {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <div class="auth-left-content">
                <h1>Create Account</h1>
                <p>Join Buttercloud Bakery today and enjoy fresh pastries delivered to your door. Create your account to get started!</p>
                <p>Already Have an Account? <a href="{{ route('login') }}">Login Here</a></p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h2>User Information</h2>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                @if($errors->any())
                    <div class="error-message">
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-wrapper">
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Your Name" required>
                        <span class="input-icon">👤</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required>
                        <span class="input-icon">✉️</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="••••••••••••••••" required>
                        <span class="input-icon">🔒</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••••••••••" required>
                        <span class="input-icon">🔒</span>
                    </div>
                </div>

                <button type="submit" class="btn-register">Create Account</button>

                <div class="divider">OR</div>

                <div class="social-login">
                    <a href="{{ route('shop.index') }}">
                        Continue as Guest →
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
