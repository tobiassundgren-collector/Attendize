<?php

namespace App\Mailers;

use App\Models\Order;
use App\Services\Order as OrderService;
use Log;
use Mail;

class OrderMailer
{
    public function sendOrderNotification(Order $order)
    {
        $orderService = new OrderService($order->amount, $order->organiser_booking_fee, $order->event);
        $orderService->calculateFinalCosts();

        $data = [
            'order' => $order,
            'orderService' => $orderService
        ];

        Mail::send('Emails.OrderNotification', $data, function ($message) use ($order) {
            $message->to($order->account->email);
            $message->subject('New order received on the event ' . $order->event->title . ' [' . $order->order_reference . ']');
        });

    }

    public function sendOrderTickets(Order $order)
    {
        $orderService = new OrderService($order->amount, $order->organiser_booking_fee, $order->event);
        $orderService->calculateFinalCosts();

        Log::info("Creating QR code " . $order->order_reference);

        GenerateQr($order->order_reference, $orderService->getOrderTotalWithBookingFee(true))

        Log::info("Sending ticket to: " . $order->email);
        $data = [
            'order' => $order,
            'orderService' => $orderService
        ];
        if($order->is_payment_received)
        {
            $file_name = $order->order_reference;
            $file_path = public_path(config('attendize.event_pdf_tickets_path')) . '/' . $file_name . '.pdf';
            if (!file_exists($file_path)) {
                Log::error("Cannot send actual ticket to : " . $order->email . " as ticket file does not exist on disk");
                return;
            }

            Mail::send('Mailers.TicketMailer.SendOrderTickets', $data, function ($message) use ($order, $file_path) {
                $message->to($order->email);
                $message->subject(trans("Controllers.tickets_for_event", ["event" => $order->event->title]));
                $message->attach($file_path);
            });
        }else{
            Mail::send('Mailers.TicketMailer.SendOrderTickets', $data, function ($message) use ($order) {
                $message->to($order->email);
                $message->subject(trans("Controllers.tickets_for_event", ["event" => $order->event->title]));
            });
        }
    }

    public function GenerateQr($message, $amount)
    {
    
      $service_url = 'https://mpc.getswish.net/qrg-swish/api/v1/prefilled';
                $curl = curl_init($service_url);
                $jsonData = array(
                        "size"=> 200,
                        "format"=>"png",
                        "payee" => array("value" => "0760959055", "editable" => "false"),
                        "amount" => array("value" => $amount, "editable" => "false"),
                        "message" => array("value" => $message,  "editable" => "false")
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
    
                $decoded = json_decode($curl_response);
                if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
                    die('error occured: ' . $decoded->response->errormessage);
                }
    
                $filename = "qr_" . $message . ".png";
                $file_path = 
                "user_content/qr/" . $filename;
    
                $fp = fopen($file_path, 'w+'); // Create a new file, or overwrite the existing one.
                fwrite($fp, $curl_response);
                fclose($fp);
    
                return $file_path;
        }

}
