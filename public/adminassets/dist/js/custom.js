
function getCurrentTime(){
    const now = new Date();

// To get the full date and time string in a human-readable format based on the user's locale
const currentDateTimeString = now.toLocaleString();
console.log(currentDateTimeString);

// To get only the date portion as a string
const currentDateString = now.toDateString();
//console.log(currentDateString);

// To get individual components like year, month, day, hours, minutes, and seconds
const year = now.getFullYear();
const month = now.getMonth() + 1; // getMonth() returns 0-11, so add 1 for actual month
const day = now.getDate();
const hours = now.getHours();
const minutes = now.getMinutes();
const seconds = now.getSeconds()
return `${day}-${month}-${year}` ;
}

$(document).ready(function () {
    
    

    const goDownvalidator = $('#goDownproductAction').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            name: { required: true },
            category_id: { required: true },
            unit_category_id: { required: true },
        },
       highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function () {
            return false; 
        },
        submitHandler: function (form) {
         let prodNm = $('#title').val();
         let mode = $('#action').attr('mode');
         let slug = $('#action').attr('slug');

            let submitBtn = $('#submitButton');
            let originalBtnText = submitBtn.text();
            submitBtn.prop('disabled', true).text('Processing...');
            $('#ajaxResponse').html('');

             let url = '/admin/before-submit-form';
             let csrfToken = $('meta[name="csrf-token"]').attr('content');
             let tble ='gdp';
            if (prodNm.length > 0) {
            $.ajax({
                url: url,
                headers: { 'X-XSRF-TOKEN' : csrfToken },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken );
                },
                type: "POST",
                data: { search: prodNm , section: tble ,param: { mode: mode , slug:slug} },
                success: function(data) {
                     
                    if(data > 0 ){
                        submitBtn.prop('disabled', false).text(originalBtnText);
                            
                            $('html, body').animate({
                                scrollTop: $('body').offset().top
                            }, 500);
                        $('#title').addClass('is-invalid');
                        $('#ajaxResponse').html(
                            `<p class="text text-danger text-center">`+prodNm+` already exists.</p>`
                        );
                    }else{
                        form.submit();
                    }
                }
            });
        }
           


         }
    });

jQuery.validator.addMethod("mobileNumber", function (value, element) {
        // Example regex for a 10-digit Indian mobile number starting with 6, 7, 8, or 9
        var regex = /^[6-9]\d{9}$/; 
        return this.optional(element) || regex.test(value);
    }, "Please enter a valid mobile number.");

     $('#vendorForm').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            name: { required: true },
            contact_person: { required: true },
            phone_1: { 
                required: true,
                mobileNumber: true
             },
             email:{ email: true }
        },
       highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function () {
            return false; 
        }
    });
    $('#damagedProducts').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            damaged_date: { required: true },
            go_down_product_id: { required: true },
            quantity: { required: true },
            unit_id: { required: true },
        },
       highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function () {
            return false; 
        }
    });
    
 
     $('#damagedPacketProducts').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            quantity: { required: true },
            product_id: { required: true },
            damaged_date: { required: true },
        },
       highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function () {
            return false; 
        }
    });
    
    $('#OrderReturnForm').validate({
        errorClass: 'is-invalid',
        errorElement: 'div',
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function () {
            return false; // disable default error placement
        }
    });

     $('input[name^="quantity["]').each(function () {
        const max = parseFloat($(this).attr('max'));
        $(this).rules("add", {
            required: true,
            number: true,
            max: max
        });
    });

    $('input[name^="quantity["]').on('input', function () {
        const max = parseFloat($(this).attr('max'));
        if (parseFloat($(this).val()) > max) {
            $(this).val(max);
        }
    });

 

    if( $('#addVendorGoDown').attr('mode') == 'add' ){
        $('#addVendorGoDown').trigger('click');
    } 


    
$('#managerPasswordSec').validate({
    errorClass: 'is-invalid',
    errorElement: 'div',
    rules: {
        password: { required: true },
        current_password: { required: true },
        password_confirmation: {
            required: true,
            equalTo: '[name="password"]'
        }
    },
    messages: {
        password_confirmation: {
            equalTo: "Passwords do not match"
        }
    },
    highlight: function (element) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element) {
        $(element).removeClass('is-invalid');
    },
    errorPlacement: function () {
        return false; // No error messages shown, only red border
    }
});




const validator =$('#productAction').validate({
    errorClass: 'is-invalid', 
    errorElement: 'div',
    rules: {
        go_down_product_id: { required: true },
        packet_size:{ required: true },
        quantity: { required: true },
        unit_id: { required: true },
        buying_price: { required: true },
        selling_price: { required: true },
    },
    highlight: function (element) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element) {
        $(element).removeClass('is-invalid');
    },
    errorPlacement: function (error, element) {
    error.addClass('text-danger small'); // style
    error.insertAfter(element); // place after the input
},
    submitHandler: function (form, e) {
        e.preventDefault(); // stop default submit

        let submitBtn = $('#submitButton');
        let current_packet_qunatity = $('#current_packet_qunatity').val();
        let quantity = $('#quantity').val();
        let originalBtnText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Processing...');
        let quntityCheck= 'N';
        if(parseFloat(current_packet_qunatity) == parseFloat(quantity)){
            quntityCheck= 'Y';
        }
        console.log(quantity,current_packet_qunatity,quntityCheck)

        let query = {
            mode: $('#action').attr('mode'),
            packet_size : $('#packet_size').val(),
            unit_id : $('#unit_id').val(),
            quantity : quantity,
            go_down_product_id: $('#go_down_product_id').val(),
            slug : $('#prod_slug').val() || '',
            check_qnt : quntityCheck
        };

        let csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '/admin/check-availability',
            method: "POST",
            headers: { 'X-CSRF-Token': csrfToken },
            data: { search: query },
            success: function(data) {
                submitBtn.prop('disabled', false).html(originalBtnText);

                if (data.reject_request === 'Y') {
                    $('#ajaxResponse').html(`<p class="text text-danger text-center">The quantity added cannot exceed the available stock in the godown.</p>`);
                    $('#quantity').addClass('is-invalid');
                    $('html, body').animate({ scrollTop: $('body').offset().top }, 500);

                }else if (data.reject_request === 'D') {
                    $('#ajaxResponse').html(`<p class="text text-danger text-center">This product already exists with same packet size.</p>`);
                    $('#quantity').addClass('is-invalid');
                    $('html, body').animate({ scrollTop: $('body').offset().top }, 500);

                }else if (data.reject_request === 'Q') {
                    $('#ajaxResponse').html(`<p class="text text-danger text-center">The product quantity has changed to `+ data.current_quantity +`, 
                        possibly because of an order or return. Please update quantity to 
                        `+ data.current_quantity +` or more than it.
                        <a href="javascript:void(0);" id="updatePackt" current-qnt="`+data.current_quantity+`">click here</a> to referesh quantity
                        </p>`);
                    $('#quantity').addClass('is-invalid');
                    $('html, body').animate({ scrollTop: $('body').offset().top }, 500);

                } else if (data.reject_request === 'N') {
                    
                    form.removeEventListener('submit', preventValidation);
                    form.submit();
                }
            }
        });

        // preventValidate hook to stop normal submit before AJAX
        function preventValidation(e) {
            e.preventDefault();
        }
        form.addEventListener('submit', preventValidation);
    }
});

$.validator.addMethod("realDateDMY", function(value, element) {
    // Check format d-m-Y
    const regex = /^([0-2]\d|3[01])-(0\d|1[0-2])-(\d{4})$/;
    if (!regex.test(value)) return false;

    // Check actual date validity
    const [day, month, year] = value.split('-').map(Number);
    const date = new Date(year, month - 1, day);
    return (
        date.getFullYear() === year &&
        date.getMonth() === month - 1 &&
        date.getDate() === day
    );
});

jQuery.validator.addMethod("datetimeDMYHi", function (value, element) {
    return this.optional(element) || /^(0?[1-9]|[12][0-9]|3[01])-(0?[1-9]|1[0-2])-\d{4} ([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(value);
}, "Please enter a valid date in format DD-MM-YYYY H:i");

    $('#reportGenerator').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            report_type: { required: true },
            report_of: { required: true},
            date_from: { required: true , realDateDMY: true},
            date_to: { required: true , realDateDMY: true},
           
        },
       highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function () {
            return false; 
        }
    });


    $('#stickerForm').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            name: { required: true },
            quantity: { required: true},
            action_date: { required: true},
            mode: { required: true},
           
        },
       highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function () {
            return false; 
        }
    });

     $('.selectPicker').select2({
        theme: 'bootstrap4'
      });
      $('.select2-selection--single').attr('style','background: transparent;');
      $('.select2-selection__rendered').attr('style','color: ##000;');
      
