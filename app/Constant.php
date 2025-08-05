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
    public const PAYMENT_STATUS = [
        'PAID' => 'paid',
        'UNPAID' => 'unpaid',
    ];
    public const PAYMENT_METHOD = [
        'CASH' => 'cash',
        'DIGITAL' => 'digital',
    ];



}
