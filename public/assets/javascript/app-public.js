$(function() {
    $('form.ajax').on('submit', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var $form =
                $(this),
                $submitButton = $form.find('input[type=submit]'),
                ajaxFormConf = {
                    delegation: true,
                    beforeSerialize: function(jqForm, options) {
                        window.doSubmit = true;
                        clearFormErrors(jqForm[0]);
                        toggleSubmitDisabled($submitButton);
                    },
                    beforeSubmit: function() {
                        $submitButton = $form.find('input[type=submit]');
                        toggleSubmitDisabled($submitButton);
                        return window.doSubmit;
                    },
                    error: function(data, statusText, xhr, $form) {
                        $submitButton = $form.find('input[type=submit]');

                        // Form validation error.
                        if (422 == data.status) {
                            processFormErrors($form, $.parseJSON(data.responseText));
                            return;
                        }

                        toggleSubmitDisabled($submitButton);
                        showMessage(lang("whoops"));
                    },
                    success: function(data, statusText, xhr, $form) {
                        var $submitButton = $form.find('input[type=submit]');

                        if (data.message) {
                            showMessage(data.message);
                        }
                        switch (data.status) {
                            case 'success':

                                if (data.redirectUrl) {
                                    if(data.redirectData)  {
                                        $.redirectPost(data.redirectUrl, data.redirectData);
                                    } else {
                                        document.location.href = data.redirectUrl;
                                    }
                                }
                                break;

                            case 'error':
                                if (data.messages) {
                                    processFormErrors($form, data.messages);
                                    return;
                                }
                                break;

                            default:
                                break;


                        }

                        toggleSubmitDisabled($submitButton);


                    },
                    dataType: 'json'
                };

        toggleSubmitDisabled($submitButton);

        if ($form.hasClass('payment-form') && $('#pay_offline').val()==0) {
            clearFormErrors($('.payment-form'));

            Stripe.setPublishableKey($form.data('stripe-pub-key'));

            var
                    noErrors = true,
                    $cardNumber = $('.card-number'),
                    $cardName = $('.card-name'),
                    $cvcNumber = $('.card-cvc'),
                    $expiryMonth = $('.card-expiry-month'),
                    $expiryYear = $('.card-expiry-year');


            if (!Stripe.validateCardNumber($cardNumber.val())) {
                showFormError($cardNumber, lang("credit_card_error"));
                noErrors = false;
            }

            if (!Stripe.validateCVC($cvcNumber.val())) {
                showFormError($cardNumber, lang("cvc_error"));
                noErrors = false;
            }

            if (!Stripe.validateExpiry($expiryMonth.val(), $expiryYear.val())) {
                showFormError($cardNumber, lang("expiry_error"));
                showFormError($expiryYear, '');
                noErrors = false;
            }

            if (noErrors) {
                Stripe.card.createToken({
                    name: $cardName.val(),
                    number: $cardNumber.val(),
                    cvc: $cvcNumber.val(),
                    exp_month: $expiryMonth.val(),
                    exp_year: $expiryYear.val()
                },
                function(status, response) {

                    if (response.error) {
                        clearFormErrors($('.payment-form'));
                        if(response.error.param.length>0)
                            showFormError($('*[data-stripe=' + response.error.param + ']', $('.payment-form')), response.error.message);
                        else
                            showMessage(response.error.message);
                        toggleSubmitDisabled($submitButton);
                    } else {
                        var token = response.id;
                        $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                        $form.ajaxSubmit(ajaxFormConf);
                    }

                });
            } else {
                showMessage(lang("card_validation_error"));
                toggleSubmitDisabled($submitButton);
            }

        } else {
            $form.ajaxSubmit(ajaxFormConf);
        }
    });

    $('a').smoothScroll({
        offset: -60
    });


    /* Scroll to top */
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.totop').fadeIn();
        } else {
            $('.totop').fadeOut();
        }
    });

    $('#organiserHead').on('click', function(e) {
        e.stopImmediatePropagation();
        $('#organiser')[0].scrollIntoView();
    });

    $('#contact_organiser').on('click', function(e) {
        e.preventDefault();
        $('.contact_form').slideToggle();
    });

    $('#mirror_buyer_info').on('click', function(e) {
        $('.ticket_holder_first_name').val($('#order_first_name').val());
        $('.ticket_holder_last_name').val($('#order_last_name').val());
        $('.ticket_holder_email').val($('#order_email').val());
    });

    $('#order_email').on('keyup', function(e) {
        $('.ticket_holder_first_name').val($('#order_first_name').val());
        $('.ticket_holder_last_name').val($('#order_last_name').val());
        $('.ticket_holder_email').val($('#order_email').val());
    });

    $('.card-number').payment('formatCardNumber');
    $('.card-cvc').payment('formatCardCVC');

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).data("target"); // activated tab
        if(target=="#card")
        {
            $('#pay_offline').val(0);
            $('#pay_offline').attr('disabled',true);
            $('.online_payment input,  .online_payment select').attr('disabled', false);
        }else{
            $('#pay_offline').val(1);
            $('#pay_offline').attr('disabled',false);
            $('.online_payment input,  .online_payment select').attr('disabled', true);
        }
      });
});

