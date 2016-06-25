<h1>Reset Your Password</h1>

<form method="POST" action="/password/email">

    {!! csrf_field() !!}

    @include('partials.errors')
    @include('partials.status')

    <div>
        Email
        <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}">
    </div>

    <div>
        <button type="submit" class="button">Send Password Reset Link</button>
    </div>

</form>
