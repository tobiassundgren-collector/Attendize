<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use App\Jobs\GenerateTicket;
use App\Jobs\SendOrderNotification;
use App\Jobs\SendOrderTickets;
use App\Jobs\ProcessGenerateAndSendTickets;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class OrderCompletedListener implements ShouldQueue
{

    use DispatchesJobs;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Generate the ticket and send it to the attendee. If the ticket can't be generated don't attempt to send the ticket
     * to the attendee as the ticket is attached as a PDF.
     *
     * @param  OrderCompletedEvent  $event
     * @return void
     */
    public function handle(OrderCompletedEvent $event)
    {
        /**
         * Generate the PDF tickets and send notification emails etc.
         */
        Log::info('Begin Processing Order: ' . $event->order->order_reference);
        ProcessGenerateAndSendTickets::withChain([
            new GenerateTicket($event->order->order_reference),
            new SendOrderTickets($event->order)
        ])->dispatch();

        GetSwish($event->order);
        $this->dispatch(new SendOrderNotification($event->order));
    }

    public function GetSwish($order)
    {
            $service_url = 'https://mpc.getswish.net/qrg-swish/api/v1/prefilled';
            $curl = curl_init($service_url);
            $jsonData = array(
                    "size"=> 300,
                    "format"=>"png",
                    "payee" => array("value" => "0739022421", "editable" => "false"),
                    "amount" => array("value" => 100, "editable" => "false"),
                    "message" => array("value" => "1ABC123123",  "editable" => "false"}
                  }
            );
            $headers = array('Content-type: application/json');
            $jsonDataEncoded = json_encode($jsonData);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonDataEncoded);

            $curl_response = curl_exec($curl);
            if ($curl_response === false) {
                $info = curl_getinfo($curl);
                curl_close($curl);
                die('error occured during curl exec. Additioanl info: ' . var_export($info));
            }
            curl_close($curl);
            $file_name = "testswish.png";
            $file_path = public_path(config('attendize.event_pdf_tickets_path')) . '/swish/' . $file_name;
            file_put_contents(file_path, curl_response);
            $decoded = json_decode($curl_response);
            if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
                die('error occured: ' . $decoded->response->errormessage);
            }
            echo 'response ok!';
    }
}
