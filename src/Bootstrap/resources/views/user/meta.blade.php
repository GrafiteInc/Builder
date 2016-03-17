
    <div class="col-md-12 raw-margin-top-24"
        <label>Phone</label>
        <input class="form-control" type="text" name="meta[phone]" value="{{ $user->meta->phone }}">
    </div>

    <div class="col-md-12 raw-margin-top-24"
        <label>Marketing Info</label>
        <input type="checkbox" name="meta[marketing]" value="1" @if ($user->meta->marketing) checked @endif>
    </div>