@if (Session::has('message') && ! is_array(Session::get('message')))
    <div class="ui info floating message alert">
        <span> {{ Session::get('message') }} </span>
        <i class="fa fa-close"></i>
    </div>
@endif