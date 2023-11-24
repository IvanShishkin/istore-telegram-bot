@php
    $exists = $this->walletExists();
    $icon = 'heroicon-o-arrow-circle-down';
@endphp

<x-filament::widget>
    <x-filament::card>
        @if($exists)
            <div>Кошелек магазина существует</div>
        @else
            <div>Нажмите чтобы создать кошелек для магазина</div>
            <x-filament::button wire:click="createStoreWallet">Создать кошелек</x-filament::button>
        @endif
    </x-filament::card>
</x-filament::widget>
