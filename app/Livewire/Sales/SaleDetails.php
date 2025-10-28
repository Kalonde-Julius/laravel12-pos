<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleDetails extends Component
{
    public Sale $sale;

    public function mount($record): void
    {
        $this->sale = Sale::with(['customer', 'paymentMethod', 'salesItems', 'salesItems.item'])
            ->findOrFail($record);
    }

    public function exportSale($id)
    {
        $sale = Sale::with(['customer', 'paymentMethod', 'salesItems', 'salesItems.item'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('pdf.sale', compact('sale'));

        return $pdf->download('sale_' . $sale->id . '.pdf');
    }

    public function render()
    {
        return view('livewire.sales.sale-details', [
            'sale' => $this->sale,
        ]);
    }
}
