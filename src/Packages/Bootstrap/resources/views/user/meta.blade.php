
    <div class="raw-margin-top-24">
        @input_maker_label('Phone')
        @input_maker_create('meta[phone]', ['type' => 'string'], $user)
    </div>

    <div class="raw-margin-top-24">
        @input_maker_create('meta[marketing]', ['type' => 'checkbox'], $user)
        @input_maker_label('I agree to recieve marketing materials')
    </div>

    <div class="raw-margin-top-24">
        <label>
            <input type="checkbox" name="meta[terms_and_cond]" value="1" @if ($user->meta->terms_and_cond) checked @endif>
            I agree to the <a href="{{ url('terms-and-conditions') }}">Terms &amp; Conditions</a>
        </label>
    </div>