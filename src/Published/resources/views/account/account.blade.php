
    <div>
        Phone
        <input type="text" name="account[phone]" value="{{ $account->account->phone }}">
    </div>

    <div>
        Marketing Info
        <input type="checkbox" name="account[marketing]" value="1" @if ($account->account->marketing) checked @endif>
    </div>