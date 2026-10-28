const contactFrom = $('#contactform').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            name: { required: true },
            email: { required: true , email:true},
            subject: {  maxlength:200},
            message: { required: true , maxlength: 700 },
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
       
    });
 
$(document).ready(function () {
    let noofProdInList = $('.prodList').length;
    $('#showing_prod_number').text(noofProdInList);
     
    cartItemCounter();
    
    //get Pincode from local storage
    let hasVerifiedPin = managePinCode('R');
    

    //let path = window.location.pathname;

    // if ( !hasVerifiedPin ) {
    //    $(".popup-overlay").fadeIn();
    // }

}); //end of onload func
function managePinCode( _action , val =null )
{
   
     let key = 'boomija_pin_code';
     if(_action == 'W' ){
          return localStorage.setItem(key,val);
     }else{
        let pincode = localStorage.getItem(key);
        if(  ! $('#header_left_items').hasClass('user_loggedin_active') ){
            $('body').find('#user_pincode').val(pincode);
        }

        return pincode;
     }
    
}

function cartItemCounter(){
    let url = '/ajaxcall';
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    let dataToPass = {
            type:'CART-COUNT'
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
                   
               
                $('#catProdQnt').text(res.count);
                if(res.count > 0 ){
                    $('#catProdQnt').addClass('shake');
                    setTimeout(function () {
                        $('#catProdQnt').removeClass('shake');
                    },800); 
                }

                $('#wishlistQnt').text(res.wishListcount);
                
                
        }
    });
}

function ajaxController(dataToPass, callback) {
    let url = '/ajaxcall';
    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: url,
        headers: { 'X-XSRF-TOKEN': csrfToken },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', csrfToken);
        },
        type: "POST",
        data: { data: dataToPass },
        success: function (res) {
            callback({
                response: res.status,
                data: res.data
            });
        }
    });
}

function makeProdHtml( data ){
    //console.log(data);
    
    const URL = $('body').data('url');
    let imagePath = URL+'/storage/website-image/no-image.png';
    if(data.image != null && data.image != ''){
        imagePath = URL+'/storage/products/'+data.image;
    }
    
    const slug = URL+'/product-details/'+data.slug;

    return  `<li id="product_${data.product_code}" class="prodList">
                <div class="image_frame scale-with-grid product-loop-thumb">
                    <div class="image_wrapper">
                        <div class="mask"></div>
                        <img width="600" height="500" src="${imagePath}" class="scale-with-grid wp-post-image" alt="${data.name}" sizes="(max-width: 600px) 100vw, 600px">
                        <div class="image_links double">
                            <a href="javascript:void(0);" class="link" onclick="addTocart('${data.product_code}','1','${data.selling_price}')">
                                <i class="icon-basket"></i>
                            </a>
                            <a class="link" href="${slug}">
                                <i class="icon-link"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="desc">
                    <h4 style="margin-bottom: 0px;">
                        <a href="${slug}">
                            ${data.name}
                        </a>
                    </h4>
                    <span class="price">
                        <span class="woocommerce-Price-amount amount">
                            <span class="woocommerce-Price-currencySymbol">₹</span>${data.selling_price}</span>
                </span></div>
            </li>`;
}

