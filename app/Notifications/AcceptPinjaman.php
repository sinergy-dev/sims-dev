<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use DB;

class AcceptPinjaman extends Notification
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

        /*$peminjaman = DB::table('tb_detail_asset_transaction')
                        ->join('tb_asset_transaction', 'tb_asset_transaction.id_transaction', '=', 'tb_detail_asset_transaction.id_transaction')
                        ->join('users', 'users.nik', '=', 'tb_asset_transaction.nik_peminjam')
                        ->join('tb_asset', 'tb_asset.id_barang', '=', 'tb_detail_asset_transaction.id_barang')
                        ->select('tgl_peminjaman', 'nik_peminjam', 'keterangan', 'nama_barang', 'name')
                        ->orderBy('tb_asset_transaction.created_at', 'desc')
                        ->first();*/

        return (new MailMessage)
                    ->line('Barang yang Anda pinjam telah diverifikasi. Untuk pengambilan barang bisa dilakukan di ruang Technical.')
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
