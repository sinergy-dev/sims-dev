<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use DB;

class NewGuaranteeBank extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $lastid = DB::table('tb_bank_garansi')->select('nama_proyek', 'perusahaan')->where('status', 'new')->orderBy('created_at', 'desc')->first();

        return (new MailMessage)
                    ->line('New Request Guarantee Bank From Sales For : '.$lastid->nama_proyek)
                    ->line('There is a new request guarantee bank is created in the application. To access the Application please click the following button.')
                    ->action('New Request Guarantee Bank', url('/bank_garansi'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