$(document).on('change', '#productFilter', function () {
    
    let getSortVal = $(this).val();
    let selectedCatSlug = $('.sidebar').find('.active_category').attr('data-slug');
    let selectedPriceRange = $('.sidebar').find('.active_price_range').attr('data-range');
    


    let dataPass = {
        type: 'PROD-SORT',
        //query: getSortVal
        query: {
            sort_val : getSortVal,
            cat_slug : selectedCatSlug,
            price_range: selectedPriceRange
        }
    };


  // console.log(dataPass); return false;

    ajaxController(dataPass, function(getRecords) {
        // console.log(getRecords.response);
        // console.log(getRecords.data);

        if( getRecords.response == 'Y' ){
            if(getRecords.data.length > 0 ){
                $('#productsContainer').html('').removeClass('d-none');
                $('#ajaxResponse').remove();
                
                $('#showing_prod_number').text(getRecords.data.length);
                
                $.each(getRecords.data, function (key, val) {
                    let prd = makeProdHtml(val);
                    $('#productsContainer').append(prd);
                });

            }
            
        }
    });
});
$(document).on('click', '.sortBycategory', function () {

    let selectedCatSlug = $(this).attr('data-slug');
    let catName = $(this).text();
    $('.sortBycategory').each(function(){
        $(this).removeClass('active_category');
    });
    $(this).addClass('active_category');
    let sortedVal = $('#productFilter').find(':selected').val();
    let selectedPriceRange = $('.sidebar').find('.active_price_range').attr('data-range');

    console.log(sortedVal,selectedCatSlug);
    let dataPass = {
        type: 'PROD-SORT',
        query: {
            sort_val : sortedVal,
            cat_slug : selectedCatSlug,
            price_range: selectedPriceRange
        }
    };
    let htmlFmdata = {cat_name: catName, cat_slug: selectedCatSlug , price_range:selectedPriceRange }
    ajaxController(dataPass, function(getAllRecords) {
        console.log(getAllRecords);
          htmlFormation(getAllRecords , htmlFmdata);
    });
    
    
});
$(document).on('click', '.data_range', function () {
    $('.sidebar').find('.data_range').removeClass('active_price_range');
    $(this).addClass('active_price_range');

    let selectedPriceRange = $(this).attr('data-range');
    let selectedCatSlug = $('.sidebar').find('.active_category').attr('data-slug');
    let sortedVal = $('#productFilter').find(':selected').val();
    let catName = $('.sidebar').find('.active_category').text();
    let dataPass = {
        type: 'PROD-SORT',
        query: {
            sort_val : sortedVal,
            cat_slug : selectedCatSlug,
            price_range: selectedPriceRange
        }
    };

   // console.log(dataPass); return false;
    let htmlFmdata = {cat_name: catName, cat_slug: selectedCatSlug , price_range:selectedPriceRange }
     ajaxController(dataPass, function(getAllRecords) {
        htmlFormation(getAllRecords , htmlFmdata);
    });

});

function htmlFormation(getRecords ,htmlFmdata){
    
        console.log(htmlFmdata);
        
            $('#productsContainer').html('');
            $('#ajaxResponse').remove();
         if( getRecords.response == 'Y' ){
            if(getRecords.data.length > 0 ){
                
                $('#productsContainer').html('').removeClass('d-none');
                 $('#showing_prod_number').text(getRecords.data.length);
                
                $.each(getRecords.data, function (key, val) {
                    let prd = makeProdHtml(val);
                    $('#productsContainer').append(prd);
                });

            }else{
                $('#showing_prod_number').text(0);
                $('#productsContainer').addClass('d-none').after(`<div id="ajaxResponse" class="server_response_msg">
                    <p class="text text-center"> Oops! No product found. </p>
                </div>`);
            }
            
        }else{
            $('#productsContainer').addClass('d-none').after(`<div id="ajaxResponse" class="server_response_msg">
                    <p class="text text-center"> Oops! No product found. (`+getRecords.response+`)</p>
                </div>`);
        }

        $('html, body').animate({
            scrollTop: $('.entry-content').offset().top
        }, 500);
        var priceBrdcmbs = '';
        var catBrdcmbs = '';
        let c=0;

        let catBrdCrumb = $('#productsContainer').attr('specific-cat');
        if (htmlFmdata && htmlFmdata.cat_slug !== undefined && catBrdCrumb == 'N') {
            catBrdcmbs = '<li id="cat_brdcmb_'+htmlFmdata.cat_slug+'" class="mr-5 brdlist">&nbsp;<small>'
           + htmlFmdata.cat_name
           + '</small><a class="text text-danger danger" onclick="removeBrdcm(\'cat_brdcmb_'+htmlFmdata.cat_slug+'\', \''+htmlFmdata.cat_slug+'\', \'cat\');">X</a></li>';
            c+=1;
        }
        if (htmlFmdata && htmlFmdata.price_range !== undefined) {
            priceBrdcmbs = ' <li id="brdcmb_' + htmlFmdata.price_range + '" class="brdlist">&nbsp;<small>' 
             + htmlFmdata.price_range + '</small>'
             + '<a class="text text-danger danger" '
             + 'onclick="removeBrdcm(\'brdcmb_' + htmlFmdata.price_range + '\', \'' 
             + htmlFmdata.price_range + '\', \'price\');">X</a></li>';
            c+=1;
        }

       $('#brdcrmbs').remove();
       let separator = '';
       if(c==2) {  separator = '<li class="sptr"> > </li>'; }
       console.log(c);
       
        $('.shop-filters').after(`
            <div class="shop-filters mt-3" id="brdcrmbs">
                <p class="woocommerce-result-count">
                    <ul class="flterLst">
                        ${catBrdcmbs}  ${separator} ${priceBrdcmbs}    
                    </ul>
                              
                </p>
            </div>
            
            `);
}
function removeBrdcm( _id , slug ,fromWhere){
    let id = $.trim(_id);
    $('#'+id).remove();
    $('.sptr').remove();
    $('#ajaxResponse').remove();
        if( fromWhere == 'cat'){
                $('.sortBycategory').each(function(){
                    if( $(this).attr('data-slug') == slug ){
                        $(this).removeClass('active_category');
                    }
                    
                });
        }else if( fromWhere == 'price'){
            $('.data_range').each(function(){
                if( $(this).attr('data-range') == slug ){
                    $(this).removeClass('active_price_range');
                }
            });
        }
     let sortedVal = $('#productFilter').find(':selected').val();
     $('#productFilter').val(sortedVal).trigger('change'); //ajax calling
    let brdlist = $('.brdlist').length ;
    if(brdlist == 0 ){
        $('#brdcrmbs').remove();
    }
}

