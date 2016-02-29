@if (Session::has('message') && ! is_array(Session::get('message')))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span> {{ Session::get('message') }} </span>
    </div>
@endif