function ucwords (str) {
    let makeWord = (str+'').toLowerCase();
    return (makeWord + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

$(document).on('click', '#updatePackt', function () {
    let  qnty = $(this).attr('current-qnt');
   // alert(qnty);
      $('#quantity').val(qnty).attr('value',qnty).removeClass('is-invalid');
        $('#current_packet_qunatity').val(qnty).attr('value',qnty).removeClass('is-invalid');
        $('#ajaxResponse').html('');

 });

$('.searchInput').on('keyup', function() {
        let query = $(this).val();
        let DataFor = $(this).data('for');
        let tble='';
        console.log(DataFor, query);
        
        let url= 'autocomplete';
        let LiClassName = 'suggest-item';
        let slug='';
        let csrfToken = $('div[name="csrf-token"]').attr('content');
        if(DataFor == 'addNew'){
            url = '/admin/autocomplete';
            csrfToken = $('meta[name="csrf-token"]').attr('content');
            

        }else if(DataFor == 'products'){
            url = '/admin/suggest-product';
            csrfToken = $('meta[name="csrf-token"]').attr('content');
            

        }else if(DataFor == 'mastersdata'){
           
            url = '/admin/master-autocomplete';
            csrfToken = $('meta[name="csrf-token"]').attr('content');
            tble = $(this).data('section');
            LiClassName='onlyShowing';
            slug= $(this).data('slug');
            console.log();
            
        }else if(DataFor == 'stickers'){
            url = '/admin/suggest-stickers';
            csrfToken = $('meta[name="csrf-token"]').attr('content');
            

        }


        if (query.length > 0) {
            $.ajax({
                url: url,
                headers: { 'X-XSRF-TOKEN' : csrfToken },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken );
                },
                type: "POST",
                data: { search: query , section: tble , slug:slug},
                success: function(data) {
                    let list = '';
                    data.forEach(item => {
                        let pdName = ucwords(item.name);
                         list += `<li class="`+LiClassName+`">${pdName}</li>`;
                    });
                    $('#suggestionList').html(list).show();
                    
                    
                    
                }
            });
        } else {
            $('#suggestionList').hide();
        }
    });

    
     
    let stickerList=[];
    $('#suggest_sticker').on('keyup', function() {
        let query = $(this).val();
        
        let url= '/admin/get-stickers';
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
       


        if (query.length >= 2) {
            $.ajax({
                url: url,
                headers: { 'X-XSRF-TOKEN' : csrfToken },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken );
                },
                type: "POST",
                data: { search: query },
                success: function(data) {
                    let list = '';
                   data.forEach((item, index) => {
                        let pdName = ucwords(item.name);
                        stickerList[index] = item;
                        list += `<li class="list-group-item stickerName" data-index="${index}">${pdName}</li>`;
                    });
                    $('#suggestionList').html(list).show();
                    
                    
                    
                }
            });
        } else {
            $('#suggestionList').hide();
        }
    });

    

    $(document).on('click', '.suggest-item', function() {
        $('.searchInput').val($(this).text());
        $('#suggestionList').hide();
    });

    
    $(document).on('click', '.stickerName', function () {
    let index = $(this).data('index');
    let sticker = stickerList[index];
    if (sticker) {
        $('#name').val(`${sticker.name}`);
        $('#sticker_code').val(`${sticker.sticker_code}`);
        //$('#action_slug').val(`${sticker.slug}`);
        $('#available_quantity').val(`${sticker.totalsticker.quantity}`);
         $('#quantity').attr('max',quantity).attr('placeholder','Max quantity :'+sticker.totalsticker.quantity);
        $('#suggestionList').hide();
        $('#suggest_sticker').val('');
    }
});



    


let customerList = []; // global array
$(document).on('keyup', '#searchCustomer', function () {
    let query = $(this).val();
    let url = '/manage/customerinfo';
    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (query.length >= 2) {
        $.ajax({
            url: url,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { search: query },
            success: function (data) {
                customerList = data; // store globally
                let list = '';

                data.forEach((item, index) => {
                    let fullName = ucwords(item.first_name + ' ' + item.last_name);
                    let phone = item.phone ?? '';
                    list += `<li class="list-group-item chooseCustomer" data-index="${index}">${fullName} - ${phone}</li>`;
                });

                $('#suggestionList').html(list).show();
            }
        });
    } else {
        $('#suggestionList').hide();
    }
});

$(document).on('click', '.chooseCustomer', function () {
    let index = $(this).data('index');
    let customer = customerList[index];

    if (customer) {
        $('#first_name').val(`${customer.first_name}`);
        $('#last_name').val(`${customer.last_name}`);
        $('#phone').val(customer.phone || '');
        $('#email').val(customer.email || '');
        $('#alternative_phone').val(customer.alternative_phone || '');
        $('#address_line_one').val(customer.address_line_one || '');
        $('#address_line_two').val(customer.address_line_two || '');
        $('#city').val(customer.city || '');
        $('#pin').val(customer.pin || '');
        $('#state').val(customer.state || '');
        $('#gst_number').val(customer.gst_number || '');
        $('#suggestionList').hide();

        $('#searchCustomer').val('');
    }
});




    //forOrder


   $('#customerDetails').validate({
    errorClass: 'is-invalid',
    errorElement: 'div',
    rules: {
        
        order_date: { required: true , datetimeDMYHi: true },
        first_name: { required: true },
        phone: { required: true },
        address_line_one: { required: true },
        city: { required: true },
        pin: { required: true },
        state: { required: true }
    },
    highlight: function (element) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element) {
        $(element).removeClass('is-invalid');
    },
    errorPlacement: function () {
        return false;
    },
    submitHandler: function (form) {
        // Only runs when form is valid
            let url = '/manage/save-order';
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            let formData = $('#customerDetails').serialize();

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                // Handle success (e.g., show toast or go to next step)
                
                console.log(response);
                
                if( response != '2' ){
                     switchTabs('step3');
                     showFinalDetails(response);
                }else{
                     alert('Something went wrong in step 2');
                }




            },
            error: function (xhr) {
                // Handle error (optional)
                console.error(xhr.responseText);
                alert('Something went wrong!');
            }
        });
    }
});

function showFinalDetails( params ){
   // console.log(params.orderProducts , params.orderProducts);
   
    const productsArray = Object.values(params.orderProducts);
    console.log(productsArray);
    

 //console.log(productsArray, productsArray.length);

    if(productsArray.length > 0 ){
        let tableHTML = '';
        let subTotal =  0;
        productsArray.forEach(product => {
            if(product.gst){
                product.gst = product.gst;
            }else{
                product.gst = 0;
            }
            if(product.discount_percent){
                product.discount_percent = product.discount_percent;
            }else{
                product.discount_percent = 0;
            }
            
           
           tableHTML += `
                        <tr>
                            <td>${product.name}</td>
                            <td>${product.quantity}</td>
                            <td><i class="fas fa-rupee-sign"></i> ${product.selling_price}</td>
                            <td>${product.gst} %</td>
                            <td>${product.discount_percent} %</td>
                            <td><i class="fas fa-rupee-sign"></i> ${product.total}</td>
                        </tr>
                    `;

                    subTotal += parseFloat(product.total);
            // Do your processing here
        });
        document.getElementById('selectedAllProducts').innerHTML = tableHTML;
        //document.getElementById('subtotal').innerHTML = '<i class="fas fa-rupee-sign"></i> '+subTotal;
        let _overAllGst = parseFloat($('#overAll_gst').val());
        if(_overAllGst > 0 ){
            let extra_subTotal = _overAllGst *  subTotal/100;
            subTotal = subTotal + extra_subTotal;
        }
        $('.subtotal').html('<i class="fas fa-rupee-sign"></i> '+subTotal.toFixed(2));
        $('#subtotal').attr('amount',subTotal.toFixed(2));
        

        
    }


    const labelMap = {
            first_name: "First Name",
            last_name: "Last Name",
            email: "Email",
            phone: "Phone",
            alternative_phone: "Alt. Phone",
            address_line_one: "Address Line 1",
            address_line_two: "Address Line 2",
            city: "City",
            state: "State",
            pin: "PIN Code",
            gst_number: "GST Number",
        };
        let orderCustomer = params.orderCustomer;
        let customerHtml = '';
            // for (let key in orderCustomer) {
            //     if (
            //         orderCustomer.hasOwnProperty(key) &&
            //         key !== 'form_type' // exclude form_type
            //     ) {
            //         let label = labelMap[key] || key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
            //         let value = orderCustomer[key] ?? '-';
            //         customerHtml += `<b>${label}:</b> ${value}<br>`;
            //     }
            // }
           // Name
customerHtml += `<b>Name:  </b>` + ucwords(orderCustomer['first_name']) + ' ' + ucwords(orderCustomer['last_name']) + '<br />';

// Email
customerHtml += orderCustomer['email']
    ? `<b>Email: </b>` + orderCustomer['email'] + '<br />'
    : `<b>Email: </b> ""<br />`;

// Phone
customerHtml += `<b>Phone:  </b>` + orderCustomer['phone'];
customerHtml += orderCustomer['alternative_phone']
    ? ' / ' + orderCustomer['alternative_phone'] + '<br />'
    : '<br />';

// Address
const addr1 = ucwords(orderCustomer['address_line_one']);
const addr2 = orderCustomer['address_line_two'] ? ', ' + ucwords(orderCustomer['address_line_two']) : '';
customerHtml += `<b>Address:  </b>` + addr1 + addr2 + '<br />';

// City, State, PIN
customerHtml += `<b>City:  </b>` + ucwords(orderCustomer['city']) +
    '  <b>State:  </b>' + ucwords(orderCustomer['state']) +
    '  <b>PIN:  </b>' + (orderCustomer['pin'] ?? '') + '<br />';

// GST
customerHtml += orderCustomer['gst_number']
    ? `<b>GST Number: </b>` + orderCustomer['gst_number'] + '<br />'
    : `<b>GST Number: </b> ""<br />`;

// Inject into DOM
$('.invoice-col').html('<p class="lead">Customer Details:</p>' + customerHtml);


}

