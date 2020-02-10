<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use DB;
use Auth;

class CutiKaryawan extends Notification
{
    use Queueable;
    public $id_cuti,$status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($id_cuti,$status)
    {
        $this->id_cuti = $id_cuti;
        $this->status  = $status;
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
        $cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->select('users.name')
                ->where('id_cuti', $this->id_cuti)->first();

        $hari = DB::table('tb_cuti')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'))
                ->groupby('tb_cuti_detail.id_cuti')
                ->where('tb_cuti.id_cuti', $this->id_cuti)
                ->first();

        if ($this->status == 'v') {
            return (new MailMessage)
                    ->line('Hore Cuti '.$cuti->name.' sudah di Approve si Bos!.')
                    ->line('Gunakan cuti kamu selama '. $hari->days .' hari sebaik mungkin ya ')
                    ->line('Untuk lihat detail Klik link di bawah ini')
                    ->action('Perizinan Cuti', url('/show_cuti'))
                    ->line('Thank you for using our application!');
        }else{
            return (new MailMessage)
                    ->line('Perizinan Cuti!.')
                    ->line('Oleh : ' .$cuti->name. ' Selama ' .$hari->days. ' hari' )
                    ->line('Untuk Persetujuan Klik link di bawah ini')
                    ->action('Perizinan Cuti', url('/show_cuti'))
                    ->line('Thank you for using our application!');
        }

        
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
