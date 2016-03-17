
    <div>
        Phone
        <input type="text" name="meta[phone]" value="{{ $user->meta->phone }}">
    </div>

    <div>
        Marketing Info
        <input type="checkbox" name="meta[marketing]" value="1" @if ($user->meta->marketing) checked @endif>
    </div>