<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Pendaftaran;

class StatusPendaftaranNotification extends Notification
{
    public function __construct(
        public ?Pendaftaran $pendaftaran = null,
        public ?string $tipe = null
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $pesanStatus = [
            'menunggu_verifikasi' => [
                'title' => 'Pendaftaran Diterima',
                'body' => 'Pendaftaran kamu sedang menunggu verifikasi.',
                'icon' => 'clock',
                'color' => 'yellow',
            ],
            'seleksi_pretest' => [
                'title' => 'Lanjut ke Pretest',
                'body' => 'Pendaftaran kamu diverifikasi. Silakan kerjakan pretest.',
                'icon' => 'file-text',
                'color' => 'blue',
            ],
            'wawancara' => [
                'title' => 'Jadwal Wawancara',
                'body' => 'Kamu lolos pretest dan dijadwalkan untuk wawancara.',
                'icon' => 'mic',
                'color' => 'purple',
            ],
            'diterima' => [
                'title' => 'Selamat, Kamu Diterima! 🎉',
                'body' => 'Kamu resmi diterima di Rumah Gemilang Indonesia.',
                'icon' => 'check-circle',
                'color' => 'green',
            ],
            'ditolak' => [
                'title' => 'Hasil Seleksi',
                'body' => 'Mohon maaf, kamu belum berhasil dalam seleksi ini.',
                'icon' => 'x-circle',
                'color' => 'red',
            ],
        ];

        $pesanSistem = [
            'pendaftaran_dibuka' => [
                'title' => 'Pendaftaran Dibuka',
                'body' => 'Pendaftaran peserta baru telah dibuka. Segera lakukan pendaftaran.',
                'icon' => 'megaphone',
                'color' => 'green',
            ],
            'pendaftaran_ditutup' => [
                'title' => 'Pendaftaran Ditutup',
                'body' => 'Masa pendaftaran telah berakhir.',
                'icon' => 'lock',
                'color' => 'red',
            ],
            'pretest_dibuka' => [
                'title' => 'Pretest Dibuka',
                'body' => 'Pretest sudah dapat dikerjakan sekarang.',
                'icon' => 'file-text',
                'color' => 'blue',
            ],
            'pretest_ditutup' => [
                'title' => 'Pretest Ditutup',
                'body' => 'Waktu pengerjaan pretest telah berakhir.',
                'icon' => 'clock',
                'color' => 'amber',
            ],
        ];

        if ($this->pendaftaran) {
            $data = $pesanStatus[$this->pendaftaran->status] ?? [
                'title' => 'Update Pendaftaran',
                'body' => 'Status pendaftaran kamu telah diperbarui.',
                'icon' => 'bell',
                'color' => 'gray',
            ];

            return array_merge($data, [
                'type' => 'status',
                'pendaftaran_id' => $this->pendaftaran->id,
                'kode_pendaftaran' => $this->pendaftaran->kode_pendaftaran,
                'status' => $this->pendaftaran->status,
            ]);
        }

        $data = $pesanSistem[$this->tipe] ?? [
            'title' => 'Informasi Sistem',
            'body' => 'Ada informasi terbaru untuk kamu.',
            'icon' => 'bell',
            'color' => 'gray',
        ];

        return array_merge($data, [
            'type' => 'system',
            'tipe' => $this->tipe,
        ]);
    }


    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
