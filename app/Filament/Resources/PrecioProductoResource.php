<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrecioProductoResource\Pages;
use App\Filament\Resources\PrecioProductoResource\RelationManagers;
use App\Models\PrecioProducto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use App\Models\DetalleProducto;

class PrecioProductoResource extends Resource
{
    protected static ?string $model = PrecioProducto::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Catalogos';

    protected static ?int $navigationSort = 0;
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Seleccionar Producto')
                        ->schema([
                            // Select para productos agrupados por categoría/subcategoría
                            Select::make('detalleproducto_id')
                                ->label('Productos')
                                ->options(function () {
                                    $productos = (new PrecioProducto())->ObtenerProductosConCategorias();
    
                                    $options = [];
    
                                    foreach ($productos as $categoria => $subcategorias) {
                                        foreach ($subcategorias as $subcategoria => $items) {
                                            foreach ($items as $item) {
                                                $options[$item['id']] = "{$categoria} -> {$subcategoria} -> {$item['nombre']} (Marca: {$item['marca']}, Material: {$item['material']}, Color: {$item['color']})";
                                            }
                                        }
                                    }
    
                                    return $options;
                                })
                                ->placeholder('Selecciona un producto')
                                ->searchable()
                                ->reactive() // Campo reactivo
                                ->required()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state) {
                                        // Buscar el precio del producto relacionado
                                        $detalleProducto = DetalleProducto::find($state);
                                        if ($detalleProducto) {
                                            $set('precio', $detalleProducto->producto->precio);
                                        }
                                    }
                                }),
    
                            // Mostrar el precio actual del producto seleccionado
                            TextInput::make('precio')
                                ->label('Precio Actual del Producto')
                                ->disabled()
                                ->dehydrated(false), // Campo solo informativo
    
                       
                        ])
                        ->columns(2), // Organizar en dos columnas
    
                    Forms\Components\Section::make('Precios y Fechas')
                        ->schema([
                            // Campo para precio, editable por el usuario
                            TextInput::make('precio')
                                ->label('Precio')
                                ->numeric()
                                ->required(),
    
                            // Fechas de inicio y fin
                            DatePicker::make('fecha_inicio')
                                ->label('Fecha de Inicio')
                                ->required(),
    
                            DatePicker::make('fecha_fin')
                                ->label('Fecha de Fin'),
                        ])
                        ->columns(2), // Organizar en dos columnas
    
                    Forms\Components\Section::make('Estado del Producto')
                        ->schema([
                            // Estado activo/inactivo del producto
                            Select::make('estado')
                                ->label('Estado')
                                ->options([
                                    1 => 'Activo',
                                    0 => 'Inactivo',
                                ])
                                ->default(1)
                                ->required(),
                        ])
                        ->columns(1), // Organizar en una columna
                ])
                ->columnSpan(['lg' => 2]),
        ]);
    

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListPrecioProductos::route('/'),
            'create' => Pages\CreatePrecioProducto::route('/create'),
            'edit' => Pages\EditPrecioProducto::route('/{record}/edit'),
        ];
    }
}
