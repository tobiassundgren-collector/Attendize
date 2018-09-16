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

}
