<?php

namespace App\Exports;

use App\Models\NewsletterSubscriber;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnWidths
};

class SubscribersExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    /**
     * Fetch all subscribers.
     */
    public function collection()
    {
        return NewsletterSubscriber::all();
    }

    /**
     * Set the column headings.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Email',
            'Status',
            'Subscribed On',
        ];
    }

    /**
     * Map each model instance to a row.
     */
    public function map($subscriber): array
    {
        return [
            $subscriber->id,
            $subscriber->email,
            $subscriber->status ? 'Active' : 'Inactive',
            $subscriber->created_at->toDateTimeString(),
        ];
    }

    /**
     * Optionally set column widths.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,   // ID
            'B' => 40,   // Email
            'C' => 15,   // Status
            'D' => 25,   // Subscribed On
        ];
    }
}
