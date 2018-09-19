@extends('en.Emails.Layouts.Master')

@section('message_content')
Hej kära biljettköpare,
<br/>
@if($order->is_payment_received)
tack för att du köpt biljetter till <b>{{$order->event->title}}</b>,<br><br>
Dina biljetter är bifogade i detta email.<br/>
Du kan också se detaljer för din beställning och ladda ner biljetterna på: {{route('showOrderDetails', ['order_reference' => $order->order_reference])}}
@else
tack för att du reserverat biljetter till <b>{{$order->event->title}}</b>,<br><br>
<br><br>
<b>För att slutföra beställningen, så är det bara att betala via Swish. 
Instruktioner för hur du betalar finns på: {{route('showOrderDetails', ['order_reference' => $order->order_reference])}}</b>
<br><br>
@endif
<h3>Detaljer för ordern</h3>
Bokningsnummer: <b>{{$order->order_reference}}</b><br>
Namn: <b>{{$order->full_name}}</b><br>
Bokningsdatum: <b>{{$order->created_at->toDayDateTimeString()}}</b><br>
Email: <b>{{$order->email}}</b><br>
<a href="{!! route('downloadCalendarIcs', ['event_id' => $order->event->id]) !!}">Lägg till i kalender</a>
<h3>Följande är @if($order->is_payment_received) köpt @else reserverat @endif</h3>
<div style="padding:10px; background: #F9F9F9; border: 1px solid #f1f1f1;">
    <table style="width:100%; margin:10px;">
        <tr>
            <td>
                <b>Biljett</b>
            </td>
            <td>
                <b>Antal</b>
            </td>
            <td>
                <b>Pris</b>
            </td>
            <td>
                <b>Avgift</b>
            </td>
            <td>
                <b>Summa</b>
            </td>
        </tr>
        @foreach($order->orderItems as $order_item)
                                <tr>
                                    <td>
                                        {{$order_item->title}}
                                    </td>
                                    <td>
                                        {{$order_item->quantity}}
                                    </td>
                                    <td>
                                        @if((int)ceil($order_item->unit_price) == 0)
                                        FREE
                                        @else
                                       {{money($order_item->unit_price, $order->event->currency)}}
                                        @endif

                                    </td>
                                    <td>
                                        @if((int)ceil($order_item->unit_price) == 0)
                                        -
                                        @else
                                        {{money($order_item->unit_booking_fee, $order->event->currency)}}
                                        @endif

                                    </td>
                                    <td>
                                        @if((int)ceil($order_item->unit_price) == 0)
                                        GRATIS
                                        @else
                                        {{money(($order_item->unit_price + $order_item->unit_booking_fee) * ($order_item->quantity), $order->event->currency)}}
                                        @endif

                                    </td>
                                </tr>
                                @endforeach
        <tr>
            <td>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td>
                <b>Delsumma</b>
            </td>
            <td colspan="2">
                {{$orderService->getOrderTotalWithBookingFee(true)}}
            </td>
        </tr>
        @if($order->event->organiser->charge_tax == 1)
        <tr>
            <td>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td>
                <b>{{$order->event->organiser->tax_name}}</b>
            </td>
            <td colspan="2">
                {{$orderService->getTaxAmount(true)}}
            </td>
        </tr>
        @endif
        <tr>
            <td>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td>
                <b>Totalt</b>
            </td>
            <td colspan="2">
                {{$orderService->getGrandTotal(true)}}
            </td>
        </tr>
    </table>

    <br><br>
</div>
<br><br>
Med vänlig hälsning
{{$order->event->organiser->name}}
@stop