$(document).on('keyup', '#overAll_gst', function() {

    let excdGST = $(this).val(); 
    let subTotal = parseFloat($('#subtotal').attr('amount'));
    if( excdGST ){
        excdGST = parseFloat(excdGST); 
        
        

                let gst = excdGST*subTotal/100;
                let final_amount = subTotal+gst;
                final_amount = final_amount.toFixed(2);
            $('#final_amount').html('<i class="fas fa-rupee-sign"></i>' + final_amount); 

    }else{
            $('#final_amount').html('<i class="fas fa-rupee-sign"></i>' + subTotal);
    }

});


$(document).on('click', '.removeFromList', function() {
let getCode = $(this).data('id');

    if( confirm('Are you sure you want to delete this?') ){
        $('#prod_'+getCode).remove();
        let isEmpty = $('.addedPord').length;
        if(isEmpty < 1 ){
            $('.getProdDetails').addClass('d-none');
        }
    }
});
$(document).off('input', '.quantityCalc').on('input', '.quantityCalc', function() {
    const getCode = $(this).attr('data-code');
    
    if (!getCode) return; // guard

    // read inputs explicitly by ID to avoid reading wrong element
    const $qty = $('#quantity_' + getCode);
    const $sp = $('#selling_price_' + getCode);
    const $disc = $('#discount_percent_' + getCode);
    const $gst = $('#gst_' + getCode);
    const $amount = $('#amount_' + getCode);
    const editFrom = $(this).attr('edit-from') || '';
    // debug: ensure elements exist
    if (editFrom === 'I' && ( !$sp.length || !$qty.length || !$disc.length || !$gst.length || !$amount.length) ) {
        // console.warn('Missing element(s) for code:', getCode,
        //     'counts:', {
        //         sp: $sp.length, qty: $qty.length, disc: $disc.length, gst: $gst.length, amount: $amount.length
        //     });
        return;
    }

    // parse numbers safely
    const sp = Number($sp.val()) || 0;
    const qty = Number($qty.val()) || 0;
    const discPct = Number($disc.val()) || 0;
    const gstPct = Number($gst.val()) || 0;
    

    // calculations
    const totalBeforeDiscount = sp * qty;                 
    const discountAmount = totalBeforeDiscount * discPct / 100;
    const afterDiscount = totalBeforeDiscount - discountAmount;
    const gstAmount = afterDiscount * gstPct / 100;
    const finalTotal = +(afterDiscount + gstAmount).toFixed(2);

    // debug — remove or comment out when resolved
    // console.log('calc', getCode, {
    //     sp, qty, totalBeforeDiscount, discPct, discountAmount, afterDiscount, gstPct, gstAmount, finalTotal
    // });

    // set value
    $amount.val(finalTotal).attr('value', finalTotal);

    // update grand total when in 'R' mode
    if (editFrom === 'R') {
    
        let totalAmount = 0;
        $('.subtotal').each(function() {
            totalAmount += Number($(this).val()) || 0;
        });
        const extraGst = Number($('#extra_gst').attr('data-extra-gst')) || 0;
        const extraGstAmount = totalAmount * extraGst / 100;
        totalAmount = +(totalAmount + extraGstAmount).toFixed(2);
        let shippingChart = $('#deliveryChrg').attr('data-d-charges');
        shippingChart = Number(shippingChart);
        totalAmount = totalAmount+shippingChart;
        $('#totalAmount').text(totalAmount.toFixed(2));
    }
});


    $('.getProducInfo').on('keyup', function() {
        let query = $(this).val();
        //let DataFor = $(this).data('for');
         url = '/manage/findproduct';
         csrfToken = $('meta[name="csrf-token"]').attr('content');


        if (query.length >= 2) {
            $.ajax({
                url: url,
                headers: { 'X-XSRF-TOKEN' : csrfToken },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken );
                },
                type: "POST",
                data: { search: query },
                success: function(data) {
                    let list = '';
                    if(data != '0' ){
                            data.forEach(item => {
                                let pdName = ucwords(item.name);
                                list += `<li class="suggest-item">${pdName}</li>`;
                            });
                            $('#suggestionList').html(list).show();

                }
                    
                    
                }
            });
        } else {
            $('#suggestionList').hide();
        }
    });


    $(document).on('change', '#choosenProduct', function() {
    let query = $(this).val();

         url = '/manage/findproduct';
         csrfToken = $('meta[name="csrf-token"]').attr('content');
       
         if(query != '0'){
            let alreadyAdded = 0;
            $(".addedPord").each(function(index, element) {
                let alReadyInList = $(this).attr('prod-slug');
                if( query == alReadyInList) { alreadyAdded = 1; }
            });

           if(alreadyAdded == 0 ){ 
                $.ajax({
                    url: url,
                    headers: { 'X-XSRF-TOKEN' : csrfToken },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrfToken );
                    },
                    type: "POST",
                    data: { search: query },
                    success: function(data) {
                        if( data == '0' ){
                            // product qunatity less than 1
                            
                        }else{  
                            console.log(data);
                                $("#choosenProduct").val("0").trigger("change");

                            $('.getProdDetails').removeClass('d-none');
                            let sellingPrce = data.selling_price;
                            let buyingPrice = data.buying_price;
                            let gst= data.gst;

                            let makeForm = `
<div prod-slug="${query}" class="row addedPord" id="prod_${data.product_code}">
    <div class="col-md-12">
        <div class="product-order-wrap">
            <div class="row">

                <!-- Product Name -->
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="hidden" name="data[${data.product_code}][slug]" value="${data.slug}" />
                        <input type="text" name="data[${data.product_code}][name]" 
                               value="${ucwords(data.name)}" 
                               readonly="readonly" 
                               class="form-control" 
                               id="name_${data.product_code}" />
                    </div>
                </div>
                <!-- Buying Price -->
                <div class="col-md-2 d-none">
                    <input type="number" name="data[`+data.product_code+`][buying_price]" value="`+buyingPrice+`" readonly="readonly" class="form-control" id="buying_price_`+data.product_code+`" />
                </div>

                <!-- Selling Price -->
                <div class="col-md-2">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                            </div>
                            <input type="number" 
                                   name="data[${data.product_code}][selling_price]" 
                                   value="${sellingPrce}" 
                                   readonly="readonly" 
                                   class="form-control" 
                                   id="selling_price_${data.product_code}" />
                            <div class="input-group-append">
                                <span class="input-group-text">/pkt</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" 
                               max="${data.quantity}" 
                               data-code="${data.product_code}" 
                               name="data[${data.product_code}][quantity]"
                               value="0"
                               data-field="qnty"
                               class="form-control quantityCalc" 
                               edit-from="I"
                               id="quantity_${data.product_code}" />
                    </div>
                </div>

                <!-- GST -->
                
                <div class="col-md-1">
                    <div class="form-group">
                        <div class="input-group">
                            
                            <input type="number"
                               name="data[${data.product_code}][gst]"
                               value="${gst}" 
                               readonly="readonly"
                               class="form-control" 
                               id="gst_${data.product_code}" />
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                </div>

                

                <!-- Discount Percent -->
                <div class="col-md-2">
                    <div class="form-group">
                        <div class="input-group">
                            
                            <input type="number" 
                                   name="data[${data.product_code}][discount_percent]"
                                   data-code="${data.product_code}"
                                   value="0" 
                                   data-field="discountPercent"
                                   class="form-control quantityCalc" 
                                   step="0.000001"
                                   edit-from="I"
                                   id="discount_percent_${data.product_code}" />
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="col-md-2">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                            </div>
                            <input name="data[${data.product_code}][total]" 
                                   type="number" 
                                   value="0" 
                                   readonly="readonly" 
                                   class="form-control subtotal" 
                                   id="amount_${data.product_code}" />
                        </div>
                    </div>
                </div>

                <!-- Remove Button -->
                <div class="col-md-1">
                    <div class="form-group">
                        <button data-id="${data.product_code}" 
                                id="removeFromList_${data.product_code}" 
                                type="button"
                                class="form-control btn btn-danger removeFromList">
                            <i class="fas fa-trash text-white"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
`;


                            $('#saveAndCont').before(makeForm);
                            
                        }
                        
                        
                        
                    }
                });

            }else{
                var $target = $('[prod-slug="'+query+'"]');
                //scroll to the added one
                if ($target.length) {
                    $('html, body').animate({
                        scrollTop: $target.offset().top
                    }, 500);
                $("#choosenProduct").val("0").trigger("change");
                    $target.addClass('blink-effect');
                    setTimeout(function() {
                        $target.removeClass('blink-effect');
                    }, 2000);
                }
            }
        
        }

    });

    $('.scrollable').DataTable({ 
    
        scrollY: '400px',      // vertical scroll height
        scrollX: true,         // enable horizontal scroll
        scrollCollapse: true,
        lengthChange: false,
        pageLength: 10, 
        paging: false           // enable pagination
    });

    



    //console.log($('.reloadProd').val());
   // $('.goDownproductName').trigger('change');

   //$('.reloadProd').trigger('change');
});



