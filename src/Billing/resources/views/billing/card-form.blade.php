{!! csrf_field() !!}

<input type="hidden" id="exp_month" name="exp_month" data-stripe="exp-month">
<input type="hidden" id="exp_year" name="exp_year" data-stripe="exp-year">

<div class="form-group number">
    <label for="number">Number</label>
    <input class="form-control" type="text" name="number" id="number" required placeholder="Card Number" value="{{ old('number') }}" data-stripe="number">
</div>

<div class="form-group name">
    <label for="name">Name</label>
    <input class="form-control" type="text" name="name" id="name" required placeholder="Full Name" value="{{ $user->name }}" data-stripe="name">
</div>

<div class="form-group expiry">
    <label for="expiry">Expiry Date</label>
    <input class="form-control" type="text" name="expiry" id="expiry" required placeholder="MM/YYYY" value="{{ old('expiry') }}">
</div>

<div class="form-group cvc">
    <label for="cvc">CVC</label>
    <input class="form-control" type="text" name="cvc" id="cvc" required placeholder="CVC" value="{{ old('cvc') }}" data-stripe="cvc">
</div>