<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Illuminate\Contracts\View\View;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;


class ListSales extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Sale::query()
            ->with(['customer', 'paymentMethod', 'salesItems', 'item']))
            ->columns([

                // Define your table columns here
                TextColumn::make('id')
                    ->label('Sale ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('salesItems.item.name')
                    ->label('Sold Items')
                   // ->limitList(2)
                    ->expandableLimitedList(),

                TextColumn::make('salesItems.quantity')
                    ->label('Quantity')
                   // ->limitList(2)
                    ->expandableLimitedList(),

                TextColumn::make('salesItems.item.price')
                    ->label('Unit Price')
                    ->expandableLimitedList(),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->sortable(),

                TextColumn::make('tax')
                    ->label('Tax')
                    ->sortable(),

                TextColumn::make('discount')
                    ->label('Discount')
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->sortable(),

                TextColumn::make('paid_amount')
                    ->label('Paid Amount')
                    ->sortable(),

                TextColumn::make('change')
                    ->label('Change')
                    ->sortable(),

                TextColumn::make('paymentMethod.name')
                    ->label('Payment Method')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('sale_date')
                    ->label('Sale Date')
                    ->dateTime('D, d, M, Y: h:i A')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('View')
                    ->label('View sale')
                    ->icon('heroicon-o-document')
                    ->url(fn (Sale $record): string => route('sale.details', $record)),

                Action::make('Delete')
                // ->Icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (Sale $record) => $record->delete())
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Sale deleted successfully')
                            //->body('Inventory Deleted successfully.')
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.sales.list-sales');
    }
}
