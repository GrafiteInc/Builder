

<form method="POST" action="/login">
    {!! csrf_field() !!}

    <div>
        Email
        <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
        Password
        <input type="password" name="password" id="password">
    </div>

    <div>
        <input type="checkbox" name="remember"> Remember Me
    </div>

    <a href="/password/reset">Forgot Password</a>

    <div>
        <button type="submit">Login</button>
    </div>

    <a href="/register">Register</a>
</form>