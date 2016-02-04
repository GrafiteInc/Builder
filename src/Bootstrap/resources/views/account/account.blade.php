
    <div class="col-md-12 raw-margin-top-24"
        <label>Phone</label>
        <input class="form-control" type="text" name="account[phone]" value="{{ $account->account->phone }}">
    </div>

    <div class="col-md-12 raw-margin-top-24"
        <label>Marketing Info</label>
        <input type="checkbox" name="account[marketing]" value="1" @if ($account->account->marketing) checked @endif>
    </div>