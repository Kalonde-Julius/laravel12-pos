<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
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

class ListItems extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Item::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),

                TextColumn::make('price')
                    ->label('Price (UGX)')
                    ->money('UGX', true)
                    ->sortable(),

                TextColumn::make('inventory.quantity')
                    ->label('Current Stock')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Add New Item')
                    ->url(fn (): string => route('item.create'))
                    ->openUrlInNewTab(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->url(fn (Item $record): string => route('item.update', $record))
                    ->openUrlInNewTab(),

                Action::make('Delete')
               // ->Icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->action(fn (Item $record) => $record->delete())
                ->color('danger')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Item deleted Successfully'),
                       // ->body('Item Deleted successfully.'),
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
        return view('livewire.items.list-items');
    }
}
