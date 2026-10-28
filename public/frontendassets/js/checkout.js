
document.addEventListener("DOMContentLoaded", function () {
    const captchaQuestion = document.getElementById("captchaQuestion");
    const captchaAnswer = document.getElementById("captchaAnswer");
    const refreshCaptcha = document.getElementById("refreshCaptcha");
    const placeOrderBtn = document.getElementById("placeOrderBtn");

    let correctAnswer = 0;
    $('#captchaAnswer').removeClass('shake is-invalid');
    // Generate captcha
    function generateCaptcha() {
        const num1 = Math.floor(Math.random() * 10) + 1;
        const num2 = Math.floor(Math.random() * 10) + 1;
        correctAnswer = num1 + num2;
        captchaQuestion.textContent = `${num1} + ${num2} = ?`;
        captchaAnswer.value = "";
    }

    // Refresh captcha
    refreshCaptcha.addEventListener("click", generateCaptcha);

    // Place order validation
    placeOrderBtn.addEventListener("click", function () {
        const userAnswer = parseInt(captchaAnswer.value, 10);

        setTimeout(() => {
            $('#captchaAnswer').removeClass('is-invalid');
       }, 1200);

        if (isNaN(userAnswer)) {
             $('#captchaAnswer').addClass('shake is-invalid');
            return;
        }

        if (userAnswer !== correctAnswer) {
            $('#captchaAnswer').addClass('shake is-invalid');
            generateCaptcha();
            return;
        }
       
        $('#placeOrdrBtn').trigger('click');
    });

//     // Generate on load
    generateCaptcha();
});
// $(document).ready(function () {
//     console.log("Document ready");
//     managePinCode('R');
//     function managePinCode(_action, val = null) {
//     //console.log("managePinCode called with action:", _action);
//     let key = 'boomija_pin_code';
//     if (_action == 'W') {
//         return localStorage.setItem(key, val);
//     } else {
//         let pincode = localStorage.getItem(key);
//         if (pincode) {
//             console.log("Found pincode:", pincode);
//             $('#user_pincode').val(pincode).trigger('input');
//         }
//         return true;
//     }
//     }
// });



$('#captchaAnswer').on('click',function(){
    $(this).removeClass('shake is-invalid');
});
const placeOrderForm = $('#placeOrderForm').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            first_name: { required: true },
            last_name: { required: true },
            email: {  required: false , email:true},
            phone: { 
                required: true,
                mobileNumber: true
             },
            address_line_one: { required: true , maxlength: 700 },
            address_line_two: { required: false , maxlength: 700 },
            city: { required: true },
            pin: { required: true,
                minlength: 6,
                maxlength: 6,
                digits: true
            },
            state: { required: true },
        },
       highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function () {
            return true; 
        },
         submitHandler: function (form) {
                // let getPincode = $('#pin').val();
                 $('#placeOrderBtn').attr('style','display:none').after('<button class="button button_size_2 button_js" disabled="disabled">Submitting....</button>');
                 form.submit();
                 
         }
       
    });
    jQuery.validator.addMethod("mobileNumber", function (value, element) {
        // Example regex for a 10-digit Indian mobile number starting with 6, 7, 8, or 9
        var regex = /^[6-9]\d{9}$/; 
        return this.optional(element) || regex.test(value);
    }, "Please enter a valid mobile number.");

 const customerLogin = $('#loginForm').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            email: {  required: true , email:true},
            password: { required: true },
        },
       highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function () {
            return true; 
        }
       
    });   

$(document).on('input', '#user_pincode', function () {
    let pincode = $(this).val();
     let finalAmounBeforShpCharg = $('#needToPay').attr('data-f-amount');
     let shippingFreeAfter  = $('#free_shipping').attr('data-free-shipping');
    // //alert($('#needToPay').attr('data-f-amount')); return false;
    // $('#shipping_charges').removeClass('cut_through');
    if (pincode.length === 6) {
        let url = '/ajaxcall';
        let csrfToken = $('meta[name="csrf-token"]').attr('content');

        let dataToPass = {
            type: 'SHIPPING-CHARGE',
            value: pincode,
        };

        $.ajax({
            url: url,
            headers: { 'X-XSRF-TOKEN': csrfToken },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', csrfToken);
            },
            type: "POST",
            data: { data: dataToPass },
            success: function (res) {
                $('#placeOrdrBtn').removeAttr('disabled').prop('disabled', false);
                $('#ajaxReply').remove();
                console.log(res);
                

                if (res.status === 'success') {
                    // Save pin in localStorage
                    managePinCode('W', pincode);

                    
                    $('#shipping_charges')
                        .attr('data-shipping-charge', res.charge)
                        .text('₹' + res.charge);

                    let subtotal = $('#finalSubtotal').attr('data-shubt');
                    let needToPay =  parseFloat(subtotal) + parseFloat(res.charge);
                    let amount = parseFloat(finalAmounBeforShpCharg) || 0;
                    let freeLimit = parseFloat(shippingFreeAfter) || 0;
                   
                    if( amount > freeLimit){
                        needToPay = parseFloat(subtotal);
                        $('#shipping_charges').addClass('cut_through');
                    }
                    
                    $('#needToPay')
                        .attr('data-f-amount', needToPay)
                        .text('₹' + needToPay);

                    $('#final_amount_aftercal').val(needToPay);
                    //alert(needToPay);


                    $('#placeOrderBtn').before(
                        `<p class="text text-success" id="ajaxReply">${res.msg}</p>`
                    );

                    // $('html, body').animate({
                    //     scrollTop: $('#placedBox').offset().top
                    // }, 500);

                } else {
                    if (res.status === 'ERROR-1') {
                        $('#placeOrdrBtn').attr('disabled', 'disabled').prop('disabled', true);
                        $('#placeOrderForm').before(
                            `<p class="text text-danger" id="ajaxReply">${res.msg}</p>`
                        );
                    }

                    $('html, body').animate({
                        scrollTop: $('.entry-content').offset().top
                    }, 500);
                }
            }
        });
    }
});

//new ***********************
$(document).ready(function () {
    console.log("Document ready");
     let getpn = managePinCode('R');
    
     if(!getpn){
        window.location.href = $('body').data('url')+'/cart';
     }
    
    function managePinCode(_action, val = null) {
    //console.log("managePinCode called with action:", _action);
    let key = 'boomija_pin_code';
    if (_action == 'W') {
        return localStorage.setItem(key, val);
    } else {
        let pincode = localStorage.getItem(key);
        if (pincode) {
           
            $('#user_pincode').val(pincode).trigger('input');
             return pincode;
        }
       
    }
    }
});