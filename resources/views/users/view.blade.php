{{-- <x-frontend.customerlayout>
    Hello Customer
    <a href="{{ route('customer-logout') }}">Logout</a>


</x-frontend.customerlayout> --}}

    <x-frontend.customerlayout
                       :basicData="$sitedata"
                       :menu="$menu"
                       >
    @section('bg_image',$bg_image)
    @section('title',$title)

    <div class="column mcb-column three-fourth column_column">
                                            <div class="column_attr clearfix">
											    <!-- Shopping Summery -->
                                                <div class="error_notification">
                                                    @if (session('message'))
                                                        <p class="text-center text text-{{session('response')}}">{{session('message')}}</p>
                                                    @endif
                                               </div>
                                                <h4 style="text-align: center; background: #f6f6f6; padding: 8px; margin: 0;">Order Details</h4>
                                                <table style="border: 1px solid #f6f6f6">
                                                    <thead>
                                                        <tr>
                                                            
                                                            <th width="100%" class="text-end company-data">
                                                                @if($orders->status == '2')
                                                                    <tr>
                                                                        <td  style="text-align:left; color: #b10404; border:none;">
                                                                            Cancelled Order
                                                                        </td>
                                                                    </tr> 
                                                                @endif
                                                                <tr><td  style="text-align:left; color: #000; border:none;">Invoice #{{$orders->invoice_number}}</td></tr> 
                                                                <tr><td style="text-align:left; color: #000; border:none;">Order Number: {{$orders->order_number}}</td></tr>
                                                                <tr><td style="text-align:left; color: #000; border:none;">Date: {{ date('d-m-Y h:i A',strtotime($orders->order_date)) }}</td> </tr>
                                                            </th>
                                                            
                                                        </tr>
                                                    </thead>
                                                </table>
                                                <table class="custmView">

                                                <tbody>
                                                    
                                                    <tr>
                                                        <td class="text-start company-data">
                                                            <span>BILL TO</span><br>
                                                            <span style="text-transform:uppercase;"><strong>{{ $orders->customerAddress->first_name .' '.$orders->customerAddress->last_name }}</strong></span><br>
                                                            <span>{{$orders->customerAddress->address_line_one}}, {{$orders->customerAddress->address_line_two}}, {{$orders->customerAddress->city}}, {{$orders->customerAddress->state}}, {{$orders->customerAddress->pin}}</span><br>
                                                            <span>Phone: {{$orders->customerAddress->phone}} / {{$orders->customerAddress->alternative_phone}}</span><br>
                                                            <span>Email: {{$orders->customerAddress->email}}</span>
                                                            
                                                        </td>
                                                        <td class="text-start company-data">
                                                            <span>SHIP TO</span><br>
                                                            <span style="text-transform:uppercase;"><strong>{{ $orders->customerAddress->first_name .' '.$orders->customerAddress->last_name }}</strong></span><br>
                                                            <span>{{$orders->customerAddress->address_line_one}}, {{$orders->customerAddress->address_line_two}}, {{$orders->customerAddress->city}}, {{$orders->customerAddress->state}}, {{$orders->customerAddress->pin}}</span><br>
                                                            <span>Phone: {{$orders->customerAddress->phone}} / {{$orders->customerAddress->alternative_phone}}</span><br>
                                                            <span>Email: {{$orders->customerAddress->email}}</span>
                                                            
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                
                                                </table>
                                                
												<table class="table shopping-summery">
                                                    
													<thead>
														<tr class="main-hading">
															<th class="text-center">NAME</th>
															<th class="text-center">QUANTITY</th>
															<th class="text-center">SELLING PRICE</th>
															<th class="text-center">GST</th>
															<th class="text-center">SUBTOTAL</th> 
															
														</tr>
													</thead>
													<tbody>
													 @foreach ($orders->orderDetails as $k => $order)
                                                    <tr>
                                                        <td class="" data-title="">{{$order->product_name}}</td>
                                                        <td class="" data-title="">
                                                            {{$order->quantity}}
                                                        </td>
                                                        <td class="" data-title="Invoice">
                                                            ₹{{$order->selling_price}}
                                                        </td>
                                                        <td class="">{{$order->product_gst}}%</td>
                                                        <td class="action" data-title="Action">₹{{$order->amount}}</td>
                                                    </tr>
                                                @endforeach
                                                    <tr class="tableBottom">
                                                        
                                                        <td class="del_charge" style="text-align:left;" colspan="3">
                                                            SHIPPING CHARGES : 
                                                            <span
                                                                @if( round($orders->total_amount,2) > round($orders->delvry_free_after,2) )
                                                                    style="text-decoration:line-through;font-weight: bold;"
                                                                @endif
                                                            >
                                                            ₹ {{$orders->delivery_charge}}
                                                            </span>
                                                        </td>
                                                        <td style="font-weight: bold; text-align: right;" colspan="2">Total :                                                            
                                                            ₹{{$orders->total_amount}}
                                                        </td>
                                                        
                                                    </tr>
														
														
													</tbody>
												</table>
                                                 <table>
                                                    <thead>
                                                        <tr>
                                                            
                                                            <th width="100%" class="text-end company-data">
                                                            <div class="column mcb-column two-fourth column_column">
                                                                 <a class="button button_size_2 button_js" href="{{route('users.dashboard')}}">
                                                                    <span class="button_label">Back</span>
                                                                 </a>
                                                            </div>
                                                            <div class="column mcb-column two-fourth column_column">
                                                                @php
                                                                    $orderTime = strtotime($orders->order_date);
                                                                    $currentTime = time();
                                                                    $diffInHours = floor(($currentTime - $orderTime) / 3600);
                                                                @endphp
                                                                 @if ($orders->status == '1')
                                                                        @if ( $diffInHours < $limitHours )
                                                                            <a class="button button_size_2 button_js" href="{{ route('users.cancel-request',$orders->order_number)}}"
                                                                            onclick="if(confirm('Are you sure you want to send Cancel request ?')){ return true; }else {return false; }"
                                                                        >
                                                                            <span class="button_label">Request Cancellation</span>
                                                                        @else
                                                                            <span class="button_label">Cancellation Not Allowed</span>
                                                                        @endif
                                                                        
                                                                        </a>
                                                                @elseif($orders->status == '2')
                                                                    <span class="button_label">Cancelled</span>
                                                                @elseif($orders->status == '3')
                                                                    <span class="button_label">Partially Returned</span>
                                                                @endif
                                                                
                                                             </div>    
                                                            </th>
                                                        
                                                            
                                                        </tr>
                                                    </thead>
                                                </table>

												<!--/ End Shopping Summery -->
                                            </div>
                                        </div>

</x-frontend.customerlayout>