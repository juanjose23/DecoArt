<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecepcionesResource\Pages;
use App\Models\Recepciones;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\DetalleProducto;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use App\Enums\CompraStatus;
use Closure;
use App\Models\Inventarios;
class RecepcionesResource extends Resource
{
    protected static ?string $model = Recepciones::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Recepciones';

    protected static ?string $navigationGroup = 'Compras';
    public static function form(Form $form): Form
    {
        $form

            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        // Columna 1: Información general de la recepción
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Información General')
                                    ->schema([
                                        Forms\Components\Select::make('compras_id')
                                            ->label('Código de Compra')
                                            ->relationship('compras', 'codigo')
                                            ->disabled()
                                            ->required(),


                                        Forms\Components\Placeholder::make('')
                                            ->label('Nombre del Proveedor')
                                            ->content(fn(Recepciones $record): ?string => $record->fecha_recepcion),

                                        Forms\Components\Placeholder::make('nombre')
                                            ->label('Nombre del Proveedor')
                                            ->content(fn(Recepciones $record): ?string => $record->compras->proveedor->nombre ?? 'Sin proveedor'),

                                        Forms\Components\ToggleButtons::make('estado')
                                            ->inline()
                                            ->options([
                                                CompraStatus::EnProceso->value => CompraStatus::EnProceso->getLabel(),
                                                CompraStatus::Recepcionada->value => CompraStatus::Recepcionada->getLabel(),
                                                CompraStatus::Cancelada->value => CompraStatus::Cancelada->getLabel(),
                                            ])
                                            ->required(),
                                    ])
                                    ->columns(1),
                            ])
                            ->columnSpan(1),

                        // Columna 2: Detalles del producto
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Detalles de la compra')
                                    ->schema([
                                        Forms\Components\Repeater::make('detalleRecepciones')
                                            ->relationship('detalleRecepciones')
                                            ->schema([
                                                Forms\Components\Grid::make(1)
                                                    ->schema([
                                                        Forms\Components\Select::make('detalleproducto_id')
                                                            ->label('Producto')
                                                            ->options(function () {
                                                                return DetalleProducto::with('producto')->get()->mapWithKeys(function ($detalleProducto) {
                                                                    $productoNombre = $detalleProducto->producto->nombre;
                                                                    $color = $detalleProducto->color->nombre ?? 'Sin Color';
                                                                    $marca = $detalleProducto->marca->nombre ?? 'Sin Marca';
                                                                    $material = $detalleProducto->material->nombre ?? 'Sin Material';

                                                                    return [
                                                                        $detalleProducto->id => "{$productoNombre} (Color: {$color}, Marca: {$marca}, Material: {$material})"
                                                                    ];
                                                                });
                                                            })
                                                            ->afterStateUpdated(function ($state, callable $set) {
                                                                // Obtén el stock máximo del inventario correspondiente
                                                                $inventario = Inventarios::where('detalleproducto_id', $state)->first();

                                                                if ($inventario) {
                                                                    // Establecer la regla de validación basada en el stock máximo
                                                                    $set('cantidad_recibida_rule', "max:{$inventario->stock_maximo}");
                                                                } else {
                                                                    // Si no hay inventario, puedes eliminar la regla o establecer alguna por defecto
                                                                    $set('cantidad_recibida_rule', null);
                                                                }
                                                            })
                                                            ->disabled(),

                                                        Forms\Components\TextInput::make('cantidad_esperada')
                                                            ->label('Cantidad Esperada')
                                                            ->required()
                                                            ->numeric(),

                                                        Forms\Components\TextInput::make('cantidad_recibida')
                                                            ->label('Cantidad Recibida')
                                                            ->required()
                                                            ->numeric()
                                                            ->rule(function ($get) {
                                                                // Obtén el ID del producto
                                                                $detalleProductoId = $get('detalleproducto_id'); // Asegúrate de que el ID esté disponible en este contexto
                                                                // Obtén el stock máximo usando la función
                                                                $stockDisponible = self::getStockDisponible($detalleProductoId);
                                                                if ($stockDisponible !== null) {
                                                                    $stockActual = $stockDisponible['stock_actual'];
                                                                    $stockMaximo = $stockDisponible['stock_maximo'];
                                                                    $cantidadEsperada = $get('cantidad_esperada');
                                                    
                                                                    // Verifica que la cantidad recibida no exceda el stock disponible
                                                                    return [
                                                                        'max:' . $stockMaximo-$stockActual, // Verifica que la cantidad recibida no exceda el stock actual
                                                                        'not_in:' . ($stockMaximo - $stockActual - $cantidadEsperada), 
                                                                    ];
                                                                }

                                                                return [];
                                                            }),
                                                        Forms\Components\DatePicker::make('fecha_vencimiento')
                                                            ->label('Fecha de Expiración')
                                                            ->required(function ($get) {
                                                                $detalleProducto = DetalleProducto::find($get('detalleproducto_id'));
                                                                return $detalleProducto?->producto->caducidad ?? false;
                                                            })
                                                            ->hidden(function ($get) {
                                                                // Oculta el campo si el producto no es caducable
                                                                $detalleProducto = DetalleProducto::find($get('detalleproducto_id'));
                                                                return !($detalleProducto?->producto->caducidad ?? false);
                                                            })

                                                            ->displayFormat('Y-m-d H:i'),
                                                    ]),
                                            ])
                                            ->minItems(1)
                                            ->maxItems(10)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                            ])
                            ->columnSpan(2),
                    ]),
            ]);

        return $form;
    }
    protected static function getStockDisponible($detalleproductoId): ?array
{
    // Busca el inventario correspondiente al detalle del producto
    $inventario = Inventarios::where('detalleproducto_id', $detalleproductoId)->first();

     // Si el inventario existe, devuelve el stock actual y el stock máximo, de lo contrario, null
     if ($inventario) {
        return [
            'stock_actual' => $inventario->stock_actual,
            'stock_maximo' => $inventario->stock_maximo,
        ];
    }

    return null; // Si no hay inventario, devuelve null
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                Tables\Columns\TextColumn::make('compras.codigo')
                    ->label('Código de Compra')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => $state ?? 'Sin código'),
                Tables\Columns\TextColumn::make('compras.proveedor.nombre')
                    ->label('Proveedor de Compra')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => $state ?? 'Sin código'),
                Tables\Columns\TextColumn::make('compras.fecha_recepcion')
                    ->label('Fecha de Recepción')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado') // Asigna una etiqueta para la columna
                    ->badge()
                    ->colors([
                        CompraStatus::Nueva->value => CompraStatus::Nueva->getColor(),

                    ])
                    ->icons([
                        CompraStatus::Nueva->value => CompraStatus::Nueva->getIcon(),
                    ])
                    ->formatStateUsing(fn($state) => CompraStatus::from($state)->getLabel()) // Muestra la etiqueta correspondiente
                    ->sortable(),

            ])
            ->filters([
                // Puedes agregar filtros aquí si es necesario
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        1 => 'Pendiente',
                        2 => 'Aceptada',
                        3 => 'En Proceso',
                        4 => 'Devuelta',
                        5 => 'Cancelada',
                    ])
                    ->placeholder('Todos los estados'), // Placeholder que muestra todos los registros

                // Filtro por Fecha de Recepción
                Filter::make('fecha_recepcion')
                    ->label('Fecha de Recepción')
                    ->form([
                        Forms\Components\Datepicker::make('desde')
                            ->label('Desde'),
                        Forms\Components\Datepicker::make('hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn($query, $date) => $query->whereDate('fecha_recepcion', '>=', $date))
                            ->when($data['hasta'], fn($query, $date) => $query->whereDate('fecha_recepcion', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()

                    ->hidden(fn($record) => in_array($record->estado, [4, 5])),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function query(): Builder
    {
        return parent::query()->with('compras.proveedor');
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
            'index' => Pages\ListRecepciones::route('/'),

            'edit' => Pages\EditRecepciones::route('/{record}/edit'),
        ];
    }
}