$(document).on('submit', '#getProdDetails', function(e) {
  
     let isValid = true;
    e.preventDefault();
    // Remove previous error highlights
    $('.quantityCalc').removeClass('is-invalid');

    $('.quantityCalc').each(function() {
        let qty = parseFloat($(this).val());
        let fldCheck = $(this).attr('data-field');
        if ( (isNaN(qty) || qty <= 0) && fldCheck == 'qnty') {
            isValid = false;
            $(this).addClass('is-invalid'); // Bootstrap invalid style
        }
    });

    if (isValid) {
        // Proceed
            url = '/manage/save-order';
            csrfToken = $('meta[name="csrf-token"]').attr('content');
            let formData = $('#getProdDetails').serialize();

         $.ajax({
                url: url,
                headers: { 'X-XSRF-TOKEN' : csrfToken },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken );
                },
                type: "POST",
                data: formData ,
                success: function(data) {
                    
                    
                    switchTabs('step2');
                    
                    
                }
            });
    }
});
function switchTabs( activeId ){
    $('.switchTabs').addClass('d-none');
    $('#'+activeId).removeClass('d-none');
}


function formatDate(d) {
    return d.toLocaleDateString('en-GB').split('/').join('-'); // dd-mm-yyyy
}

 function getDateRange(_rangeOf){
        if(_rangeOf =='ALL'){
                const fromDate = new Date('2025-05-01'); // Start from 1st May 2025
                const toDate = new Date(); // Today's date
                // Optional: Format the dates as needed
                const formattedFrom = formatDate(fromDate);
                const formattedTo = formatDate(toDate);

                return { from: formattedFrom, to: formattedTo };

        }else if(_rangeOf =='PD'){
            const yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            const formatted = formatDate(yesterday);
             return { from: formatted, to: formatted };
        }else if(_rangeOf=='PW'){
            const today = new Date();
            const day = today.getDay(); // 0 (Sun) to 6 (Sat)
            const lastMonday = new Date(today);
            lastMonday.setDate(today.getDate() - day - 6);
            const lastSunday = new Date(today);
            lastSunday.setDate(today.getDate() - day - 0);
            return {
                from: formatDate(lastMonday),
                to: formatDate(lastSunday)
            };
        }else if(_rangeOf=='PM'){
            const date = new Date();
            date.setMonth(date.getMonth() - 1);

            const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
            const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

            return {
            from: formatDate(firstDay),
            to: formatDate(lastDay)
             };
        }
        
 }
$(document).on('change', '#report_of', function() {
        let slD = $(this).val();
        
        if(slD == 'DR' ){
            $('.dt-range').removeClass('d-none');
        }else if(slD == '' ){
            $('.datetimepicker-input').val('');
            $('.dt-range').addClass('d-none');
        }else{
            let fromTodate = getDateRange(slD);
            console.log(fromTodate.to);
           $('#date_from').val(fromTodate.from);
           $('#date_to').val(fromTodate.to);
           $('.dt-range').addClass('d-none');  
        }
    });

    // Submit protection
    $('#productAction').on('submit', function (e) {
        if (!$(this).valid()) {
            e.preventDefault();
        }
    });

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
  
$("#profile_image").change(function() {
       readURL(this);
});

// $(document).on("click", ".removeVendor", function(){ 

//     let _thisId = $(this).
// });
function removeVendor( thisId ){
    if(confirm( 'Are you sure you want to delete this ?')){
        $('#vendor_'+thisId).remove();
        let vendorNumber = $('.vendor-group').length;

        if( vendorNumber < 2 ){
             $('#bottomAddOption').addClass('d-none');
         }

        $('.vendorNumber').each(function(i) {
            let afterDlt = i+1;
            $(this).html('Vendor '+afterDlt );
        });

    }
}


//

var myWindowHght = $(window).height();
var _scrollYCalc = myWindowHght*167/323;
var _scrollY = _scrollYCalc - 70;

let columnsArray = [];



