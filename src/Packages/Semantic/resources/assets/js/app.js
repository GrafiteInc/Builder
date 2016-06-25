$(function(){

    $('.alert').delay(7000).fadeOut();
    $('.alert .fa-close').on('click', function() {
        $(this).closest('.message').transition('fade');
    });

});