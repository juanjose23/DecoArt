<?php

namespace App\Filament\Resources;
use Filament\Tables\Actions\ReplicateAction;

use App\Filament\Resources\ComprasResource\Widgets\ComprasWidget;
use App\Filament\Resources\ComprasResource\Pages;
use App\Models\Compras;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\DetalleProducto;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Wizard;
use App\Enums\CompraStatus;
use Filament\Forms\Get;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
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

                            ->columns(2),

                        Forms\Components\Section::make('Detalles de la compra')

                            ->schema([
                                static::getItemsRepeater(),
                            ]),

                    ])
                    ->columnSpan(['lg' => fn(?Compras $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn(Compras $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn(Compras $record): ?string => $record->updated_at?->diffForHumans()),
                        Forms\Components\Section::make()
                            ->schema(static::getCostosupdate())
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
                Tables\Actions\EditAction::make(),
                ReplicateAction::make(),

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
        $subtotal = array_reduce($get('detalleCompras'), function ($carry, $detalle) {
            return $carry + floatval($detalle['subtotal'] ?? 0);
        }, 0);
        $ivatotal = array_reduce($get('detalleCompras'), function ($carry, $detalle) {
            return $carry + floatval($detalle['iva_unitario'] ?? 0);
        }, 0);
        $iva = $ivatotal;
        $costoEnvio = floatval($get('costo_envio') ?? 0);
        $costoAduana = floatval($get('costo_aduana') ?? 0);

        $total = $subtotal + $iva + $costoEnvio + $costoAduana;

        $set('subtotal', $subtotal);
        $set('iva', $iva);
        $set('total', $total);
    }

    public static function getCostos(): array
    {
        return [
            Forms\Components\Section::make('Costos de la compra')

                ->schema([
                    Grid::make(2)  // Crea un grid de dos columnas
                        ->schema([
                            Forms\Components\TextInput::make('costo_envio')
                                ->required()
                                ->numeric()
                                ->reactive()
                                ->default(0)
                                ->afterStateUpdated(fn(callable $set, $state, $get) => self::updateTotal($set, $get)),

                            Forms\Components\TextInput::make('costo_aduana')
                                ->required()
                                ->numeric()
                                ->reactive()
                                ->default(0)
                                ->afterStateUpdated(fn(callable $set, $state, $get) => self::updateTotal($set, $get)),

                            Forms\Components\TextInput::make('iva')
                                ->required()
                                ->numeric()
                                ->reactive()
                                ->readOnly(),

                            Forms\Components\TextInput::make('subtotal')
                                ->required()
                                ->numeric()
                                ->readOnly()
                                ->label('Subtotal de la compra')
                                ->reactive(),

                            Forms\Components\TextInput::make('total')
                                ->required()
                                ->numeric()
                                ->label('Total de la compra')
                                ->readOnly()
                                ->reactive()
                                ->dehydrateStateUsing(function ($state, callable $get) {
                                    $ivatotal = $get('iva') ?? 0;
                                    $subtotal = $get('subtotal') ?? 0; // Asegúrate de usar 0 si es null
                                    $costoEnvio = $get('costo_envio') ?? 0; // Asegúrate de usar 0 si es null
                                    $costoAduana = $get('costo_aduana') ?? 0; // Asegúrate de usar 0 si es null
                        
                                    return floatval($ivatotal) + floatval($subtotal) + floatval($costoEnvio) + floatval($costoAduana);
                                }),

                        ]),
                ]),
        ];
    }
    public static function getCostosupdate(): array
    {
        return schema([
            Forms\Components\TextInput::make('costo_envio')
                ->required()
                ->numeric()
                ->reactive()
                ->default(0)
                ->afterStateUpdated(fn(callable $set, $state, $get) => self::updateTotal($set, $get)),

            Forms\Components\TextInput::make('costo_aduana')
                ->required()
                ->numeric()
                ->reactive()
                ->default(0)
                ->afterStateUpdated(fn(callable $set, $state, $get) => self::updateTotal($set, $get)),

            Forms\Components\TextInput::make('iva')
                ->required()
                ->numeric()
                ->reactive()
                ->readOnly(),

            Forms\Components\TextInput::make('subtotal')
                ->required()
                ->numeric()
                ->readOnly()
                ->label('Subtotal de la compra')
                ->reactive(),

            Forms\Components\TextInput::make('total')
                ->required()
                ->numeric()
                ->label('Total de la compra')
                ->readOnly()
                ->reactive()
                ->dehydrateStateUsing(function ($state, callable $get) {
                    $ivatotal = $get('iva') ?? 0;
                    $subtotal = $get('subtotal') ?? 0; // Asegúrate de usar 0 si es null
                    $costoEnvio = $get('costo_envio') ?? 0; // Asegúrate de usar 0 si es null
                    $costoAduana = $get('costo_aduana') ?? 0; // Asegúrate de usar 0 si es null
        
                    return floatval($ivatotal) + floatval($subtotal) + floatval($costoEnvio) + floatval($costoAduana);
                }),

        ]);

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
                            Forms\Components\TextInput::make('cantidad')
                                ->required()
                                ->numeric()
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $state, $get) {
                                    $cantidad = floatval($state);
                                    $precio_unitario = floatval($get('precio_unitario'));
                                    $set('subtotal', $cantidad * $precio_unitario);
                                    #   self::updateTotal($set, $get); 
                                })
                                ->default(1),
                            Forms\Components\TextInput::make('precio_unitario')
                                ->required()
                                ->numeric()
                                ->label('Precio Unitario')
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $state, $get) {
                                    $cantidad = floatval($get('cantidad'));
                                    $precio_unitario = floatval($state);
                                    $set('subtotal', $cantidad * $precio_unitario);
                                    # self::updateTotal($set, $get); 
                                })
                                ->default(0.00),
                            Forms\Components\TextInput::make('subtotal')
                                ->required()
                                ->numeric()
                                ->label('Subtotal')
                                ->default(0.00)
                                ->readOnly()
                                ->reactive(),
                            Forms\Components\TextInput::make('iva_unitario')
                                ->required()
                                ->numeric()
                                ->label('Iva Unitario')
                                ->default(0.00)
                        ]),
                ])
                ->reactive()
                ->live(onBlur: true)
                ->afterStateUpdated(function (callable $set, $state, $get) {
                    self::updateTotal($set, $get);
                })
                ->minItems(1)
                ->maxItems(50)
                ->columnSpanFull();

    }


}