@if (isset($errors))
    @if ($errors->count() > 0)
        <div class="ui floating negative message alert">
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <i class="fa fa-close"></i>
        </div>
    @endif
@endif