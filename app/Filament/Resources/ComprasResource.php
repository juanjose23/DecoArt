<?php

namespace App\Filament\Resources;
use App\Filament\Resources\ComprasResource\Widgets\ComprasWidget;
use App\Filament\Resources\ComprasResource\Pages;
use App\Models\Compras;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\DetalleProducto;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use App\Enums\CompraStatus;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class ComprasResource extends Resource
{
    protected static ?string $model = Compras::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Compras';

    protected static ?string $navigationGroup = 'Compras';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema(static::getDetailsFormSchemaupdate())

                            ->columns(1),

                        Forms\Components\Section::make('Detalles de la compra')

                            ->schema([
                                static::getItemsRepeater(),
                            ]),

                    ])
                    ->columnSpan(['lg' => fn(?Compras $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Creado')
                            ->content(fn(Compras $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Ultima actualización')
                            ->content(fn(Compras $record): ?string => $record->updated_at?->diffForHumans()),
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('costo_envio')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->reactive()
                                    ->afterStateUpdated(fn($set, $state, $get) => self::updateTotal($set, $get)),

                                Forms\Components\TextInput::make('costo_aduana')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->reactive()
                                    ->afterStateUpdated(fn($set, $state, $get) => self::updateTotal($set, $get)),

                                Forms\Components\TextInput::make('iva')
                                    ->required()
                                    ->numeric()
                                    ->readOnly()
                                    ->reactive(),

                                Forms\Components\TextInput::make('subtotal')
                                    ->required()
                                    ->numeric()
                                    ->readOnly()
                                    ->reactive()
                                    ->label('Subtotal de la compra'),

                                Forms\Components\TextInput::make('total')
                                    ->required()
                                    ->numeric()
                                    ->readOnly()
                                    ->reactive()
                                    ->label('Total de la compra'),


                            ]),


                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn(?Compras $record) => $record === null),


            ])
            ->columns(3);

    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Responsable')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('proveedor.nombre')
                    ->label('Proveedor')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_recepcion')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('iva')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
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
                    ->sortable(), // Permite que esta columna sea ordenable

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Responsable')
                    ->options(User::all()->pluck('name', 'id')) // Obtiene todos los usuarios
                    ->placeholder('Seleccionar Responsable'),

                Tables\Filters\SelectFilter::make('proveedor_id')
                    ->label('Proveedor')
                    ->options(Proveedor::all()->pluck('nombre', 'id')) // Obtiene todos los proveedores
                    ->placeholder('Seleccionar Proveedor'),

                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options(CompraStatus::class) // Obtiene todos los estados como opciones
                    ->placeholder('Seleccionar Estado'),

                /* Tables\Filters\DateFilter::make('fecha_recepcion')
                     ->label('Fecha de Recepción')
                     ->placeholder('Seleccionar Fecha'),*/

                /* Tables\Filters\RangeFilter::make('subtotal')
                     ->label('Subtotal')
                     ->placeholder('Rango de Subtotal'),

                 Tables\Filters\RangeFilter::make('iva')
                     ->label('IVA')
                     ->placeholder('Rango de IVA'),

                 Tables\Filters\RangeFilter::make('total')
                     ->label('Total')
                     ->placeholder('Rango de Total'),*/
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn($record) => in_array($record->estado, [4])),


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
            'index' => Pages\ListCompras::route('/'),
            'create' => Pages\CreateCompras::route('/create'),
            'edit' => Pages\EditCompras::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ComprasWidget::class,
        ];
    }
    public static function getDetailsFormSchema(): array
    {
        return [

            Forms\Components\Hidden::make("user_id")
                ->default(Auth::user()->id),
            Forms\Components\TextInput::make('codigo')
                ->default('OR-' . random_int(100000, 999999))
                ->label('Codigo de compra')
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32),

            Forms\Components\Select::make('proveedor_id')
                ->relationship('proveedor', 'nombre')
                ->preload()
                ->searchable()
                ->required(),
            Forms\Components\Datepicker::make('fecha_recepcion')
                ->label('Fecha de recepcion')
                ->required(),

            Forms\Components\ToggleButtons::make('estado')
                ->inline()
                ->options([
                    CompraStatus::Nueva->value => CompraStatus::Nueva->getLabel(),


                ])
                ->colors([
                    CompraStatus::Nueva->value => CompraStatus::Nueva->getColor(),


                ])
                ->icons([
                    CompraStatus::Nueva->value => CompraStatus::Nueva->getIcon(),

                ])
                ->required(),



            Forms\Components\MarkdownEditor::make('notas')
                ->label('Notas')
                ->columnSpan('full'),
        ];
    }
    public static function getDetailsFormSchemaupdate(): array
    {
        return [

            Forms\Components\Hidden::make("user_id")
                ->default(Auth::user()->id),
            Forms\Components\TextInput::make('codigo')
                ->default('OR-' . random_int(100000, 999999))
                ->label('Codigo de compra')
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32),

            Forms\Components\Select::make('proveedor_id')
                ->relationship('proveedor', 'nombre')
                ->preload()
                ->searchable()
                ->required(),
            Forms\Components\Datepicker::make('fecha_recepcion')
                ->label('Fecha de recepcion')
                ->required(),

            Forms\Components\ToggleButtons::make('estado')
                ->inline()
                ->options([
                    CompraStatus::Nueva->value => CompraStatus::Nueva->getLabel(),
                    CompraStatus::Aprobada->value => CompraStatus::Aprobada->getLabel(),
                    CompraStatus::Cancelada->value => CompraStatus::Cancelada->getLabel(),
                ])
                ->colors([
                    CompraStatus::Nueva->value => CompraStatus::Nueva->getColor(),
                    CompraStatus::Aprobada->value => CompraStatus::Aprobada->getColor(),
                    CompraStatus::Cancelada->value => CompraStatus::Cancelada->getColor(),


                ])
                ->icons([
                    CompraStatus::Nueva->value => CompraStatus::Nueva->getIcon(),
                    CompraStatus::Aprobada->value => CompraStatus::Aprobada->getIcon(),
                    CompraStatus::Cancelada->value => CompraStatus::Cancelada->getIcon(),
                ])
                ->required(),



            Forms\Components\MarkdownEditor::make('notas')
                ->label('Notas')
                ->columnSpan('full'),
        ];
    }
    protected static function updateTotal(callable $set, callable $get): void
    {
        $detalles = $get('detalleCompras') ?? [];

        $subtotal = 0;
        $iva = 0;

        foreach ($detalles as $detalle) {
            $cantidad = floatval($detalle['cantidad'] ?? 0);
            $precio = floatval($detalle['precio_unitario'] ?? 0);
            $ivaPorcentaje = floatval($detalle['iva_unitario'] ?? 0); // porcentaje

            $subtotalItem = $cantidad * $precio;
            $subtotal += $subtotalItem;

            $iva += $subtotalItem * ($ivaPorcentaje / 100);
        }

        $costoEnvio = floatval($get('costo_envio') ?? 0);
        $costoAduana = floatval($get('costo_aduana') ?? 0);
        $total = $subtotal + $iva + $costoEnvio + $costoAduana;

        $set('subtotal', number_format($subtotal, 2, '.', ''));
        $set('iva', number_format($iva, 2, '.', ''));
        $set('total', number_format($total, 2, '.', ''));
    }


    public static function getCostos(): array
    {
        return [
            Forms\Components\Section::make('Costos de la compra')
                ->description('Resumen de costos calculados e ingresados manualmente.')
                ->schema([
                    Grid::make(2)
                        ->schema([

                            // Costos manuales
                            Forms\Components\Card::make([
                                Forms\Components\TextInput::make('costo_envio')
                                    ->label('Costo de Envío')
                                    ->required()
                                    ->numeric()
                                    ->lazy() // Solo actualiza cuando el campo pierde el foco
                                    ->default(0)
                                    ->afterStateUpdated(fn(callable $set, $state, $get) => self::updateTotal($set, $get)),

                                Forms\Components\TextInput::make('costo_aduana')
                                    ->label('Costo de Aduana')
                                    ->required()
                                    ->numeric()
                                    ->lazy()
                                    ->default(0)
                                    ->afterStateUpdated(fn(callable $set, $state, $get) => self::updateTotal($set, $get)),

                            ])
                                ->columnSpan(1)
                                ->description('Costos manuales'),

                            // Cálculos automáticos
                            Forms\Components\Card::make([
                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal de la compra')
                                    ->numeric()
                                    ->readOnly()
                                    ->reactive()
                                    ->dehydrated(),

                                Forms\Components\TextInput::make('iva')
                                    ->label('IVA Total')
                                    ->numeric()
                                    ->readOnly()
                                    ->reactive()
                                    ->dehydrated(),

                                Forms\Components\TextInput::make('total')
                                    ->label('Total de la compra')
                                    ->numeric()
                                    ->readOnly()
                                    ->reactive()
                                    ->dehydrateStateUsing(function ($state, callable $get) {
                                        $ivatotal = $get('iva') ?? 0;
                                        $subtotal = $get('subtotal') ?? 0;
                                        $costoEnvio = $get('costo_envio') ?? 0;
                                        $costoAduana = $get('costo_aduana') ?? 0;

                                        return floatval($ivatotal) + floatval($subtotal) + floatval($costoEnvio) + floatval($costoAduana);
                                    })
                                    ->dehydrated(),
                            ])
                                ->columnSpan(1)
                                ->description('Totales calculados'),
                        ]),
                ])
                ->columns(1), // para que solo haya una sección con dos columnas dentro
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function getItemsRepeater(): Repeater
    {
        return
            Repeater::make('detalleCompras')
                ->relationship()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('detalleproducto_id')
                                ->label('Productos')
                                ->searchable()
                                ->getSearchResultsUsing(function (string $search) {
                                    return \App\Models\DetalleProducto::query()
                                        ->select('id', 'producto_id', 'color_id', 'marca_id', 'material_id')
                                        ->with([
                                            'producto:id,nombre',
                                            'color:id,nombre',
                                            'marca:id,nombre',
                                            'material:id,nombre',
                                        ])
                                        ->whereHas(
                                            'producto',
                                            fn($query) =>
                                            $query->where('nombre', 'like', "%{$search}%")
                                        )
                                        ->orWhereHas(
                                            'color',
                                            fn($query) =>
                                            $query->where('nombre', 'like', "%{$search}%")
                                        )
                                        ->orWhereHas(
                                            'marca',
                                            fn($query) =>
                                            $query->where('nombre', 'like', "%{$search}%")
                                        )
                                        ->orWhereHas(
                                            'material',
                                            fn($query) =>
                                            $query->where('nombre', 'like', "%{$search}%")
                                        )
                                        ->limit(20)
                                        ->get()
                                        ->mapWithKeys(function ($detalleProducto) {
                                            $productoNombre = $detalleProducto->producto->nombre ?? 'Sin Producto';
                                            $color = $detalleProducto->color->nombre ?? 'Sin Color';
                                            $marca = $detalleProducto->marca->nombre ?? 'Sin Marca';
                                            $material = $detalleProducto->material->nombre ?? 'Sin Material';

                                            return [
                                                $detalleProducto->id => "{$productoNombre} (Color: {$color}, Marca: {$marca}, Material: {$material})"
                                            ];
                                        });
                                })
                                ->getOptionLabelUsing(function ($value): ?string {
                                    $detalle = \App\Models\DetalleProducto::with([
                                        'producto:id,nombre',
                                        'color:id,nombre',
                                        'marca:id,nombre',
                                        'material:id,nombre',
                                    ])->find($value);

                                    if (!$detalle)
                                        return null;

                                    $productoNombre = $detalle->producto->nombre ?? 'Sin Producto';
                                    $color = $detalle->color->nombre ?? 'Sin Color';
                                    $marca = $detalle->marca->nombre ?? 'Sin Marca';
                                    $material = $detalle->material->nombre ?? 'Sin Material';

                                    return "{$productoNombre} (Color: {$color}, Marca: {$marca}, Material: {$material})";
                                })
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->required(),

                            Forms\Components\TextInput::make('cantidad')
                                ->required()
                                ->numeric()
                                ->live()
                                ->dehydrated()

                                ->default(1),

                            Forms\Components\TextInput::make('precio_unitario')
                                ->required()
                                ->numeric()
                                ->reactive(),

                            Forms\Components\TextInput::make('iva_unitario')
                                ->required()
                                ->numeric()
                                ->label('IVA %')
                                ->default(0.00),


                            Forms\Components\Placeholder::make('subtotal')
                                ->label('Subtotal ')
                                ->content(function (Get $get): string {
                                    return number_format($get('precio_unitario') * $get('cantidad'), 2);
                                }),
                            Forms\Components\Placeholder::make('Total')
                                ->label('Total')
                                ->content(function (Get $get): string {
                                    $cantidad = floatval($get('cantidad') ?? 0);
                                    $precio = floatval($get('precio_unitario') ?? 0);
                                    $ivaPorcentaje = floatval($get('iva_unitario') ?? 0);

                                    $subtotal = $cantidad * $precio;
                                    $iva = $subtotal * ($ivaPorcentaje / 100);
                                    $total = $subtotal + $iva;

                                    return number_format($total, 2);
                                }),


                        ]),
                ])
                ->reactive()


                ->minItems(1)
                ->maxItems(50)
                ->columnSpanFull();

    }


}