function addTocart(productCode, qty, price) {
    //console.log(productCode, qty, price) ;
    
    let qnt = Number(qty);
    // Validate qty (not blank, not zero, not negative, and is a number)
    if (!qty || isNaN(qnt) || qnt <= 0) {
        console.warn("Invalid quantity! Must be greater than 0.");
        return; // Stop execution
    }

    //console.log(productCode, qty, price) ;
    if(qnt > 0 ){
            let url = '/ajaxcall';
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            let dataToPass = {
                    product_code: productCode,
                    quantity: qty,
                    price: price,
                    type:'CART'
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
                        if (res.status === 'success') {
                            $('#catProdQnt').text(res.count);
                            $('#catProdQnt').addClass('shake');
                                setTimeout(function () {
                                    $('#catProdQnt').removeClass('shake');
                                },300); 
                        }
                }
            });
        }

}

function addtoWishlist( productCode ,isAdd){
    if( $('#header_left_items').hasClass('user_loggedin_active') ){
                let url = '/ajaxcall';
                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                let dataToPass = {
                        product_code: productCode,
                        is_add : isAdd,
                        type:'WISHLIST'
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
                        if (res.status === 'success') {
                            if( isAdd == 'N' ){
                                $('.wishlistBtn').attr('style','');
                            }else if( isAdd == 'Y' ){
                                $('.wishlistBtn').attr('style','background-color: #5c762f !important; color: #fff !important;');
                            }
                            $('#wishlistQnt').text(res.count);
                            $('#wishlistQnt').addClass('shake');
                                setTimeout(function () {
                                    $('#wishlistQnt').removeClass('shake');
                                },300); 
                        }
                }
            });
    
    
    }else{
        alert('Please login first');
    }

}


$(document).off('input', '.quantityCalc').on('input', '.quantityCalc', function() {
    $('#ajax_response').html('')
    let getCode = $(this).attr('data-code');
    let $qty = $(this).val();
    let $sp =  $(this).attr('data-sp');
    let $gst = $(this).attr('data-gst');

    $(this).removeClass('is-invalid');

    let sp = Number($sp) || 0;
    let qty = Number($qty) || 0;
    let gstPct = 100 + Number($gst) || 0;
    
      // console.log(qty ); return false;
       if(qty == 0 ){
            $(this).addClass('is-invalid');
       }else{

    let totalBeforeGST = sp * qty;                 
    let subtotal = totalBeforeGST * gstPct / 100;
    
    subtotal = subtotal.toFixed(2);

    
    
    $('#subtotal_' + getCode).text('₹'+subtotal).attr('data-val',subtotal);
    $('#subtotal_input_' + getCode).val(subtotal);
    let parm = {
            price : sp,
            qty : qty
    };
    cartProdAction(getCode,'U',parm); //update Session
    finalTotalCalc();

    }
});
function finalTotalCalc(){
     $finalTotalAmount = 0;
    let getSubtl = 0;
    $('.cart_subtotal').each(function(){
        getSubtl += Number($(this).attr('data-val'));
    });
      $('#final_amount').attr('data-final',getSubtl).text('₹'+getSubtl);
    
    let overall_gst = 100 + Number($('#overall_gst').attr('data-extra-gst'));
    //console.log(overall_gst); return false;
    let includingGstFinal = Number(getSubtl * overall_gst/100);
    let includingGstFinalFormatted = includingGstFinal.toFixed(2);
    $('#final_ing_gst').attr('data-final-inc-gst',includingGstFinalFormatted).text('₹'+includingGstFinalFormatted);

}


