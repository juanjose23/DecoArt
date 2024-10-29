<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventariosResource\Pages;
use App\Filament\Resources\InventariosResource\RelationManagers;
use App\Models\Inventarios;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use App\Models\DetalleProducto;
use Filament\Forms\Components\TextInput;

class InventariosResource extends Resource
{
    protected static ?string $model = Inventarios::class;
    protected static ?string $navigationLabel = 'Inventario';

    protected static ?string $navigationGroup = 'Inventario';
    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('stock_minimo')
                ->label('Stock Mínimo')
                ->required()
                ->numeric() // Asegura que solo se puedan ingresar números
                ->minValue(0), // Establece el valor mínimo
            TextInput::make('stock_maximo')
                ->label('Stock Máximo')
                ->required()
                ->numeric() // Asegura que solo se puedan ingresar números
                ->minValue(0), // Establece el valor mínimo
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('detalleProducto.producto.subcategoria.categoria.nombre')->label('Categoría') ->searchable(),
                Tables\Columns\TextColumn::make('detalleProducto.producto.subcategoria.nombre')->label('Subcategoría') ->searchable(),
                Tables\Columns\TextColumn::make('detalleproducto_id')
                    ->label('Producto')
                    ->getStateUsing(function ($record) {
                        $detalleProducto = $record->detalleProducto;
                        if ($detalleProducto && $detalleProducto->producto) {
                            $productoNombre = $detalleProducto->producto->nombre;
                            $color = $detalleProducto->color->nombre ?? 'Sin Color';
                            $marca = $detalleProducto->marca->nombre ?? 'Sin Marca';
                            $material = $detalleProducto->material->nombre ?? 'Sin Material';
                            return "{$productoNombre} (Color: {$color}, Marca: {$marca}, Material: {$material})";
                        }
                        return 'Desconocido';
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock_actual')
                    ->label('Stock Actual')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_minimo')
                    ->label('Stock Mínimo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_maximo')
                    ->label('Stock Máximo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_disponible')
                    ->label('Stock Disponible')
                    ->sortable(),
            ])
            ->filters([
              
                SelectFilter::make('stock_minimo')
                    ->label('Stock Mínimo')
                    ->options([
                        '0' => '0',
                        '1' => '1',
                        '5' => '5',
                        '10' => '10',
                        '20' => '20',
                        // Agrega más opciones según sea necesario
                    ]),
                SelectFilter::make('stock_maximo')
                    ->label('Stock Máximo')
                    ->options([
                        '50' => '50',
                        '100' => '100',
                        '200' => '200',
                        // Agrega más opciones según sea necesario
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListInventarios::route('/'),
            'view' => Pages\ViewInventarios::route('/{record}'),
            'edit' => Pages\EditInventarios::route('/{record}/edit'),
        ];
    }
}