$(window).resize(function(){
        var myWindowHght = $(window).height();
        var _scrollYCalc = myWindowHght*167/323;
        var _scrollY = _scrollYCalc - 70;	
        $('.dataTables_scrollBody').css('max-height',_scrollY+'px');	 
});
function onld(){
    if ($('#tableOf').val() == 'G') {
    columnsArray = [
        { data: 'sl' },
        { data: 'damaged_date' },
        { data: 'name' },
        { data: 'quantity' },
        { data: 'URL' }
    ];
} else {
    columnsArray = [
       { data: 'sl' },
       { data: 'damaged_date' },
        { data: 'name' },
        { data: 'quantity' },
        { data: 'no_of_packet' },
        { data: 'URL' }
    ];
}
}
onld();
var damagedDataTbl = $('#damagedtbl').DataTable({
 
    dom: 'Bfrtip', // Add buttons at the top
    buttons: [
        {
            extend: 'excelHtml5',
            text: 'Export to Excel',
            title: 'Damaged items_',
            exportOptions: {
                columns: ':not(:last-child)' 
            }
        }
    ],

'processing': true,
'serverSide': true,
'serverMethod': 'post',
//"bStateSave": true,
'searching': false, // Remove default Search Control
'ajax': {
    
  //'url':'http://localhost/crb/api/contact.php',
    'url': '/admin/damaged-items',
    'headers': { 'X-XSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
    'beforeSend': function (xhr) {
        xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content') );
    },
    'error': function(xhr, error){
        
        $('.dataTables_processing').attr('style','display:none');
        alert('Session out. Please login again !');	
        
    },
   'data': function(d){
       
            d.for = $('#tableOf').val();
            d.name = $('#name').val();
            d.purchase_date_from = $('#purchase_date_from').val();
            d.purchase_date_to = $('#purchase_date_to').val();
      
   }
},
'columns': columnsArray,
'paging': true,
'pageLength': 150,
'scrollY':  _scrollY+"px",
'initComplete': function( settings, json ) {
       // redirectToppage();
       
 },
 "drawCallback": function () { // need to customize in simple_numbers_no_ellipses js file
             
  },
 
 
//'sPaginationType': "full_numbers",
"lengthChange": false,
"pagingType": "simple_numbers_no_ellipses",
'aaSorting' : [[1, 'desc']],
'columnDefs' : [
       { 'orderable' : false, 'targets' : 0},
       { 'orderable' : false, 'targets' : -1},
       { 'orderable' : false, 'targets' : 3},
       { 'orderable' : false, 'targets' : 4},

]
});
var dataTable = $('#kt_table_1').DataTable({
 dom: 'Bfrtip', // Add buttons at the top
    buttons: [
        {
            extend: 'excelHtml5',
            text: 'Export to Excel',
            title: 'Products Records _'+getCurrentTime(),
            exportOptions: {
                 columns: ':not(:last-child)' 
            }
        }
    ],

'processing': true,
'serverSide': true,
'serverMethod': 'post',
//"bStateSave": true,
'searching': false, // Remove default Search Control
'ajax': {
    
  //'url':'http://localhost/crb/api/contact.php',
    'url': 'productlist',
    'headers': { 'X-XSRF-TOKEN' : $('div[name="csrf-token"]').attr('content') },
    'beforeSend': function (xhr) {
        xhr.setRequestHeader('X-CSRF-Token', $('div[name="csrf-token"]').attr('content') );
    },
    'error': function(xhr, error){
        
        $('.dataTables_processing').attr('style','display:none');
        alert('Session out. Please login again !');	
        
    },
   'data': function(d){
       console.log(d);
            d.name = $('#name').val();
            //d.selling_price = $('#selling_price').val();
            d.category_id = $('#category_id').val();
      
   }
},
'columns': [
    //{ data: 'serial' }, 
    { data: 'name' }, 
    { data: 'packet' },
    { data: 'quantity' },
    { data: 'total' },
    { data: 'buying_price' },
    { data: 'selling_price' },
    { data: 'URL' }
    
],
'paging': true,
'pageLength': 150,
'scrollY':  _scrollY+"px",
'initComplete': function( settings, json ) {
       // redirectToppage();
       
 },
 "drawCallback": function () { // need to customize in simple_numbers_no_ellipses js file
             
  },
 
 
//'sPaginationType': "full_numbers",
"lengthChange": false,
"pagingType": "simple_numbers_no_ellipses",
'aaSorting' : [[0, 'asc']],
'columnDefs' : [
       { 'orderable' : false, 'targets' : -1},
       { 'orderable' : false, 'targets' : 1},
       { 'orderable' : false, 'targets' : 3}
] 
});

var dataTableVendorProdView = $('#basic_table_vendor_prd_view').DataTable({
    
  dom: 'Bfrtip', // Add buttons at the top
    buttons: [
        {
            extend: 'excelHtml5',
            text: 'Export to Excel',
            title: 'Vendor :'+ $('#vendorName').data('name') +'_'+getCurrentTime(),
            exportOptions: {
                 columns: ':not(:last-child)' 
            }
        }
    ],

'processing': true,
'serverSide': true,
'serverMethod': 'post',
//"bStateSave": true,
'searching': false, // Remove default Search Control
'ajax': {
    
     url: "/vendorprodlistview",
    'headers': {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    'beforeSend': function (xhr) {
        xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content') );
    },
    'error': function(xhr, error){
        
        $('.dataTables_processing').attr('style','display:none');
        alert('Session out. Please login again !');	
        
    },
   'data': function(d){
       console.log(d);
        d.slug = $('#vendor_slug').val();
             d.name = $('#name').val();
             d.purchase_date_from = $('#purchase_date_from').val();
             d.purchase_date_to = $('#purchase_date_to').val();
            // //d.selling_price = $('#selling_price').val();
            // d.category_id = $('#category_id').val();
      
   }
},
'columns': [
    //{ data: 'serial' }, 
    { data: 'sl' }, 
    { data: 'purchase_date' },
    { data: 'product_name' },
    { data: 'actual_price' },
    { data: 'quantity' },
    { data: 'gst' },
    { data: 'buying_price' }
    
    
],
'paging': true,
'pageLength': 150,
'scrollY':  _scrollY+"px",
'initComplete': function( settings, json ) {
       // redirectToppage();
       
 },
 "drawCallback": function () { // need to customize in simple_numbers_no_ellipses js file
             
  },
 
 
//'sPaginationType': "full_numbers",
"lengthChange": false,
"pagingType": "simple_numbers_no_ellipses",
'aaSorting' : [[1, 'desc']],
'columnDefs' : [
    //    { 'orderable' : false, 'targets' : -1},
    //    { 'orderable' : false, 'targets' : 1}
]
});

// $('#pin_charges_list').DataTable({
 
//         dom: 'Bfrtip', // Add buttons at the top
//         buttons: [
//             {
//                 extend: 'excelHtml5',
//                 text: 'Export to Excel',
//                 title: 'PIN_Changes',
//                 exportOptions: {
//                     columns: ':visible'
//                 }
                
//             }
//         ],
//              // vertical scroll height
//         scrollX: true,         // enable horizontal scroll
//         pageLength: 20, 
//         paging: false     
//     });

$('#searchFilter button').on('click', function () {
    dataTable.ajax.reload();
});
$('#searchGodownFilter button').on('click', function () {
    goDownProductList.ajax.reload();
});
$('#searchCustomer button').on('click', function () {
    customerDataTable.ajax.reload();
});

$('#searchDamangedGodownPrd button').on('click', function () {
    damagedDataTbl.ajax.reload();
});


$('#vendorProdSearch button').on('click', function () {
    dataTableVendorProdView.ajax.reload();
});

$('#searchOrder button').on('click', function () {
    OrderDataTable.ajax.reload();
});

$('#datetimepicker1').datetimepicker({
    format: 'DD-MM-YYYY',
    useCurrent: false
});

$('#order_date_picker').datetimepicker({
    format: 'DD-MM-YYYY H:s',
    time_24hr: true,
    maxDate: moment().endOf('day')  // allows today fully
});

$('#datetimepicker2').datetimepicker({
    format: 'DD-MM-YYYY',
  
});

// Optional: Prevent selecting "to date" before "from date"
$("#datetimepicker1").on("change.datetimepicker", function (e) {
    $('#datetimepicker2').datetimepicker('minDate', e.date);
});
$("#datetimepicker2").on("change.datetimepicker", function (e) {
    $('#datetimepicker1').datetimepicker('maxDate', e.date);
});


$('#filterBtnIndex').on('click', function (e) {
    //session put
     let searchdata = {
           value:{
                    name : $('#name').val(),
                    category_id : $('#category_id').val(),
                    vendor_id : $('#vendor_id').val()
            },
            mode:'W',
            key: 'prod-idx'

     }
    // return searchdata;
     $.ajax({
                url: "searchSession",
                headers: { 'X-XSRF-TOKEN' : $('div[name="csrf-token"]').attr('content') },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('div[name="csrf-token"]').attr('content') );
                },
                type: "POST",
                data: searchdata,
                success: function(data) {

                }
            });
            
});

$('#resetCustomerForm').on('click', function (e) {
    if( confirm('Are you sure you want to reset form ?')){
        document.getElementById("customerDetails").reset();
    }

});
$('#resetOrderSrch').on('click', function (e) {
    window.location.reload();

});
$('#resetCustomer').on('click', function (e) {
    window.location.reload();

});


$('#resetIndSrch').on('click', function (e) {
    let formId = $(this).data('form');
let searchdata = {
            mode:'D',
            key: 'prod-idx'
     }
     $.ajax({
                url: "searchSession",
                headers: { 'X-XSRF-TOKEN' : $('div[name="csrf-token"]').attr('content') },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('div[name="csrf-token"]').attr('content') );
                },
                type: "POST",
                data: searchdata,
                success: function(data) {
                    $('#'+formId).addClass('d-none');
                    window.location.reload();

                }
            });
});
function openSearchBox(_paramId){
        if( $('#'+_paramId).hasClass('d-none') ){
             $('#'+_paramId).removeClass('d-none');
        }else{
            $('#'+_paramId).addClass('d-none');
        }
    }

   

