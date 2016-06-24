@if (isset($errors))
    @if ($errors->count() > 0)
        <div class="ui floating negative message alert">
            <i class="fa fa-close"></i>
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif