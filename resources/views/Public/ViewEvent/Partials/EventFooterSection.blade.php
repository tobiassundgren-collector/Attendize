<footer id="footer" class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#termsModalCenter">
            Allmänna villkor
            </button>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#contactModalCenter">
            Kontakta oss
                </button>

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

   <div class="modal fade" id="contactModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Kontakta oss</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <strong>Kontakta Mölnlycke Storband</strong><br>
        Önskar du att komma i kontakt med oss, skicka ett mail till kontakt@molnlyckestorband.com med ditt ärende och dina kontaktuppgifter så hör vi av oss inom kort.
        Du kan också ringa Tobias Sundgren på 0760959055. 
        <br/> <br/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Stäng</button>
      </div>
    </div>
  </div>
</div>
    <!-- Modal -->
    <div class="modal fade" id="termsModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Allmänna villkor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <ol>
    <li>
        <strong>Om Mölnlycke Storband</strong>
    </li>
</ol>
<ul>
    <li>Företaget har sitt säte i Mölnlycke. Företagets postadress är Högadalsvägen 2.</li>
    <li>Orgnr: 852000-7959</li>
    <li>Önskar du att komma i kontakt med oss, skicka ett mail till kontakt@molnlyckestorband.com med ditt ärende och dina kontaktuppgifter så hör vi av oss inom kort.</li>
</ul>
<ol start="2">
    <li>
        <strong>Beställning</strong>
    </li>
</ol>
<ul>
    <li>När du slutfört din beställning skickas en orderbekräftelse till din e-postadress. I bekräftelsen finner du alla uppgifter om produkter, pris, fakturerings- och leveransadress.</li>
    <li>Är något fel i orderbekräftelsen ska du omedelbart kontakta oss via e-post till kontakt@molnlyckestorband.com.</li>
</ul>
<ol start="3">
    <li>
        <strong>Leverans</strong>
    </li>
</ol>
<ul>
    <li>Våra normala leveranstider är 1 dagar. OBS! Beställningar lagda på helger skickas tidigast på måndagen efter.</li>
    <li>Om förseningar i leveransen skulle uppstå (utan att vi har meddelat dig om längre leveranstid) ska du kontakta oss på e-postadress: kontakt@molnlyckestorband.com.</li>
</ul>
<ol start="4">
    <li>
        <strong>Priser</strong>
    </li>
</ol>
<ul>
    <li>Alla priser i butiken anges i SEK och alla priser är inklusive 0% moms.</li>
    <li>Vi reserverar oss för prisändringar orsakat av prisändring från leverantör, feltryck i prislistan samt felaktigheter i priser beroende på felaktig information och förbehåller oss rätten att justera priset.</li>
</ul>
<ol start="5">
    <li>
        <strong>Ångerrätt</strong>
    </li>
</ol>
<ul>
    <li>Vid köp av varor på webbplatsen har du som kund en lagstiftad 14 dagars ångerrätt som gäller från det att du har tagit emot en vara som du har beställt. 
        
        <br />
        <br />
        5.1 <strong>Vid nyttjande av din ångerrätt: </strong>
    </li>
    <li>Du måste meddela att du ångrar dig. Meddelandet ska skickas till oss kontakt@molnlyckestorband.com. I ditt meddelande ska ditt namn, din adress, e-postadress, ordernumret samt vilka varor som returneringen gäller framgå klart och tydligt.</li>
    <li>Du bör omedelbart och senast inom lagstiftad 14 dagar efter ångermeddelandet returnera produkterna till oss.</li>
    <li>Du står för returfrakt, leverans och skick på produkterna vid retur, produkterna bör därför skickas välpaketerade och i ursprunglig förpackning.</li>
    <li>På återbetalningsbeloppet förbehåller vi oss rätten att dra av en summa motsvarande värdeminskningen jämfört med varans ursprungliga värde vid använd eller skadad produkt. 
        
        <br />
        <br />
        5.2 <strong>Ångerrätten gäller inte vid:</strong>
    </li>
    <li>Produkter som på grund av hälso- eller hygienskäl har förseglats (plomberats) och där förseglingen (plomberingen) har brutits av dig.</li>
    <li>Produkter som har karaktär av förseglad ljud- eller bildupptagning och där förseglingen har brutits av dig.</li>
    <li>Specialtillverkad produkt, som har skräddarsytts särskilt åt dig eller har en tydlig personlig prägel efter dina önskemål.</li>
    <li>Tjänster som har fullgjorts och där du uttryckligen har samtyckt till att tjänsten påbörjas utan ångerrätt.</li>
    <li>Varor som snabbt kan försämras, exempelvis livsmedel.</li>
    <li>Lösnummer av tidningar eller tidskrifter. 
        
        <br />
        <br />För mer om den lagstiftade ångerrätten, se  
        
        <a target="_blank" href="http://www.konsumentverket.se/for-foretag/konsumentratt-for-foretagare/om-kunden-angrar-sitt-kop/">
            <strong>här</strong>.
        </a>
        
    </li>
