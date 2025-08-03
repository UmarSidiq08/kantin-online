<?php

namespace App;

class Constant
{
    public const ORDER_STATUS = [
        'PENDING' => 'pending',
        'DITERIMA' => 'diterima',
        'DIPROSES' => 'diproses',
        'SELESAI' => 'selesai',
        'DITOLAK' => 'ditolak',
    ];
    public const MENU_CATEGORIES = [
        'makanan' => 'Makanan',
        'minuman' => 'Minuman',
        'snack'    => 'Snack',
        'lainnya'  => 'Lainnya',
    ];
    public const STATUS_COLORS = [
        'pending'   => 'secondary',
        'diterima'  => 'info',
        'diproses'  => 'warning',
        'selesai'   => 'success',
        'ditolak'   => 'danger',
    ];

    // public const STATUS_LABELS = [
    //     'pending'   => 'Menunggu Konfirmasi',
    //     'diterima'  => 'Diterima',
    //     'diproses'  => 'Sedang Diproses',
    //     'selesai'   => 'Selesai',
    //     'ditolak'   => 'Ditolak',
    // ];

    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';
    public static function statusList()
    {
        return [
            self::STATUS_ACCEPTED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_COMPLETED => 'Selesai',
        ];
    }

    // kamu bisa tambahkan constant lain seperti APP_NAME, INVENTORY_TYPE, dll
}
