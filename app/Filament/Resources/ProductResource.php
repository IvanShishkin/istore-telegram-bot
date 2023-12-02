<?php

namespace App\Filament\Resources;

use App\Domain\Products\Models\Product;
use App\Domain\Store\Models\Order;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $recordTitleAttribute = 'Товары';
    protected static ?string $pluralModelLabel = 'Товары';

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Название')
                    ->required(),
                Forms\Components\Toggle::make('active')
                    ->label('Активность')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->autosize()
                    ->label('Описание')
                    ->required(),
                Forms\Components\FileUpload::make('image_path')

                    ->image(),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('stock')
                    ->numeric()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Активность'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Название'),
                Tables\Columns\TextColumn::make('price')
                    ->formatStateUsing(fn(Product $order) => $order->price . '💎')
                    ->label('Цена')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('stock')->label('Количество')

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
