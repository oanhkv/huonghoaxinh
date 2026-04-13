<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RevenueReportExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly Collection $orders)
    {
    }

    public function collection()
    {
        return $this->orders->map(function ($order) {
            return [
                'order_code' => $order->order_code ?? ('HH' . $order->id),
                'customer_name' => $order->user->name ?? 'Khach vang lai',
                'status' => $order->status,
                'total_amount' => (float) $order->total_amount,
                'phone' => $order->phone,
                'shipping_address' => $order->shipping_address,
                'created_at' => optional($order->created_at)->format('d/m/Y H:i'),
                'note' => $order->note,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Ma don',
            'Khach hang',
            'Trang thai',
            'Tong tien',
            'So dien thoai',
            'Dia chi giao',
            'Ngay dat',
            'Ghi chu',
        ];
    }
}