function processFormErrors($form, errors)
{
    $.each(errors, function (index, error)
    {
        var selector = (index.indexOf(".") >= 0) ? '.' + index.replace(/\./g, "\\.") : ':input[name=' + index + ']';
        var $input = $(selector, $form);

        if ($input.prop('type') === 'file') {
            $('#input-' + $input.prop('name')).append('<div class="help-block error">' + error + '</div>')
                .parent()
                .addClass('has-error');
        } else {
            if($input.parent().hasClass('input-group')) {
                $input = $input.parent();
            }

            $input.after('<div class="help-block error">' + error + '</div>')
                .parent()
                .addClass('has-error');
        }
    });

    var $submitButton = $form.find('input[type=submit]');
    toggleSubmitDisabled($submitButton);
}

/**
 * Toggle a submit button disabled/enabled
 *
 * @param element $submitButton
 * @returns void
 */
function toggleSubmitDisabled($submitButton) {

    if ($submitButton.hasClass('disabled')) {
        $submitButton.attr('disabled', false)
                .removeClass('disabled')
                .val($submitButton.data('original-text'));
        return;
    }

    $submitButton.data('original-text', $submitButton.val())
            .attr('disabled', true)
            .addClass('disabled')
            .val(lang("processing"));
}

/**
 * Clears given form of any error classes / messages
 *
 * @param {Element} $form
 * @returns {void}
 */
function clearFormErrors($form) {
    $($form)
            .find('.error.help-block')
            .remove();
    $($form).find(':input')
            .parent()
            .removeClass('has-error');
    $($form).find(':input')
            .parent().parent()
            .removeClass('has-error');
}

function showFormError($formElement, message) {
    $formElement.after('<div class="help-block error">' + message + '</div>')
            .parent()
            .addClass('has-error');
}

/**
 * Shows users a message.
 * Currently uses humane.js
 *
 * @param string message
 * @returns void
 */
function showMessage(message) {
    humane.log(message, {
        timeoutAfterMove: 3000,
        waitForMove: true
    });
}

function hideMessage() {
    humane.remove();
}

/**
 * Counts down to the given number of seconds
 *
 * @param element $element
 * @param int seconds
 * @returns void
 */
function setCountdown($element, seconds) {

    var endTime, mins, msLeft, time, twoMinWarningShown = false;

    function twoDigits(n) {
        return (n <= 9 ? "0" + n : n);
    }

    function updateTimer() {
        msLeft = endTime - (+new Date);
        if (msLeft < 1000) {
            alert(lang("time_run_out"));
            location.reload();
        } else {

            if (msLeft < 120000 && !twoMinWarningShown) {
                showMessage(lang("just_2_minutes"));
                twoMinWarningShown = true;
            }

            time = new Date(msLeft);
            mins = time.getUTCMinutes();
            $element.html('<b>' + mins + ':' + twoDigits(time.getUTCSeconds()) + '</b>');
            setTimeout(updateTimer, time.getUTCMilliseconds() + 500);
        }
    }

    endTime = (+new Date) + 1000 * seconds + 500;
    updateTimer();
}

$.extend(
    {
        redirectPost: function(location, args)
        {
            var form = '';
            $.each( args, function( key, value ) {
                value = value.split('"').join('\"')
                form += '<input type="hidden" name="'+key+'" value="'+value+'">';
            });
            $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
        }
    });
