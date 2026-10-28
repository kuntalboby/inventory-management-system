 <script>
        const elements = document.getElementsByClassName('vendor-group');
        const numberOfElements = elements.length; // Returns the number of elements with that class

let vendorIndex = numberOfElements+1;

function addVendor() {
   
    var e = document.getElementById("unit_category_id");
    var selectedUnitCat = e.value;

    console.log(selectedUnitCat);
    
    const html = `
        <div class="vendor-group" id="vendor_${vendorIndex}" mode="@php echo $getInfo['mode'] @endphp">
            <div class="row">
                <div class="col-md-8"><h4 class="vendorNumber">Vendor ${vendorIndex}</h4></div>
                <div class="col-md-4 text text-danger trashVendor" onclick="removeVendor(${vendorIndex})">
                    <i class="fa fa-trash"></i>    
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Purchase Date *</label>
                        <div class="input-group date datetimepicker_${vendorIndex}" data-target-input="nearest">
                            <input type="text" placeholder="Date format :: dd-mm-yyyy" required id="purchase_date_${vendorIndex}" name="vendors[${vendorIndex}][purchase_date]" class="form-control datetimepicker-input" data-target=".datetimepicker_${vendorIndex}"/>
                            <div class="input-group-append" data-target=".datetimepicker_${vendorIndex}" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Vendor *</label>
                        <select name="vendors[${vendorIndex}][vendor_id]" required class="form-control selectPicker">
                            <option value="">Select Vendor</option>
                            @foreach ($getInfo['vendors'] as $vendor)
                                <option value="{{ $vendor->id }}">{{ ucwords(strtolower($vendor->name)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
               
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Quantity *</label>
                        <input type="number" required name="vendors[${vendorIndex}][stock_quantity]"
                         class="form-control buyingpcCalc" id="stock_quantity_${vendorIndex}" unique-id="${vendorIndex}" placeholder="Enter Quantity *">
                    </div>
                </div>
                 <div class="col-md-2">
                    <div class="form-group">
                        <label>Unit *</label>
                         <select class="form-control unit_id"  name="vendors[${vendorIndex}][unit_id]" id="unit_id_${vendorIndex}">
                                    <option value="">Select Unit</option>
                                    @if (!empty($getInfo['units']))
                                                
                                    @foreach ($getInfo['units'] as $unitIds)
                                        <option main-unit-id="{{ $unitIds['unit_category_id'] }}" value="{{$unitIds['id']}}" 
                                        >{{$unitIds['name']}}</option>
                                    @endforeach
                                    @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>GST</label>
                        <input type="number" value="0" step="0.01" min="0" required name="vendors[${vendorIndex}][gst]" 
                        class="form-control buyingpcCalc" id="gst_${vendorIndex}" unique-id="${vendorIndex}" placeholder="Minimum value should be 0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Actual Price per Unit *</label>
                        <input type="number"  value="0" step="0.01" min="0" required name="vendors[${vendorIndex}][actual_price]" 
                        class="form-control buyingpcCalc" id="actual_price_${vendorIndex}" unique-id="${vendorIndex}" placeholder="Enter Actual Price *">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Gross Buying Price *</label>
                        <input type="number" readonly step="0.01" min="0" required name="vendors[${vendorIndex}][buying_price]" 
                         class="form-control" 
                         id="buying_price_${vendorIndex}"
                         placeholder="Enter Buying Price *">
                    </div>
                </div>
            </div>
        </div>
    `;

    // Append new vendor block
    document.getElementById('addVendorBox').insertAdjacentHTML('beforeend', html);
    
        let newSelect = $(`#vendor_${vendorIndex} .selectPicker`);

        // Initialize Select2 only for this select
        newSelect.select2({
            theme: 'bootstrap4',
            dropdownParent: $(`#vendor_${vendorIndex}`) 
        });

    $(`.datetimepicker_${vendorIndex}`).datetimepicker({
        format: 'DD-MM-YYYY',
    });

     $(`#unit_id_${vendorIndex} option`).each(function () {
        const mainUnitCat = $(this).attr('main-unit-id');
        if (mainUnitCat && mainUnitCat !== selectedUnitCat) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });

    let vendorCount = $('.vendor-group').length;
    let mode = $('.vendor-group').attr('mode');
    
    if( mode == 'add' || ( mode=='edit' && vendorCount > 0 ) ){
    
            jQuery.validator.addMethod("dateDMY", function (value, element) {
                return this.optional(element) || /^\d{2}-\d{2}-\d{4}$/.test(value);
            });

            // Add validation rules
            $(`[name="vendors[${vendorIndex}][vendor_id]"]`).rules("add", { required: true });
            $(`[name="vendors[${vendorIndex}][purchase_date]"]`).rules("add", {
                required: true,
                dateDMY: true
            });
            
            $(`[name="vendors[${vendorIndex}][gst]"]`).rules("add", { min: 0, number: true});
            $(`[name="vendors[${vendorIndex}][unit_id]"]`).rules("add", { required: true, number: true});
            $(`[name="vendors[${vendorIndex}][actual_price]"]`).rules("add", { required: true, number: true });
            $(`[name="vendors[${vendorIndex}][buying_price]"]`).rules("add", { required: true, number: true });
            $(`[name="vendors[${vendorIndex}][stock_quantity]"]`).rules("add", { required: true, number: true });
         }
            // Show remove button and re-number vendors
            let totalVendors = $('.vendor-group').length;
            if (totalVendors > 1) {
                $('#bottomAddOption').removeClass('d-none');
                $('.vendorNumber').each(function (i) {
                    $(this).text('Vendor ' + (i + 1));
                });
            
    }
    vendorIndex++;


}

</script>