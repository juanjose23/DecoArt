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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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
                        Forms\Components\Section::make(heading: 'Seleccionar Producto')
                            ->schema([
                                Select::make('detalleproducto_id')
                                    ->label('Productos')
                                    ->options(function () {
                                        // Obtener los productos con la relación adecuada
                                        return DetalleProducto::with('producto')->get()->mapWithKeys(function ($detalleProducto) {
                                            // Formato: Nombre del Producto (Detalles: Color, Marca, Material)
                                            $productoNombre = $detalleProducto->producto->nombre;
                                            $color = $detalleProducto->color->nombre ?? 'Sin Color';
                                            $marca = $detalleProducto->marca->nombre ?? 'Sin Marca';
                                            $material = $detalleProducto->material->nombre ?? 'Sin Material';

                                            return [
                                                $detalleProducto->id => "{$productoNombre} (Color: {$color}, Marca: {$marca}, Material: {$material})"
                                            ];
                                        });
                                    })
                                    ->searchable()
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
                            ->columns(1), // Organizar en dos columnas

                        Forms\Components\Section::make('Precios y Fechas')
                            ->schema([
                                // Campo para precio, editable por el usuario
                                TextInput::make('precio')
                                    ->label('Precio')
                                    ->numeric()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        // Aquí puedes procesar el estado del campo 'precio'
                                        if ($state !== null) {
                                            $set('precio', $state);
                                        }
                                    }),

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
                   
                TextColumn::make('detalleProducto.producto.subcategoria.categoria.nombre')  ->label('Categoría'),
                TextColumn::make('detalleProducto.producto.subcategoria.nombre')  ->label('Subcategoría'),
                TextColumn::make('detalleproducto_id')
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
                }),
                TextColumn::make('precio')
                    ->label('Precio'),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        return $state === 1 ? 'Activo' : 'Inactivo';
                    })
                    ->badge()
                    ->color(fn($state): string => $state === 1 ? 'success' : 'danger')
                    ->sortable(),
            ])
            ->filters([
                 // Filtrar por defecto los productos activos
                 Filter::make('activos')
                ->label('Mostrar solo activos')
                ->query(function ($query) {
                    return $query->where('estado', 1);
                })
                ->default(true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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