function cartProdAction(productCode,action,params){
    let url = '/ajaxcall';
    //console.log(productCode,action,params); return false;
    
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    $('#ajax_response').html('');
    let takeAction = false;
    if(action === 'S') { //single remove

        if(confirm(' Are you sure you want to remove it from cart?')){

            var  dataToPass = {
                product_code: productCode,
                type:'SINGLE-REMOVE'
            };
            takeAction = true;
        }
    }else if(action === 'U') { //Update qunatity from cart

        //disable checkout Btn
        $('#proced_order').prop('disabled',true).attr('disabled','disabled');
        $('#redirectToPlaceOrder').text('Calculating.....');
        var  dataToPass = {
            product_code: productCode,
            type:'CART-UPDATE',
            quantity : params.qty,
            price: params.price
        };
            takeAction = true;
        
    }
    if(takeAction){
        //console.log(dataToPass); return false;
        $.ajax({
            url: url,
            headers: { 'X-XSRF-TOKEN': csrfToken },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', csrfToken);
            },
            type: "POST",
            data: { data: dataToPass },
            success: function (res) {
                    if (res.status === 'success') {
                        if(action === 'S'){
                            $('#product_'+productCode).fadeOut('slow').remove();
                            let itemInCart = $('.cart_products').length;

                            if(itemInCart == 0){
                                    $('#cartContainer').attr('style','display:none');
                                    $('#emptyCartBox').attr('style','display:block');
                                    $('html, body').animate({
                                        scrollTop: $('.entry-content').offset().top
                                    }, 500);
                                    $('#catProdQnt').text(0);
                            }else{
                                        $('.cart_products').each(function(i){
                                            let sl = i+1;
                                            $(this).find('.SERIAL').text(sl);
                                        });
                                        finalTotalCalc();
                            }
                           
                        }else if( action === 'U' ){
                            $('#proced_order').prop('disabled',false).removeAttr('disabled');
                             $('#redirectToPlaceOrder').text('Checkout');
                        }
                         cartItemCounter();

                                
                       //.cart_products
                    }else if (res.status === 'ERROR-1') {
                        $('#ajax_response').html(`<p class="text text-center">`+res.msg+`</p>`);
                    }else if (res.status === 'ERROR-2') {
                        $('#ajax_response').html(`<p class="text text-center">`+res.msg+`</p>`);
                    }
            }
        });
    }
    

}

function showHide(){
    if( $('#loginFormSec').hasClass('d-none') ){
        $('#loginFormSec').removeClass('d-none');
        $('#guest_form').addClass('d-none');
         $('body').find("#toggleLink").text("Don’t have an account? Register");
    }else{
        $('#loginFormSec').addClass('d-none');
        $('#guest_form').removeClass('d-none');
        $('body').find("#toggleLink").text("Already have an account? Login");
    }
}
function backtologinform(){
    if( $('#forget_password').hasClass('d-none') ){
        $('#forget_password').removeClass('d-none');
        $('#loginForm').addClass('d-none');
         $('body').find("#swithloginforget").text("Back to Login");
    }else{
         $('#loginForm').removeClass('d-none');
         $('#forget_password').addClass('d-none');
         $('body').find("#swithloginforget").text("Forget Password ?");
    }
}



$("#carTform").validate({
        errorClass: "is-invalid",
        errorElement: "div",
        ignore: [], 
        rules: {
           
        },
        messages: {},
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function () {
            return false; 
        },
        submitHandler: function (form, event) {
           event.preventDefault(); // stop default submit
           let siteUrl = $('body').data('url')
             window.location.href = siteUrl+'/checkout';
        }
    });
    $(".quantityCalc").each(function() {
        $(this).rules("add", {
            required: true,
            min: 1,
            messages: {
                required: "Quantity is required",
                min: "Quantity must be greater than zero"
            }
        });
    });
