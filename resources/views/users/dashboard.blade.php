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
												<table class="table shopping-summery">
													<thead>
														<tr class="main-hading">
															<th>SL NO.</th>
															<th>DATE</th>
															<th class="text-center">INVOICE NO.</th>
															<th class="text-center">TOTAL</th>
															<th class="text-center">ACTION</th> 
															
														</tr>
													</thead>
													<tbody>
    @forelse ($orders as $k => $order)
        @php
            $orderTime = strtotime($order->order_date);
            $currentTime = time();
            $diffInHours = floor(($currentTime - $orderTime) / 3600);
        @endphp
        <tr>
            <td data-title="Sl No.">{{ $k+1 }}</td>
            <td data-title="Date">{{ date('d/m/y h:i A', strtotime($order->order_date)) }}</td>
            <td data-title="Invoice">
                <a href="javascript:void(0);" title="View Detail">#{{ $order->invoice_number }}</a>
            </td>
            <td data-title="Total">₹{{ $order->total_amount }}</td>
            <td class="action" data-title="Action">
                <a class="button button_size_2 button_js"
                   href="{{ route('users.view',$order->order_number) }}">
                   <span class="button_label">View</span>
                </a>
                @if($order->status != '2')
                <a class="button button_size_2 button_js" href="{{ route('users.download-invoice',$order->order_number) }}">
                   <span class="button_label">Download</span>
                </a>
                @endif
               
                @if ($order->status == '4')
                    <span class="button button_size_2 button_js">
                        <div class="button_label">
                             Cancel Request Sent
                            </div>
                        </span> 
                @elseif ($order->status == '1')
                        @if ( $diffInHours < $limitHours )
                            <a class="button button_size_2 button_js" href="{{ route('users.cancel-request',$order->order_number)}}"
                            onclick="if(confirm('Are you sure you want to send Cancel request ?')){ return true; }else {return false; }"
                        >
                            <span class="button_label">Request Cancellation</span>
                        @else
                            <span class="button_label">Cancellation Not Allowed</span>
                        @endif
                        
                        </a>
                @elseif($order->status == '2')
                    <span class="button_label">Cancelled</span>
                @elseif($order->status == '3')
                    <span class="button_label">Partially Returned</span>
                @endif

            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5">No order found!</td>
        </tr>
    @endforelse
</tbody>

												</table>
												<!--/ End Shopping Summery -->
                                            </div>
                                        </div>

</x-frontend.customerlayout>