//     $(document).on('change', '.damagedGroupBy', function () {
//         let this_prod = $(this).val();
//         $('#insertData').html('');
//         if(this_prod){
//             $.ajax({
//                             url: "/admin/damagedsum",
//                             headers: {
//                                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                             },
//                             type: "POST",
//                             data: { filteredData: this_prod },
//                             success: function(data) {
//                                 let standard = '';
//                                 if(data.standard_conversion){
//                                     standard = '/'+data.standard_conversion;
//                                 }
//                                     let html = `<tr>
//                                         <td>`+data.name+`</td>
//                                         <td>`+data.quantiy+` `+data.unit+` `+standard+`</td>
                                    
                                    
//                                     </tr>`;
                                   
//                                     $('#primaryBox').addClass('d-none');
//                                     $('#filterBox').removeClass('d-none');
                                    
//                                     if ($.fn.DataTable.isDataTable('#basic_table_filter')) {
//                                         $('#basic_table_filter').DataTable().clear().destroy();
//                                     }
//  $('#insertData').html(html);
//                                     $('#basic_table_filter').DataTable({
//                                         paging: false,
//                                         searching: false,
//                                         info: false
//                                     });

//                             }
//                         });

//         }else{
//             $('#primaryBox').removeClass('d-none');
//             $('#filterBox').addClass('d-none');
//         }
//     });

    $(document).on('change', '.damagedPackagedGroupBy', function () {
        let this_prod = $(this).val();
        $('#insertData').html('');
        if(this_prod){
            $.ajax({
                            url: "/admin/packeddamagedsum",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            data: { filteredData: this_prod },
                            success: function(data) {
                                let standard = '';
                                if(data.standard_conversion){
                                    standard = '/'+data.standard_conversion;
                                }
                                    let html = `<tr>
                                        <td>`+data.name+`</td>
                                        <td>`+data.quantiy+` `+data.unit+` `+standard+`</td>
                                        <td>`+data.no_of_packet+`</td>
                                    
                                    
                                    </tr>`;
                                   
                                    $('#primaryBox').addClass('d-none');
                                    $('#filterBox').removeClass('d-none');
                                    
                                    if ($.fn.DataTable.isDataTable('#basic_table_filter')) {
                                        $('#basic_table_filter').DataTable().clear().destroy();
                                    }
                                    $('#insertData').html(html);
                                    $('#basic_table_filter').DataTable({
                                        paging: false,
                                        searching: false,
                                        info: false
                                    });

                            }
                        });

        }else{
            $('#primaryBox').removeClass('d-none');
            $('#filterBox').addClass('d-none');
        }
    });

    
$(document).on('change', '.fetchProdDetails', function () {
    let _thisProdId = $(this).val();
        if(_thisProdId ){
                $.ajax({
                                    url: "/admin/single-prod-data",
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: "POST",
                                    data: { query: _thisProdId },
                                    success: function(data) {
                                            console.log(data);
                                            console.log();
                                            $('#packet_size').val(data.packet_size+' '+data.unit.name);
                                            $('#quantity').attr({
                                                'max': data.quantity,
                                                'placeholder': 'Max Quantity '+ data.quantity
                                                });
                                    
                                    }
                                });
   
                }
});



    document.addEventListener('DOMContentLoaded', function () {
        const allDatepickerGroups = document.querySelectorAll('div[class*="search_datetimepicker_"]');

        allDatepickerGroups.forEach((div) => {
            const classList = Array.from(div.classList);
            const pickerClass = classList.find(cls => cls.startsWith('search_datetimepicker_'));
            if (pickerClass) {
                $(`.${pickerClass}`).datetimepicker({
                    format: 'DD-MM-YYYY',
                });
            }
        });
    });

    $(document).on('change', '.filterOrder', function () {
        let select_order_date = $('#select_order_date').val();
        let select_invoice_number = $('#select_invoice_number').val();
        $('.collapsed-card').removeClass('blink-effect');
//data-order-invoice
        if (select_order_date && select_invoice_number) {
                let target = $('[data-order-date="' + select_order_date + '"][data-order-invoice="' + select_invoice_number + '"]');
                
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80 // adjust for fixed header
                    }, 600);

                    target.addClass('blink-effect');
                } else {
                    console.warn('No matching order card found.');
            }
        }else if(select_order_date && !select_invoice_number) {
            var target = $('[data-order-date="'+select_order_date+'"]');
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 600); // 600ms animation
                target.addClass('blink-effect');
        }else if(!select_order_date && select_invoice_number) {
            var target = $('[data-order-invoice="'+select_invoice_number+'"]');
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 600); // 600ms animation
                target.addClass('blink-effect');
        }
    });
   $('.vendor_prod_update').on('click', function(){
        var thisBtn = $(this);
        //
        let getFrmId = $(this).attr('vendor-list-id');

        let original_PD = $('.pd_'+getFrmId).attr('data-original');
        let original_QT = $('.qt_'+getFrmId).attr('data-original');
        let original_UT = $('.ut_'+getFrmId).attr('data-original');
        let original_GST = $('.gst_'+getFrmId).attr('data-original');
        let original_AP = $('.ap_'+getFrmId).attr('data-original');
        let original_BP = $('.bp_'+getFrmId).attr('data-original');

        let original_UT_NAME = $('.ut_'+getFrmId).attr('data-original-name');

        //changes
        let changes_PD = $('.pd_'+getFrmId).val();
        let changes_QT = $('.qt_'+getFrmId).val();
        let changes_UT = $('.ut_'+getFrmId).val();
        let changes_GST = $('.gst_'+getFrmId).val();
        let changes_AP = $('.ap_'+getFrmId).val();
        let changes_BP = $('.bp_'+getFrmId).val();
        let changes_UT_NAME = $('.ut_'+getFrmId+' option:selected').text();


        

        let countChanges = 0;
        let detectChanges = [];

        if( original_PD != changes_PD){
            countChanges +=1;
            detectChanges.push('Purchase Date from '+original_PD+' to '+ changes_PD);
        }
         if( original_QT != changes_QT){
            countChanges +=1;
            detectChanges.push('Quantity from '+original_QT+' to '+ changes_QT);
        }
         if( original_BP != changes_BP){
            countChanges +=1;
            detectChanges.push('Buying Price from '+original_BP+' to '+ changes_BP);
        }
        if( original_AP != changes_AP){
            countChanges +=1;
            detectChanges.push('Actual Price from '+original_AP+' to '+ changes_AP);
        }
        if( original_UT != changes_UT){
            countChanges +=1;

            detectChanges.push('Unit from '+original_UT_NAME+' to '+ changes_UT_NAME);
        }
        if( original_GST != changes_GST){
            countChanges +=1;
            detectChanges.push('GST from '+original_GST+' to '+ changes_GST);
        }
       

        if(countChanges > 0 ){
            let msg = 'Do you want to change these : '+detectChanges.toString();
            if(confirm(msg)){
                    //ajax submit
                    let _data = {
                        id: getFrmId,
                        purchase_date: changes_PD,
                        buying_price: changes_BP,
                        quantity: changes_QT,
                        unit_id: changes_UT,
                        unit_name: changes_UT_NAME,
                        actual_price: changes_AP,
                        gst: changes_GST


                    }
                    if(updateVendor(_data , thisBtn)){
                        
                    }
            }else{
                     $('.pd_'+getFrmId).val(original_PD);
                     $('.bp_'+getFrmId).val(original_BP);
                     $('.ap_'+getFrmId).val(original_AP);
                     $('.qt_'+getFrmId).val(original_QT);
                     $('.ut_'+getFrmId).val(original_UT);
                     $('.gst_'+getFrmId).val(original_GST);
                      
            }
        }else{
             
            alert( 'It looks like you didn’t make any changes.') 
        }
   });

   function updateVendor( dataToUpdate , thisBtn){
        thisBtn.text('Processing...');
            $.ajax({
                        url: "/admin/updateproductvnd",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: { update: dataToUpdate },
                        success: function(data) {
                            if(data == '1' ){
                            let getFrmId = dataToUpdate.id;
                            //alert(getFrmId);
                                $('.pd_'+getFrmId).attr('data-original',dataToUpdate.purchase_date).val(dataToUpdate.purchase_date).attr('value',dataToUpdate.purchase_date);
                                $('.qt_'+getFrmId).attr('data-original',dataToUpdate.quantity).val(dataToUpdate.quantity).attr('value',dataToUpdate.quantity);
                                $('.ap_'+getFrmId).attr('data-original',dataToUpdate.actual_price).val(dataToUpdate.actual_price).attr('value',dataToUpdate.actual_price);
                                $('.gst_'+getFrmId).attr('data-original',dataToUpdate.gst).val(dataToUpdate.gst).attr('value',dataToUpdate.gst);
                                $('.bp_'+getFrmId).attr('data-original',dataToUpdate.buying_price).val(dataToUpdate.buying_price).attr('value',dataToUpdate.buying_price);

                                // $('.ut_'+getFrmId).attr('data-original',dataToUpdate.unit_id).val(dataToUpdate.unit_id)
                                // .attr('value',dataToUpdate.selling_price);
                                $('.ut_'+getFrmId).attr('data-original',dataToUpdate.unit_id).attr('data-original-name',dataToUpdate.unit_name).val(dataToUpdate.unit_id);

                                thisBtn.text('Updated');
                                alert('Information has been updated successfully.');
                               
                            }else if(data == '2' ){

                                thisBtn.text('Update');
                                alert('The quantity you are trying to change is not available in the godown. It is less than the amount you have entered.');
                            }else{
                                thisBtn.text('Update'); alert('Something went wrong!');
                            }
                           
                        }
                    });
   }
