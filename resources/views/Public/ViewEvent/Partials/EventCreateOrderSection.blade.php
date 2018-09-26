<section id='order_form' class="container">
    <div class="row">
        <h1 class="section_head">
            @lang("Public_ViewEvent.order_details")
        </h1>
    </div>
    <div class="row">
        <div class="col-md-12" style="text-align: center">
            @lang("Public_ViewEvent.below_order_details_header")
        </div>
        <div class="col-md-4 col-md-push-8">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="ico-cart mr5"></i>
                        @lang("Public_ViewEvent.order_summary")
                    </h3>
                </div>

                <div class="panel-body pt0">
                    <table class="table mb0 table-condensed">
                        @foreach($tickets as $ticket)
                        <tr>
                            <td class="pl0">{{{$ticket['ticket']['title']}}} X <b>{{$ticket['qty']}}</b></td>
                            <td style="text-align: right;">
                                @if((int)ceil($ticket['full_price']) === 0)
                                    @lang("Public_ViewEvent.free")
                                @else
                                {{ money($ticket['full_price'], $event->currency) }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @if($order_total > 0)
                <div class="panel-footer">
                    <h5>
                        @lang("Public_ViewEvent.total"): <span style="float: right;"><b>{{ $orderService->getOrderTotalWithBookingFee(true) }}</b></span>
                    </h5>
                    @if($event->organiser->charge_tax)
                    <h5>
                        {{ $event->organiser->tax_name }} ({{ $event->organiser->tax_value }}%):
                        <span style="float: right;"><b>{{ $orderService->getTaxAmount(true) }}</b></span>
                    </h5>
                    <h5>
                        <strong>Totalt:</strong>
                        <span style="float: right;"><b>{{  $orderService->getGrandTotal(true) }}</b></span>
                    </h5>
                    @endif
                </div>
                @endif

            </div>
            <div class="help-block">
                {!! @trans("Public_ViewEvent.time", ["time"=>"<span id='countdown'></span>"]) !!}
            </div>
        </div>
        <div class="col-md-8 col-md-pull-4">
            <div class="event_order_form">
                {!! Form::open(['url' => route('postCreateOrder', ['event_id' => $event->id]), 'class' => ($order_requires_payment && @$payment_gateway->is_on_site) ? 'ajax payment-form' : 'ajax', 'data-stripe-pub-key' => isset($account_payment_gateway->config['publishableKey']) ? $account_payment_gateway->config['publishableKey'] : '']) !!}

                {!! Form::hidden('event_id', $event->id) !!}

                <h3> @lang("Public_ViewEvent.your_information")</h3>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            {!! Form::label("order_first_name", trans("Public_ViewEvent.first_name")) !!}
                            {!! Form::text("order_first_name", null, ['required' => 'required', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            {!! Form::label("order_last_name", trans("Public_ViewEvent.last_name")) !!}
                            {!! Form::text("order_last_name", null, ['required' => 'required', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label("order_email", trans("Public_ViewEvent.email")) !!}
                            {!! Form::text("order_email", null, ['required' => 'required', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>

                            <?php
                        $total_attendee_increment = 0;
                            ?> 
                            @foreach($tickets as $ticket)
                                @for($i=0; $i<=$ticket['qty']-1; $i++)
                               
                                 {!! Form::hidden("ticket_holder_first_name[{$i}][{$ticket['ticket']['id']}]", null, [ 'class' => "ticket_holder_first_name.$i.{$ticket['ticket']['id']} ticket_holder_first_name form-control"]) !!}
                        
                                 {!! Form::hidden("ticket_holder_last_name[{$i}][{$ticket['ticket']['id']}]", null, ['class' => "ticket_holder_last_name.$i.{$ticket['ticket']['id']} ticket_holder_last_name form-control"]) !!}
                        
                                 {!! Form::hidden("ticket_holder_email[{$i}][{$ticket['ticket']['id']}]", null, [ 'class' => "ticket_holder_email.$i.{$ticket['ticket']['id']} ticket_holder_email form-control"]) !!}
                                            
                                @endfor
                            @endforeach

            @if($order_requires_payment)
                <h3>@lang("Public_ViewEvent.payment_information")</h3>
                @lang("Public_ViewEvent.below_payment_information_header")
                <input type="hidden" id="pay_offline" name="pay_offline" disabled="true" value="0">

                <ul class="nav nav-tabs navbar-light">
                    <li class="active"><a data-toggle="tab" style="cursor:pointer;" data-target="#card">Betala med Kort</a></li>
                    @if($event->enable_offline_payments)
                    <li><a data-toggle="tab" style="cursor:pointer;" data-target="#swish">Betala med Swish</a></li>
                    @endif
                </ul>

                <div class="tab-content">
                    <div id="card" class="tab-pane fade in active">
                        @if(@$payment_gateway->is_on_site)
                        <div  class="container">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                {!!HTML::image('assets/images/stripe.png')!!}<br />
                                    Betala med kort via Stripe.com
                                </div>
                                <div class="col-sm-12 col-md-6">
                                <button type="button" class="btn btn-link" style="color: #000; padding:10px;" data-toggle="modal" data-target="#cardModalCenter">
                                        Info om våra kortbetalningar
                                </button>
                                </div>
                            </div>
                        </div>
                        <div class="online_payment">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('card-number', trans("Public_ViewEvent.card_number")) !!}
                                        <input required="required" type="text" autocomplete="off" placeholder="**** **** **** ****" class="form-control card-number" size="20" data-stripe="number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        {!! Form::label('card-expiry-month', trans("Public_ViewEvent.expiry_month")) !!}
                                        {!!  Form::selectRange('card-expiry-month',1,12,null, [
                                                'class' => 'form-control card-expiry-month',
                                                'data-stripe' => 'exp_month'
                                            ] )  !!}
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        {!! Form::label('card-expiry-year', trans("Public_ViewEvent.expiry_year")) !!}
                                        {!!  Form::selectRange('card-expiry-year',date('Y'),date('Y')+10,null, [
                                                'class' => 'form-control card-expiry-year',
                                                'data-stripe' => 'exp_year'
                                            ] )  !!}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('card-expiry-year', trans("Public_ViewEvent.cvc_number")) !!}
                                        <input required="required" placeholder="***" class="form-control card-cvc" data-stripe="cvc">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div id="swish" class="tab-pane fade">
                    @if($event->enable_offline_payments)
                        <div>
                            {!!HTML::image('assets/images/swish.jpeg')!!}<br/>
                            Betala gärna med Swish. Snabbt, enkelt och säkert. <br/><br/>
                            Klicka på "Slutför Köp" nedan och följ instruktionerna som du får i ditt bekräftelse-email eller på nästa sida.
                        </div>
                    @endif
                    </div>
                </div>
            @endif

                @if($event->pre_order_display_message)
                <div class="well well-small">
                    {!! nl2br(e($event->pre_order_display_message)) !!}
                </div>
                @endif

               {!! Form::hidden('is_embedded', $is_embedded) !!}
               {!! Form::submit(trans("Public_ViewEvent.checkout_submit"), ['class' => 'btn btn-lg btn-success card-submit', 'style' => 'width:100%; margin:10px;']) !!}
            </form>
            </div>
        </div>
    </div>
</section>
@if(session()->get('message'))
    <script>showMessage('{{session()->get('message')}}');</script>
@endif

 <div class="modal fade" id="cardModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Info om våra kortbetalningar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Våra kortbetalningar hanteras av vår betalningspartner <a href="www.stripe.com/se" target="_new">Stripe</a> för att garantera snabba och säkra betalningar.<br/> 
  Stripe accepterar alla stora kort såsom VISA och MasterCard<br/> 
  Vi lagrar aldrig ert kreditkortsnummer, istället förmedlas din betalning av Stripe med säker kryptering. <br/>
  Stripe är certifierat enligt PCI-leverantör Level 1. Detta är den högsta nivån av certifiering som finns i betalningsbranschen. <br/>
        <br/> <br/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Stäng</button>
      </div>
    </div>
  </div>
</div>