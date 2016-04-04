
    <div class="col-md-12 raw-margin-top-24">
        <label>Phone</label>
        <input class="form-control" type="text" name="meta[phone]" value="{{ $user->meta->phone }}">
    </div>

    <div class="col-md-12 raw-margin-top-24">
        <label>
            <input type="checkbox" name="meta[marketing]" value="1" @if ($user->meta->marketing) checked @endif>
            I agree to recieve marketing materials
        </label>
    </div>

    <div class="col-md-12 raw-margin-top-24">
        <label>
            <input type="checkbox" name="meta[terms_and_cond]" value="1" @if ($user->meta->terms_and_cond) checked @endif>
            I agree to the <a href="{{ url('terms-and-conditions') }}">Terms &amp; Conditions</a>
        </label>
    </div>