function confirmOrder(){
    if(confirm( 'Are you sure you want to palce this order? ') ){
        $('#confirmOrder').
        text('Processing...').
        prop('disabled',true).attr('disabled','disabled');
        let overAllGst = parseFloat($('#overAll_gst').val());

         $.ajax({
                        url: "/manage/place-order",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: { extra_gst: overAllGst },
                        success: function(res) {
                        
                            $('#confirmOrder').text('Place Order').prop('disabled',false).removeAttr('disabled');
                           
                            if (res.status === 'OK') {
                                        window.location.href = '/manage/thank-you/'+res.msg;
                                        //$('#ajaxRespnMsg').html();
                                } else if (res.status === 'NV') {
                                    let msg= `<p class="text text-danger">All required fields must be filled.`+res.errors+`</p>`;
                                    $('#ajaxRespnMsg').html(msg);
                                     console.log('Validation Failed:', res.errors);
                                }
                                else if( res.status === 'ERROR'){
                                    let msg= `<p class="text text-danger">Invalid Order</p>`;
                                    $('#ajaxRespnMsg').html(msg);
                                     console.log('Order Failed:', res.errors);
                                }else if( res.status === 'ERROR-1'){
                                    let msg= `<p class="text text-danger">`+res.msg+`</p>`;
                                    $('#ajaxRespnMsg').html(msg);
                                     console.log('Order Failed:', res.errors);
                                }
                           
                        }
                    });


    }
}

// order table
var OrderDataTable = $('#kt_table_order').DataTable({

'processing': true,
'serverSide': true,
'serverMethod': 'post',
//"bStateSave": true,
'searching': false, // Remove default Search Control
'ajax': {
    
  //'url':'http://localhost/crb/api/contact.php',
    'url': '/manage/orderlist',
    'headers': { 'X-XSRF-TOKEN' : $('div[name="csrf-token"]').attr('content') },
    'beforeSend': function (xhr) {
        xhr.setRequestHeader('X-CSRF-Token', $('div[name="csrf-token"]').attr('content') );
    },
    'error': function(xhr, error){
        
        $('.dataTables_processing').attr('style','display:none');
        alert('Session out. Please login again !');	
        
    },
   'data': function(d){
       console.log(d);
            d.name = $('#name').val();  
            d.ordered_from = $('#ordered_from').val();      // input[name="name"]
            d.date_from = $('#date_from').val();     // input[name="email"]
            d.date_to = $('#date_to').val();     // input[name="email"]
            d.phone = $('#phone').val();     // input[name="phone"]
            d.invoice_number = $('#invoice_number').val(); // input[name="order_id"]
      
   }
},
'columns': [
        { data: 'ordered_from', name: 'ordered_from' },
        { data: 'order_date', name: 'order_date' },
        { data: 'first_name', name: 'first_name' },
        { data: 'last_name', name: 'last_name' },
        { data: 'email', name: 'email' },
        { data: 'phone', name: 'phone' },
        { data: 'invoice_number', name: 'invoice_number' },
        { data: 'URL' }
    
],
'paging': true,
'pageLength': 150,
'scrollY':  _scrollY+"px",
'initComplete': function( settings, json ) {
       // redirectToppage();
       
 },
 "drawCallback": function () { // need to customize in simple_numbers_no_ellipses js file
             
  },
 
 
//'sPaginationType': "full_numbers",
"lengthChange": false,
"pagingType": "simple_numbers_no_ellipses",

'aaSorting' : [[6, 'desc']],
'columnDefs' : [
       { 'orderable' : false, 'targets' : -1}
]
});


// customer table
var customerDataTable = $('#kt_table_customer').DataTable({
 

'processing': true,
'serverSide': true,
'serverMethod': 'post',
//"bStateSave": true,
'searching': false, // Remove default Search Control
'ajax': {
    
  //'url':'http://localhost/crb/api/contact.php',
    'url': '/admin/customerlist',
    'headers': { 'X-XSRF-TOKEN' : $('div[name="csrf-token"]').attr('content') },
    'beforeSend': function (xhr) {
        xhr.setRequestHeader('X-CSRF-Token', $('div[name="csrf-token"]').attr('content') );
    },
    'error': function(xhr, error){
        
        $('.dataTables_processing').attr('style','display:none');
        alert('Session out. Please login again !');	
        
    },
   'data': function(d){
       console.log(d);
            d.name = $('#name').val();       // input[name="name"]
            d.date_from = $('#date_from').val();     // input[name="email"]
            d.date_to = $('#date_to').val();     // input[name="email"]
            d.phone = $('#phone').val();     // input[name="phone"]
      
   }
},
'columns': [
        { data: 'first_name', name: 'first_name' },
        { data: 'last_name', name: 'last_name' },
        { data: 'email', name: 'email' },
        { data: 'phone', name: 'phone' },
        { data: 'URL' }
    
],
'paging': true,
'pageLength': 150,
'scrollY':  _scrollY+"px",
'initComplete': function( settings, json ) {
       // redirectToppage();
       
 },
 "drawCallback": function () { // need to customize in simple_numbers_no_ellipses js file
             
  },
 
 
//'sPaginationType': "full_numbers",
"lengthChange": false,
"pagingType": "simple_numbers_no_ellipses",
'aaSorting' : [[0, 'asc']],
'columnDefs' : [
       { 'orderable' : false, 'targets' : -1}
]
});


var goDownProductList = $('#kt_table_goDownProd').DataTable({
 
    dom: 'Bfrtip', // Add buttons at the top
    buttons: [
        {
            extend: 'excelHtml5',
            text: 'Export to Excel',
            title: 'Godown Products_'+getCurrentTime(),
            exportOptions: {
                 columns: ':not(:last-child)' 
            }
        }
    ],

'processing': true,
'serverSide': true,
'serverMethod': 'post',
//"bStateSave": true,
'searching': false, // Remove default Search Control
'ajax': {
    
  //'url':'http://localhost/crb/api/contact.php',
    'url': '/admin/godownprodlist',
    'headers': { 'X-XSRF-TOKEN' : $('div[name="csrf-token"]').attr('content') },
    'beforeSend': function (xhr) {
        xhr.setRequestHeader('X-CSRF-Token', $('div[name="csrf-token"]').attr('content') );
    },
    'error': function(xhr, error){
        
        $('.dataTables_processing').attr('style','display:none');
        alert('Session out. Please login again !');	
        
    },
   'data': function(d){
       console.log(d);
            d.name = $('#name').val();
            d.category_id = $('#category_id').val();
            d.purchase_date_from = $('#purchase_date_from').val();
            d.purchase_date_to = $('#purchase_date_to').val();
      
   }
},
'columns': [
    //{ data: 'serial' }, 
    { data: 'name' }, 
    { data: 'category_id' },
    { data: 'in_godown' },
    { data: 'in_packet' },
    { data: 'total' },
    { data: 'URL' }
    
],
'paging': true,
'pageLength': 150,
'scrollY':  _scrollY+"px",
'initComplete': function( settings, json ) {
       // redirectToppage();
       
 },
 "drawCallback": function () { // need to customize in simple_numbers_no_ellipses js file
             
  },
 
 
//'sPaginationType': "full_numbers",
"lengthChange": false,
"pagingType": "simple_numbers_no_ellipses",
'aaSorting' : [[0, 'asc']],
'columnDefs' : [
       { 'orderable' : false, 'targets' : -1},
       { 'orderable' : false, 'targets' : -2},
       { 'orderable' : false, 'targets' : 2},
       { 'orderable' : false, 'targets' : 3}
]
});