</ul>
<ol start="6">
    <li>
        <strong>Reklamation och klagomål</strong>
    </li>
</ol>
<ul>
    <li>Vi besiktigar alla produkter innan dessa skickas till dig. Skulle produkten ändå vara skadad eller felexpedierad när den anländer åtar vi oss i enlighet med gällande konsumentskyddslagstiftning att kostnadsfritt åtgärda felet.</li>
    <li>Du måste alltid kontakta oss för ett godkännande innan du returnerar en defekt vara.</li>
    <li>Klagomålet ska skickas omedelbart efter att defekten har upptäckts. 
        
        <br />
        <br />
        6.1 <strong>Hur går du tillväga vid reklamation?</strong>
    </li>
    <li>Eventuella fel och defekt ska alltid reklameras till kontakt@molnlyckestorband.com där du anger ditt namn, din adress, e-postadress, ordernummer och en beskrivning av felet.</li>
</ul>
<ul>
    <li>Om det inte lyckas oss att åtgärda felet eller leverera en liknande produkt, återbetalar vi dig för den defekta produkten i enlighet med gällande konsumentskyddslagstiftning. Vi står för returfrakt vid godkända reklamationer.</li>
    <li>Vi förbehåller oss rätten att neka en reklamation om det visar sig att varan inte är felaktig i enlighet med gällande konsumentskyddslagstiftning. Vid reklamationer följer vi riktlinjer från Allmänna Reklamationsnämnden, se arn.se.</li>
</ul>
<ol start="7">
    <li>
        <strong>Ansvarsbegränsning </strong>
    </li>
</ol>
<ul>
    <li>
        <strong>Vi tar inget ansvar för indirekta skador som kan uppstå på grund av produkten.</strong>
    </li>
    <li>Vi accepterar inget ansvar för förseningar/fel till följd av omständigheter utanför företagets rådande (Force Majeure). Dessa omständigheter kan exempelvis vara arbetskonflikt, eldsvåda, krig, myndighetsbeslut, förminskad eller utebliven leverans från leverantör.</li>
    <li>Vidare tas inget ansvar för eventuella förändringar på produkter/produktegenskaper som ändrats av respektive leverantör och andra faktorer utanför vår kontroll.</li>
</ul>
<ol start="8">
    <li>
        <strong>Produktinformation</strong>
    </li>
</ol>
<ul>
    <li>Vi reserverar oss för eventuella tryckfel på denna webbplats samt slutförsäljning av produkter. Vi garanterar inte att bilderna återger produkternas exakta utseende då en viss färgskillnad kan förekomma beroende på bildskärm, fotokvalitet samt upplösning. Vi försöker alltid på bästa sätt att exponera produkterna så korrekt som möjligt.</li>
</ul>
<ol start="9">
    <li>
        <strong>Information om Cookies</strong>
    </li>
</ol>
<ul>
    <li>Enligt lag om elektronisk information ska besökare på en webbplats i integritetssyfte få information om att cookies används. Informationen i cookien är möjlig att använda för att följa en användares surfande. Cookie är en liten textfil som webbplatsen du besöker begär att få spara på din dator för att ge tillgång till olika funktioner. Det går att ställa in sin webbläsare så att den automatiskt nekar cookies. Mer information kan man hitta på Post och telestyrelsens hemsida.</li>
</ul>
<ol start="10">
    <li>
        <strong>Personuppgifter</strong>
    </li>
</ol>
<ul>
<li>
        Genom att handla hos Mölnlycke Storband accepterar du vår dataskyddspolicy och vår behandling av dina personuppgifter. Vi värnar om din personliga integritet och samlar inte in fler uppgifter än nödvändigt för att behandla din beställning.
        Vi säljer eller vidareger aldrig dina uppgifter till tredjepart utan rättslig grund.
</li>
<li>
    Mölnlycke Storband är ansvarig för behandlingen av personuppgifter som du lämnat till oss som kund. Dina personuppgifter behandlas av oss för att kunna hantera din beställning samt i de tillfällen då du har önskat nyhetsbrev eller kampanjerbjudanden - för att kunna anpassa marknadsföringen åt dina individuella behov.
</li>
<li>
    Nedan information är en summering av hur vi i enlighet med <a href="https://www.datainspektionen.se/dataskyddsreformen/dataskyddsforordningen/" target="_blank">dataskyddsförordningen</a> (GDPR) lagrar och behandlar dina uppgifter.
