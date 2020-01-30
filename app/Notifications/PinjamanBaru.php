<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use DB;

class PinjamanBaru extends Notification
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

        $peminjaman = DB::table('tb_asset_transaction')
                        ->join('users', 'users.nik', '=', 'tb_asset_transaction.nik_peminjam')
                        ->select('tgl_peminjaman', 'nik_peminjam', 'keterangan', 'name', 'keperluan')
                        ->orderBy('tb_asset_transaction.created_at', 'desc')
                        ->first();

        return (new MailMessage)
                    ->line('Berikut data peminjaman :')
                    ->line('Tanggal: '.$peminjaman->tgl_peminjaman)
                    ->line('Keperluan: '.$peminjaman->keperluan)
                    ->line('Oleh: '.$peminjaman->name)
                    ->line('Untuk melanjutkan proses peminjaman klik')
                    ->action('Technical Asset', url('/asset_pinjam'))
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
