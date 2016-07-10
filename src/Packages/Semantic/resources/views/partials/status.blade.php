@if (Session::has('status'))
    <div class="ui info floating message alert">
        <span> {{ Session::get('status') }} </span>
        <i class="fa fa-close"></i>
    </div>
@endif