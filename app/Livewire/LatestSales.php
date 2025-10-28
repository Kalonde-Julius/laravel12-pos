<?php

namespace App\Livewire;

use App\Models\Sale;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class LatestSales extends TableWidget
{
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

                TextColumn::make('salesItem.items.price')
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
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
