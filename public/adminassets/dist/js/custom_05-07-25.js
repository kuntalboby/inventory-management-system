$(document).ready(function () {
const validator = $('#productAction').validate({
        errorClass: 'is-invalid', 
        errorElement: 'div',
        rules: {
            name: { required: true },
            product_code: { required: true },
            category_id: { required: true },
            main_unit_id: { required: true },
            status: { required: true }
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

$('.searchInput').on('keyup', function() {
        let query = $(this).val();
        let DataFor = $(this).data('for');
        let url= 'autocomplete';
        let csrfToken = $('div[name="csrf-token"]').attr('content');
        if(DataFor == 'addNew'){
            url = '/admin/autocomplete';
            csrfToken = $('meta[name="csrf-token"]').attr('content');
        }


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
                    data.forEach(item => {
                        let pdName = ucwords(item.name);
                        list += `<li class="suggest-item">${pdName}</li>`;
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
        
        order_date: { required: true },
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
           tableHTML += `
                        <tr>
                            <td>${product.name}</td>
                            <td>${product.quantity}</td>
                            <td><i class="fas fa-rupee-sign"></i> ${product.selling_price}</td>
                            <td>${product.gst} %</td>
                            <td><i class="fas fa-rupee-sign"></i> ${product.total}</td>
                        </tr>
                    `;

                    subTotal += parseFloat(product.total);
            // Do your processing here
        });
        document.getElementById('selectedAllProducts').innerHTML = tableHTML;
        //document.getElementById('subtotal').innerHTML = '<i class="fas fa-rupee-sign"></i> '+subTotal;

        $('.subtotal').html('<i class="fas fa-rupee-sign"></i> '+subTotal);
        $('#subtotal').attr('amount',subTotal);
        

        
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
            for (let key in orderCustomer) {
                if (
                    orderCustomer.hasOwnProperty(key) &&
                    key !== 'form_type' // exclude form_type
                ) {
                    let label = labelMap[key] || key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                    let value = orderCustomer[key] ?? '-';
                    customerHtml += `<b>${label}:</b> ${value}<br>`;
                }
            }

            $('.invoice-col').html('<p class="lead">Customer Details:</p>'+customerHtml);


}

$(document).on('keyup', '#overAll_gst', function() {

    let excdGST = $(this).val(); 
    let subTotal = parseFloat($('#subtotal').attr('amount'));
    if( excdGST ){
        excdGST = parseFloat(excdGST); 
        
        

                let gst = excdGST*subTotal/100;
                let final_amount = subTotal+gst;
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
     $(document).on('keyup', '.quantityCalc', function() {
        let getCode = $(this).data('code');
        let qnt = $(this).val();
        let getSp = $('#selling_price_'+getCode).val();
        let getGst = $('#gst_'+getCode).val();

        // console.log(getCode);
        // console.log(qnt);
        // console.log(getSp);
        // console.log(getGst);
        

            let excdGST = parseFloat(getSp)*qnt;
            let gst = excdGST*getGst/100;
            let total = excdGST+gst;
        $('#amount_'+getCode).val(total);   

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
                    data.forEach(item => {
                        let pdName = ucwords(item.name);
                        list += `<li class="suggest-item">${pdName}</li>`;
                    });
                    $('#suggestionList').html(list).show();
                    
                    
                    
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
                            let sellingPrce = data.latest_product_selling_price.selling_price;
                            let gst= data.product_details.gst;

                            let makeForm = `
                            <div prod-slug="`+query+`" class="row addedPord card card-dark card-outline" id="prod_`+data.product_code+`">
                                    <div class="col-md-12">
                                        <div class="card-body">
                                            <div class="row">
                                                    <div class="col-md-4">
                                                            <div class="form-group">
                                                                <input type="hidden" name="data[`+data.product_code+`][slug]" value="`+data.slug+`"/>
                                                                <label for="name">Name</label>
                                                                <input type="text" name="data[`+data.product_code+`][name]" value="`+ucwords(data.name)+`" readonly="readonly" class="form-control" id="name_`+data.product_code+`" />
                                                            </div>
                                                    </div>
                                                    <div class="col-md-2">

                                                        <div class="form-group">
                                                            <label for="sp">Selling Price</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                                                            </div>
                                                                <input type="number" name="data[`+data.product_code+`][selling_price]" value="`+sellingPrce+`" readonly="readonly" class="form-control" id="selling_price_`+data.product_code+`" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text ">/`+ucwords(data.product_details.unit.name)+`</span>
                                                                </div>
                                                                </div>
                                                        </div>
                                                        
                                                    </div>

                                                    <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="quantity">Quantity</label>

                                                                <input type="number" max="`+data.product_details.quantity+`" 
                                                                data-code="`+data.product_code+`" 
                                                                name="data[`+data.product_code+`][quantity]"
                                                                value="0" class="form-control quantityCalc" 
                                                                id="quantity_`+data.product_code+`" />
                                                                
                                                            </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label for="quantity">GST</label>
                                                                <input type="number"
                                                                name="data[`+data.product_code+`][gst]"
                                                                 value="`+gst+`" readonly="readonly"
                                                                  class="form-control" id="gst_`+data.product_code+`" />
                                                            </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        
                                                            <div class="form-group">
                                                                <label for="amount">Total</label>
                                                                <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                                                                </div>
                                                                    <input name="data[`+data.product_code+`][total]" type="number" value="0" step="0.1" readonly="readonly" class="form-control" id="amount_`+data.product_code+`" />
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                    <div class="form-group">
                                                                <label for="removeFromList_`+data.product_code+`">&nbsp;</label>
                                                        <button data-id="`+data.product_code+`" id="removeFromList_`+data.product_code+`" type="button"" class="form-control btn btn-danger removeFromList">
                                                            <i class="fas fa-trash text text-white"></i>
                                                        </button>
                                                    </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>`;

                            $('#getProdDetails').prepend(makeForm);
                            
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
        paging: true           // enable pagination
    });
});

$(document).on('submit', '#getProdDetails', function(e) {
    let isValid = true;
    e.preventDefault();
    // Remove previous error highlights
    $('.quantityCalc').removeClass('is-invalid');

    $('.quantityCalc').each(function() {
        let qty = parseFloat($(this).val());

        if (isNaN(qty) || qty <= 0) {
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

$(window).resize(function(){
        var myWindowHght = $(window).height();
        var _scrollYCalc = myWindowHght*167/323;
        var _scrollY = _scrollYCalc - 70;	
        $('.dataTables_scrollBody').css('max-height',_scrollY+'px');	 
});
var dataTable = $('#kt_table_1').DataTable({
 

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
            d.buying_price = $('#buying_price').val();
            d.selling_price = $('#selling_price').val();
            d.quantity = $('#quantity').val();
            d.unit_id = $('#unit_id').val();
            d.category_id = $('#category_id').val();
            d.vendor_id = $('#vendor_id').val();
            d.purchase_date = $('#purchase_date').val();
            d.expiry_date = $('#expiry_date').val();
      
   }
},
'columns': [
    //{ data: 'serial' }, 
    { data: 'name' }, 
    { data: 'category_id' },
    { data: 'product_code' },
    { data: 'product_quantity' },
    { data: 'status' },
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
$('#searchFilter button').on('click', function () {
    dataTable.ajax.reload();
});
$('#searchCustomer button').on('click', function () {
    customerDataTable.ajax.reload();
});

$('#searchOrder button').on('click', function () {
    OrderDataTable.ajax.reload();
});

$('#datetimepicker1').datetimepicker({
    format: 'DD-MM-YYYY',
    useCurrent: false
});

$('#datetimepicker2').datetimepicker({
    format: 'DD-MM-YYYY',
    useCurrent: false
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
        let getFrmId = $(this).attr('vendor-list-id');

        let original_PD = $('.pd_'+getFrmId).data('original');
        let original_QT = $('.qt_'+getFrmId).data('original');
        let original_BP = $('.bp_'+getFrmId).data('original');
        let original_SP = $('.sp_'+getFrmId).data('original');

        //changes
        let changes_PD = $('.pd_'+getFrmId).val();
        let changes_QT = $('.qt_'+getFrmId).val();
        let changes_BP = $('.bp_'+getFrmId).val();
        let changes_SP = $('.sp_'+getFrmId).val();
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
        if( original_SP != changes_SP){
            countChanges +=1;
            detectChanges.push('Selling Price from '+original_SP+' to '+ changes_SP);
        }

        if( changes_SP <= changes_BP || (changes_SP <= original_BP ) ) {  countChanges += 1; detectChanges.push('Selling Price is less than original price');}
       
        console.log(detectChanges, countChanges);

        if(countChanges > 0 ){
            let msg = 'Do you want to change these : '+detectChanges.toString();
            if(confirm(msg)){
                    //ajax submit
                    let _data = {
                        id: getFrmId,
                        purchase_date: changes_PD,
                        buying_price: changes_BP,
                        selling_price: changes_SP,
                        quantity: changes_QT

                    }
                    if(updateVendor(_data)){
                        $('.pd_'+getFrmId).val(changes_PD);
                        $('.bp_'+getFrmId).val(changes_BP);
                        $('.sp_'+getFrmId).val(changes_SP);
                        $('.qt_'+getFrmId).val(changes_QT);
                    }
            }else{
                     $('.pd_'+getFrmId).val(original_PD);
                     $('.bp_'+getFrmId).val(original_BP);
                     $('.sp_'+getFrmId).val(original_SP);
                     $('.qt_'+getFrmId).val(original_QT);
            }
        }else{
            alert( 'It looks like you didn’t make any changes.') 
        }
   });

   function updateVendor( dataToUpdate){
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
                                $('.pd_'+getFrmId).attr('data-original',dataToUpdate.purchase_date).val(dataToUpdate.purchase_date);
                                $('.qt_'+getFrmId).attr('data-original',dataToUpdate.quantity).val(dataToUpdate.quantity);
                                $('.bp_'+getFrmId).attr('data-original',dataToUpdate.buying_price).val(dataToUpdate.buying_price);
                                $('.sp_'+getFrmId).attr('data-original',dataToUpdate.selling_price).val(dataToUpdate.selling_price);
                                alert('Information has been updated successfully.');
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
            d.name = $('#name').val();       // input[name="name"]
            d.date_from = $('#date_from').val();     // input[name="email"]
            d.date_to = $('#date_to').val();     // input[name="email"]
            d.phone = $('#phone').val();     // input[name="phone"]
            d.invoice_number = $('#invoice_number').val(); // input[name="order_id"]
      
   }
},
'columns': [
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
'aaSorting' : [[0, 'asc']],
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