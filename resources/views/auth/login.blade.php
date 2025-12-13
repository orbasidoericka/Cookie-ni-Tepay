@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="page-title">Login</div>
    <div style="max-width:400px;margin-top:1rem;">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div style="margin-bottom:1rem;">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required style="width:100%;padding:0.5rem;border-radius:6px;border:1px solid #ddd;" />
            </div>

            <div style="margin-bottom:1rem;">
                <label>Password</label>
                <input type="password" name="password" required style="width:100%;padding:0.5rem;border-radius:6px;border:1px solid #ddd;" />
            </div>

            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1rem;">
                <input type="checkbox" id="remember" name="remember" />
                <label for="remember">Remember me</label>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0;padding-left:1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>
@endsection
