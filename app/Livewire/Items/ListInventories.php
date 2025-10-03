<?php

namespace App\Livewire\Items;

use Livewire\Component;
use App\Models\Inventory;
use Filament\Tables\Table;
use Filament\Actions\Action;
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

class ListInventories extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Inventory::query())
            ->columns([

                TextColumn::make('item.name')
                    ->label('Item / Product')
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Added On')
                    ->dateTime('D, d, M, Y: h:i A')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Add New Inventory')
                    ->url(fn (): string => route('inventory.create'))
                    ->openUrlInNewTab(),
            ])
            ->recordActions([
                 Action::make('edit')
                    ->url(fn (Inventory $record): string => route('inventory.update', $record))
                    ->openUrlInNewTab(),

                 Action::make('Delete')
               // ->Icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->color('danger')
                ->action(fn (Inventory $record) => $record->delete())
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Inventory deleted successfully')
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
        return view('livewire.items.list-inventories');
    }
}
