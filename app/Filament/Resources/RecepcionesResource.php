<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecepcionesResource\Pages;
use App\Filament\Resources\RecepcionesResource\RelationManagers;
use App\Models\Recepciones;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Compras;
use App\Models\DetalleProducto;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class RecepcionesResource extends Resource
{
    protected static ?string $model = Recepciones::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Recepciones';

    protected static ?string $navigationGroup = 'Compras';
    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informacion de Recepcion')
                            ->schema([
                                Forms\Components\Select::make('compras_id')
                                    ->label('Codigo de compra')
                                    ->relationship('compras', 'codigo')
                                    ->disabled()
                                    ->required(),

                                Forms\Components\TextInput::make('fecha_recepcion')
                                    ->label('Fecha de Entrega')
                                    ->required()
                                    ->maxLength(50)
                                    ->readOnly(),
                                Forms\Components\Placeholder::make('nombre')
                                    ->label('Nombre')
                                    ->content(fn(Recepciones $record): ?string => $record->compras->proveedor->nombre),


                                // Solo lectura

                            ])->columns(2),

                    ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detalles del Producto')
                            ->schema([
                                Forms\Components\Repeater::make('detalleRecepciones')
                                    ->relationship('detalleRecepciones')
                                    ->schema([
                                        Forms\Components\Grid::make(1)
                                            ->schema([
                                                Forms\Components\Select::make('detalleproducto_id')
                                                    ->label('Productos')
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
                                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                                    ->searchable()
                                                    ->distinct()
                                                    ->required(),
                                                Forms\Components\TextInput::make('cantidad_esperada')
                                                    ->required()
                                                    ->numeric()
                                                    ->readOnly(),
                                                Forms\Components\TextInput::make('cantidad_recibidad')
                                                    ->required()
                                                    ->numeric()
                                                    ->readOnly()

                                            ]),


                                    ])
                                    ->minItems(1)
                                    ->maxItems(10)
                                    ->columnSpanFull()

                            ])
                            ->columns(2)
                    ])
            ]);
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
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            1 => 'Pendiente',
                            2 => 'Aceptada',
                            3 => 'En Proceso',
                            4 => 'Devuelta',
                            5 => 'Cancelada',
                            default => 'Desconocido',
                        };
                    })
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        1 => 'info', // Pendiente
                        2 => 'success',   // Aceptada
                        3 => 'warning',   // En Proceso
                        4 => 'danger',    // Devuelta
                        5 => 'gray',      // Cancelada
                        default => 'neutral', // Color neutral para estados desconocidos
                    })
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
                Tables\Actions\EditAction::make(),
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
            'create' => Pages\CreateRecepciones::route('/create'),
            'edit' => Pages\EditRecepciones::route('/{record}/edit'),
        ];
    }
}