$(document).on('click', '#redirectToPlaceOrder', function () {
     $('#user_pincode').removeClass('is-invalid shake');  
     let clickedBtn = $(this); 
     let pageNm = clickedBtn.data('page');
    let getPincode = $('#user_pincode').val();
    if( getPincode.length != 6 ){
        $('#user_pincode').addClass('is-invalid shake');  
    }else{
        clickedBtn.text('Checking....').prop('disbaled',true);
       
            let url = '/ajaxcall';
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        //alert(getPincode);
        let dataToPass = {
            type: 'SHIPPING-CHARGE',
            value: getPincode
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
                //console.log(res);
                
                if(pageNm === 'home'){
                   clickedBtn.text('Submit').prop('disbaled',false);
                     
                }else{
                    clickedBtn.text('Checkout').prop('disbaled',false);
                    $('#proced_order').removeAttr('disabled').prop('disabled', false);
                }
                
                $('#ajaxReply').remove();

                if (res.status === 'success') {
                    // Save pin in localStorage
                    managePinCode('W', getPincode);
                    
                    $('#proced_order').trigger('click');
                    $(".popup-overlay").fadeOut();
                    
                   
                } else if (res.status === 'ERROR-1') {

                        $('#proced_order').attr('disabled', 'disabled').prop('disabled', true);
                        $('.pincodeInput').before(
                            `<p class="text text-danger" id="ajaxReply">${res.msg}</p>`
                        );
                    }   
                                 
            }
        });
    }
    
});
$(document).on('click', '#navpincheck', function () {
     $('#user_pin_check').removeClass('is-invalid shake');  
     let clickedBtn = $(this); 
     let pageNm = clickedBtn.data('page');
    let getPincode = $('#user_pin_check').val();
    if( getPincode.length != 6 ){
        $('#user_pin_check').addClass('is-invalid shake');  
    }else{
        clickedBtn.text('Checking....').prop('disbaled',true);
       
            let url = '/ajaxcall';
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        //alert(getPincode);
        let dataToPass = {
            type: 'SHIPPING-CHARGE',
            value: getPincode
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
                //console.log(res);
                $('#ajaxReplyheader').remove();
                clickedBtn.text('Submit').prop('disbaled',false);
                if (res.status === 'success') {
                    managePinCode('W', getPincode);                   
                    $(".popup-overlay").fadeOut();
                    $('#user_pincode').val(getPincode);
                    
                   
                } else if (res.status === 'ERROR-1') {

                        $('.pincodeInput_nav').before(
                            `<p class="text text-danger" id="ajaxReplyheader">${res.msg}</p>`
                        );
                    }   
                                 
            }
        });
    }
    
});
$('#loginForm').validate({
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
     $('#signupForm').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            first_name: { required: true },
            last_name: { required: true },
            email: {  required: true , email:true },
            password: {
            required: true,
            minlength: 6 // you can set 8 or more if needed
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            },
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
        messages: {
            password: {
                required: "Please enter a password",
                minlength: "Password must be at least 6 characters long"
            },
            password_confirmation: {
                required: "Please confirm your password",
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
            return true; 
        },
         submitHandler: function (form) {
                 let getPincode = $('#pin').val();
                 
                 form.submit();
                 
         }
       
    });
    
     $('#customerProfile').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            first_name: { required: true },
            last_name: { required: true },
            email: {  required: true , email:true },
            
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
                 let getPincode = $('#pin').val();
                 
                 form.submit();
                 
         }
       
    });
    jQuery.validator.addMethod("mobileNumber", function (value, element) {
        // Example regex for a 10-digit Indian mobile number starting with 6, 7, 8, or 9
        var regex = /^[6-9]\d{9}$/; 
        return this.optional(element) || regex.test(value);
    }, "Please enter a valid mobile number.");

     $('#updatePassword').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            current_password: { required: true },
            password: {
            required: true,
            minlength: 6 // you can set 8 or more if needed
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            }
            
        },
        messages: {
            password: {
                required: "Please enter a password",
                minlength: "Password must be at least 6 characters long"
            },
            password_confirmation: {
                required: "Please confirm your password",
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
            return true; 
        },
         submitHandler: function (form) {
                 let getPincode = $('#pin').val();
                 
                 form.submit();
                 
         }
       
    });

    $('#chngepss').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            password: {
            required: true,
            minlength: 6 // you can set 8 or more if needed
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            }
            
        },
        messages: {
            password: {
                required: "Please enter a password",
                minlength: "Password must be at least 6 characters long"
            },
            password_confirmation: {
                required: "Please confirm your password",
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
            return true; 
        }
       
    });
      $('#newsLetterForm').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            email: {  required: true , email:true },            
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
                 $('#newsLetterSbt').text('subscribing....');
                 $('#newsltr_res').remove();
                    let url = '/ajaxcall';
                    let csrfToken = $('meta[name="csrf-token"]').attr('content');
                    let dataToPass = {
                            type:'POST-NEWSLETTER',
                            email : $('#newsletter_email').val()
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
                            $('#newsLetterSbt').text('Subscribe');
                                let html = `<div class="res" id="newsltr_res"><p class="text text-center text-`+res.status+`">`+res.msg+`</p></div>`;
                                $('#Newsletter').prepend(html);
                                
                        }
                    });
                 
         }
       
    });

$(document).on('click', '#pin_location', function () {
     $(".popup-overlay").fadeIn();
});
$(document).on('click', '#closeme', function () {
     $(".popup-overlay").fadeOut();
});