</li>
<li>
    <strong>10.1 Vad är en personuppgift?</strong><br />
    En personuppgift är all information som direkt eller indirekt kan hänföras till en fysisk person.
</li>
<li>
    <strong>10.2 Vilka uppgifter lagrar vi?</strong><br />
    För att kunna hantera din beställning samt svara på frågor relaterat till din order (kundtjänst) lagrar vi ditt förnamn- och efternamn, adress, telefonnummer, e-postadress, ip-adress och köphistorik.
</li>
<li>
    Dina uppgifter lagras så länge vi har en rättslig grund att behandla dina uppgifter, exempelvis för att fullfölja avtalet mellan oss eller för att efterleva en rättslig förpliktelse enligt exempelvis bokföringslagen.
</li>
<li>
    <strong>10.3 Rättslig grund</strong><br />
    I samband med ett köp behandlas dina personuppgifter för att fullfölja avtalet med dig.<br />
    Marknadsföring, kampanjer och liknande utskick sker efter samtycke från dig.
</li>
<li>
    <strong>10.4 Vilka uppgifter delas och med vilket syfte?</strong><br />
    <strong>10.4.1 Betalleverantör</strong>
</li>
<li>
    Vid genomförande av köp, delas information med vår betalleverantör. Det som lagras är förnamn, efternamn, adress, e-postadress och telefonnummer.
    Väljer du att betala med faktura sparas även personnummer hos betalleverantören. Informationen sparas för att kunna genomföra köpet och för att skydda parterna mot bedrägeri.
    <br />
    De betalleverantörer (betaltjänster) som vi använder oss av är: Stripe, Swish.
</li>
<li>
    <strong>10.4.2 Fraktbolag</strong><br />
    Vi skickar alla biljetter via epost.
</li>
<li>
     <strong>10.5 Rätten till tillgång</strong><br />
    Du har rätt att få utdrag av all information som finns om dig hos oss. Utdrag levereras elektroniskt i ett läsbart format.
</li>
<li>
     <strong>10.6 Rätt till rättelse</strong>
    <br />
    Du har rätt att be oss uppdatera felaktig information eller komplettera information som är bristfällig.
</li>
<li>
     <strong>10.7 Rätten att bli glömd</strong><br />
    Du kan när som helst be att uppgifterna som avser dig raderas.
    Det finns få undantag till rätten till radering, som till exempel om det ska behållas för att vi måste uppfylla en rättslig förpliktelse (exempelvis enligt bokföringslagen).
</li>
<li>
    <strong>10.8 Ansvarig för dataskydd</strong><br />
    Mölnlycke Storband är ansvarig för lagring och behandling av personuppgifter i webbutiken och ser till att reglerna efterföljs.
</li>
<li>
<strong>10.9 Så skyddar vi dina personuppgifter</strong><br />
<?php
if(isset($use_ssl))
{
    ?>
Vi använder oss av industristandarder som SSL/TLS och envägs hash-algoritmer för att lagra, behandla och kommunicera känslig information som exempelvis personuppgifter och lösenord på ett säkert sätt. 
        <?php
}

if(isset($use_quickbutik))
{
    ?>
<br />
Vi använder en svensk plattform, Quickbutik, som drivs av Quickbutik AB med säte i Helsingborg.
<?php
}
?>
    </li>
</ul>
<ol start="11">
    <li>
        <strong>Ändringar till de Allmänna Villkoren</strong>
    </li>
</ol>
<ul>
    <li>Vi förbehåller oss rätten att när som helst företa ändringar i villkoren. Ändringar av villkoren kommer att publiceras online på webbplatsen. De ändrade villkoren anses för accepterade i samband med order eller besök på webbplatsen.</li>
</ul>
<ol start="12">
    <li>
        <strong>Tvist och lagval</strong>
    </li>
</ol>
<ul>
    <li>I tillfälle av att tvist inte kan lösas i samförstånd med företagets kundtjänst och kunden, kan du som kund vända dig till Allmänna Reklamationsnämnden, se arn.se. För boende i ett annat EU-land än Sverige kan man lämna klagomål online via EU-kommissionens plattform för medling i tvister, se http://ec.europa.eu/consumers/odr</li>
    <li>Vid eventuell tvist följer vi beslut från ARN eller motsvarande tvistlösningsorgan.</li>
    <li>Tvist gällande tolkningen eller tillämpningen av dessa allmänna villkor ska tolkas i enlighet med svensk rätt och lag.</li>
</ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Stäng</button>
      </div>
    </div>
  </div>
</div>
