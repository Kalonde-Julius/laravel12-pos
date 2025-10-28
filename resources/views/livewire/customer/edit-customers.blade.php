<div>
    <form wire:submit="save">
        {{ $this->form }}

          <x-filament::button type="submit" class="m-3" color="info"
            icon="heroicon-m-paper-airplane" icon-position="after">
            Submit
        </x-filament::button>
        
    </form>

    <x-filament-actions::modals />
</div>
