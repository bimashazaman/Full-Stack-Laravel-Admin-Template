<!DOCTYPE html>
<html>
<head>
    <title>Stripe Integration</title>
    <link rel="stylesheet" href="{{asset('css/core.css')}}"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
<body>

<div class="container">

    <div class="row justify-content-center">
        <div class="col-11 col-md-10 col-lg-8 col-xl-6  mt-3">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="card card-with-shadow border-0 mt-3">
                <div class="card-body">

                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif

                    <form
                            role="form"
                            action="{{ route('stripe.post') }}"
                            method="post"
                            class="require-validation"
                            data-cc-on-file="false"
                            data-stripe-publishable-key="{{ $public_key }}"
                            id="payment-form">
                        @csrf

                        <div class='form-group row'>
                            <div class='col-12 required'>
                                <label class=''>Name on Card</label>
                                <input class='form-control' type='text'>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <div class='col-12 required'>
                                <label class=''>Card Number</label> <input
                                        autocomplete='off' class='form-control card-number'
                                        type='text'>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <div class='col-12 col-md-4 cvc required'>
                                <label class=''>CVC</label>
                                <input autocomplete='off'
                                       class='form-control card-cvc'
                                       placeholder='ex. 311' size='4'
                                       type='text'>
                            </div>
                            <div class='col-12 col-md-4 expiration required'>
                                <label class=''>Expiration Month</label> <input
                                        class='form-control card-expiry-month' placeholder='MM' size='2'
                                        type='text'>
                            </div>
                            <div class='col-12 col-md-4 expiration required'>
                                <label class=''>Expiration Year</label> <input
                                        class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                        type='text'>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <div class='col-md-12 error form-group d-none'>
                                <div class='alert-danger alert'>Please correct the errors and try
                                    again.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary btn-block mb-3" type="submit">Pay Now ($100)</button>
                                <a href="payment-view" class="btn btn-secondary btn-block">Back</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">
    $(function () {

        let $form = $(".require-validation");

        $('form.require-validation').bind('submit', function (e) {
            var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                    'input[type=text]', 'input[type=file]',
                    'textarea'].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            $errorMessage.addClass('d-none');

            $('.has-error').removeClass('has-error');
            $inputs.each(function (i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('d-none');
                    e.preventDefault();
                }
            });

            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            }

        });

        function stripeResponseHandler(status, response) {

            if (response.error) {
                $('.error')
                    .removeClass('d-none')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                /* token contains id, last4, and card type */
                var token = response['id'];

                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }

    });
</script>
</html>