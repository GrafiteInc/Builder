
    <div>
        Phone
        <input type="text" name="meta[phone]" value="{{ $user->meta->phone }}">
    </div>

    <div>
        Marketing Info
        <input type="checkbox" name="meta[marketing]" value="1" @if ($user->meta->marketing) checked @endif>
    </div>

    <div>
        Terms &amp; Conditions
        <input type="checkbox" name="meta[terms_and_cond]" value="1" @if ($user->meta->terns_and_cond) checked @endif>
    </div>