$(document).on('change', '#unit_category_id', function () {
    let selectedCategory = $(this).val();

    $('#parent_id option').each(function () {
        let unitCat = $(this).attr('unit-cat');

        if (!unitCat) {
            $(this).show();
            return;
        }
        if (unitCat === selectedCategory) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });

    // Optional: Reset the selection to Base Unit
    $('#parent_id').val('');
});
 $(document).on('change', '.suggestBuyingPrice', function () {
    let thisVal =$(this).val();
    $('#psgst').addClass('d-none');
    $('#suggestedPrices').html('');
    $('.showSuggestion').addClass('d-none');
    $('#availableQnt').html('');
    if(thisVal){
        let url = '/admin/product-bp-suggestion';
        let csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: url,
            headers: { 'X-XSRF-TOKEN': csrfToken },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', csrfToken);
            },
            type: "POST",
            data: { search: thisVal },
            success: function(data) {
                    $('#psgst').removeClass('d-none');
                    let prices = `
                        <span class=""><b>Lowest :</b> `+data.min.s+` ( `+data.min.b+`) : `+data.min.p_date+`</span>
                        <span class=""><b>Highest :</b> `+data.max.s+` ( `+data.max.b+`) : `+data.max.p_date+`</span>
                        <span class=""><b>Average :</b> `+data.avrg.standard+` ( `+data.avrg.base+`) </span>
                    `;
                    $('#suggestedPrices').html(prices);
                    $('.showSuggestion').removeClass('d-none');
                    $('#availableQnt').html(` <span class="">`+
                        data.after_pack_go_down+`</span>`);
                
             }
        });

    }

 });

     $(document).on('change', '.goDownproductName', function () {
  let selectedUnitCat = $(this).find('option:selected').attr('unit-cat');
   $('#unit_id').val('');
    $('#unit_id option').each(function () {
       
        let unitCatId = $(this).attr('unit-cat-id');
        console.log(unitCatId);
        
        if (!unitCatId) {
            $(this).show();
            return;
        }
        if (unitCatId === selectedUnitCat) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });

});

$(document).on('change', '.mainUnitCatId', function () {
    let selectedUnitCat = $(this).val();
   if(selectedUnitCat){
            if(confirm('Make sure this product belongs to the selected type. You will not be able to edit it after the product is saved')){
                $('.unit_id').each(function () {
                    let hasMatchingOption = false;
                    let $select = $(this); // reference to the select box

                    // Show/hide options based on selectedUnitCat
                    $select.find('option').each(function () {
                        let unitCatId = $(this).attr('main-unit-id');

                        if (!unitCatId) {
                            $(this).show(); // always show the "Select Unit" option
                            return;
                        }

                        if (unitCatId === selectedUnitCat) {
                            $(this).show();
                            hasMatchingOption = true;
                        } else {
                            $(this).hide();
                        }
                    });

                    // Reset the value if the selected option is now hidden
                    if (!$select.find('option:selected').is(':visible')) {
                        $select.val('');
                    }

                    // Enable or disable the select based on available matching options
                    $select.prop('disabled', !hasMatchingOption);
                });
            }else{
                
                $('#unit_category_id').val('').trigger('change');

            }
    }
});



 $(document).on('keyup', '.buyingpcCalc', function () {
    let indexId = $(this).attr('unique-id');
    console.log(indexId);
    let getQuant = parseFloat($('#stock_quantity_'+indexId).val());
    let getGst = parseFloat($('#gst_'+indexId).val());
    let getAP = parseFloat($('#actual_price_'+indexId).val());

    let MakeBuyingP = getQuant * getAP * ( 100 + getGst )/ 100;
    MakeBuyingP = parseFloat(MakeBuyingP).toFixed(2);
    //console.log(MakeBuyingP);
    if(MakeBuyingP) $('#buying_price_'+indexId).val(MakeBuyingP);

 });

  $('#returnFormBtn').on('click', function(){
    if( confirm( 'Are you sure you want to change this order ? ')){
        if ($('#OrderReturnForm').valid()) {
         $('#OrderReturnForm')[0].submit();
     }
    }
  });
$('#cancelOrder').on('click', function(){
    if( confirm( 'Are you sure you want to cancel entire order ? ')){
       $(this).text('Canceling Order...') ;
        $(this).attr('disabled','disabled');
        $('.quantityCalc').each(function () {
            $(this).val(0);
            $(this).attr('readonly',true);
            $(this).trigger('keyup');
            $('#action_type').val('C');
         });
          $('#OrderReturnForm')[0].submit();
    }

});
  

 $(document).on('click', '#customExportBtn', function () {

    let _thisOf = $(this).attr('of');
    if( _thisOf == 'ORD' ){
        const query = new URLSearchParams({
                report_type : _thisOf,
                date_from : $('#date_from').val(),
                date_to : $('#date_to').val(),
                phone : $('#phone').val(),
                name : $('#name').val(),
                ordered_from : $('#ordered_from').val(),
                invoice_number : $('#invoice_number').val(),
        });


       window.open('/admin/create-report?' + query.toString(), '_blank');
    }    

 });

function normalizeDateTimeDMYHi(input) {
    const match = input.match(/^(\d{1,2})-(\d{1,2})-(\d{4}) (\d{1,2}):(\d{1,2})$/);
    if (!match) return input;

    let d = String(match[1]).padStart(2, '0');
    let m = String(match[2]).padStart(2, '0');
    let y = match[3];
    let h = String(match[4]).padStart(2, '0');
    let i = String(match[5]).padStart(2, '0');

    return `${d}-${m}-${y} ${h}:${i}`;
}


$('#order_date_pck').on('blur', function () {
    const formatted = normalizeDateTimeDMYHi($(this).val());
    $(this).val(formatted);
    $('#order_date_pck').removeClass('is-invalid');
});


 $(document).on('click', '#triggerForm', function () {
    if( confirm('Are you sure you want to upload PIN Code & its charges file ?')){
        $('#excel_file').trigger('click');
    }
 });
 $(document).on('change', '#excel_file', function () {
    $('#uploadPinCharge').submit();
 });

 $(document).on('click', '#triggerUploadImage', function () {
        $('#zipFile').trigger('click');
    
 });
 $(document).on('change', '#zipFile', function () {
    $('#uploadImgForm').submit();
 });
 $(document).on('click', '.copyBtn', function () {

        let text = $(this).attr('text-to-copy');
        let hasId = $(this).attr('data-id');

        // Using Clipboard API
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function () {
                if(hasId){
                     $('#'+hasId).text('Copied');
                }else{
                     alert('Copied:');
                }
                    
               
            }).catch(function (err) {
                alert('Failed to copy: ' + err);
            });
        } else {
            // Fallback for older browsers
            let tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(text).select();
            document.execCommand('copy');
            tempInput.remove();
            alert('Copied (fallback): ' + text);
        }
    });

 $(document).on('click', '#removeImage', function () {
     if( confirm('Are you sure you want to delete this record ?')){

             let url = '/admin/pages/delete-image';
             let csrfToken = $('meta[name="csrf-token"]').attr('content');
             let imageName = $(this).attr('data-name');
             let tbl = $(this).attr('tbl');
             let id = $(this).attr('data-id');
           
            $.ajax({
                url: url,
                headers: { 'X-XSRF-TOKEN' : csrfToken },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken );
                },
                type: "POST",
                data: { search: imageName ,tbl:tbl ,id:id},
                success: function(data) {
                     window.location.reload();
                }
            });

        
     }
 });

  $(document).on('input', '#add_more_qnt', function () {
    let getNewQnt = 0;
    if( $('#add_more_qnt').val() > 0  ){
        getNewQnt = Number($('#add_more_qnt').val()) || 0;
    }
    let orginlQnt = Number($('#current_packet_qunatity').val()) || 0 ;
    let newVal = getNewQnt + orginlQnt;
    $('#quantity').val(newVal);

  });
   function removeGallerImage( imageName , id){
     let url = '/admin/delete-gallery';
             let csrfToken = $('meta[name="csrf-token"]').attr('content');
        if( confirm ( 'Are you sure you want to permanently delete this image ?'))   {

            $.ajax({
                url: url,
                headers: { 'X-XSRF-TOKEN' : csrfToken },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken );
                },
                type: "POST",
                data: { filename: imageName },
                success: function(data) {

                    if(data.success === 'Y'){
                        $('#imageg_'+id).remove();
                    }else
                    {
                        alert('Image not deleted');
                    }
                    
                }
            });
        }
        
  }