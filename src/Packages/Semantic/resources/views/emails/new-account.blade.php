<h1>Welcome {{ $user->email }}</h1>

<p>You have a brand new account! We're so delighted to bring you on board.</p>

<p>Email: {{ $user->email }}</p>
<p>Password: {{ $password }}</p>

<p>Go here to login: <a href="{{ url('login') }}">Login</a></p>