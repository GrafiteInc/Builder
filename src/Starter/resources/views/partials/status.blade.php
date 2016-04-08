@if(Session::has('status'))
    <p>{{ Session::get('status') }}</p>
@endif