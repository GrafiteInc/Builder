$(function(){
    $('form').card({
        form: 'form',
        container: '.card-wrapper',
        placeholders: {
            number: '**** **** **** ****',
            name: 'Arya Stark',
            expiry: '**/****',
            cvc: '***'
        }
    });

    jQuery(function($) {
        $('form').submit(function(event) {
            var $form = $(this);
            $form.find('button').prop('disabled', true);

            var _month = $("#expiry").val().substring(0, 2);
            var _year = $("#expiry").val().substring($("#expiry").val().indexOf("/")+2, $("#expiry").val().length);

            $('#exp_month').val(_month);
            $('#exp_year').val(_year);

            Stripe.card.createToken($form, stripeResponseHandler);

            return false;
        });
    });

    var stripeResponseHandler = function(status, response) {
        var $form = $('form');

        if (response.error) {
            $form.find('.payment-errors').text(response.error.message);
            $form.find('button').prop('disabled', false);
        } else {
            var token = response.id;
            $form.append($('<input type="hidden" name="stripeToken" />').val(token));
            $form.get(0).submit();
        }
    };
});