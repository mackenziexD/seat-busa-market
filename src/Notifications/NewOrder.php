<?php

namespace Helious\SeatBusaMarket\Notifications;

use Illuminate\Notifications\Messages\SlackAttachmentField;
use Illuminate\Notifications\Messages\SlackMessage;
use Raykazi\Seat\SeatApplication\Models\ApplicationModel;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class StuctureWarnings.
 *
 * @package Seat\Kassie\Calendar\Notifications
 */
class NewOrder extends AbstractNotification
{
    use NotificationTools;
    
    private $message;
    
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    public function toSlack($notifiable)
    {
        // message is passed in from the controller and is an array of 
        // 'user' => auth()->user()->name,
        // 'janiceLink' => $janiceLink,
        // 'items' => $items,
        // 'price' => $price
    
        // If $this->message is already an array, you can directly access its elements
        $message = $this->message;
    
        return (new SlackMessage)
            ->success()
            ->from('SeAT BUSA-Mart')
            ->attachment(function ($attachment) use ($message) {
                $attachment->field(function ($field) use ($message) {
                    $field->title('Order Created By')
                        ->content($message['user']);
                });
    
                $attachment->field(function ($field) use ($message) {
                    $field->title('Janice Link')
                        ->content($message['janiceLink']);
                });
    
                $attachment->field(function ($field) use ($message) {
                    $field->title('Items')
                        ->content(count($message['items']));
                });
    
                $attachment->field(function ($field) use ($message) {
                    $field->title('Price')
                        ->content(number_format($message['price']).' ISK');
                });
            });
    }    
    
}