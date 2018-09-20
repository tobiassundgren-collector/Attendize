<footer id="footer" class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            &bull; <a href="#">Allmänna vilkor</a>
            &bull; <a href="#">Kontakta oss</a> 

                {{--Attendize is provided free of charge on the condition the below hyperlink is left in place.--}}
                {{--See https://www.attendize.com/licence.php for more information.--}}
                @include('Shared.Partials.PoweredBy')

                @if(Utils::userOwns($event))
                &bull;
                <a class="adminLink " href="{{route('showEventDashboard' , ['event_id' => $event->id])}}">@lang("Public_ViewEvent.event_dashboard")</a>
                &bull;
                <a class="adminLink "
                   href="{{route('showOrganiserDashboard' , ['organiser_id' => $event->organiser->id])}}">@lang("Public_ViewEvent.organiser_dashboard")</a>
                @endif
            </div>
        </div>
    </div>
</footer>
{{--Admin Links--}}
