
    <div>
        @input_maker_label('Phone')
        @input_maker_create('meta[phone]', ['type' => 'string'], $user)
    </div>

    <div>
        @input_maker_label('I agree to receive marketing information')
        @input_maker_create('meta[marketing]', ['type' => 'checkbox'], $user)
    </div>

    <div>
        @input_maker_label('Terms &amp; Conditions')
        @input_maker_create('meta[terms_and_cond]', ['type' => 'checkbox'], $user)
    </div>