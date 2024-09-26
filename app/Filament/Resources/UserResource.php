<?php

namespace App\Filament\Resources;
use Barryvdh\DomPDF\Facade\PDF;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Str;
use Filament\Forms\Components\Toggle;
class UserResource extends Resource
{
    protected static ?string $model = User::class;


    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Seguridad';
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Información del Usuario')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(50),

                                TextInput::make('email')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email', ignoreRecord: true),

                                Toggle::make('generate_password')
                                    ->label('Generar Contraseña'),


                                TextInput::make('password')
                                    ->label('Contraseña Generada')
                                    ->default(fn($get, $set) => $get('generate_password') ? tap(Str::random(12), fn($password) => $set('password', $password)) : $get('password')) 
                                    ->extraInputAttributes(['readonly' => 'readonly']) 
                                    ->dehydrated(fn($get) => !$get('generate_password')),
                                Select::make('estado')
                                    ->label('Estado')
                                    ->options([
                                        2 => 'Verificar',
                                        1 => 'Activo',
                                        0 => 'Inactivo',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('roles')
                                    ->relationship('roles', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable(),



                            ])->columns(2), // Organiza los inputs en dos columnas
                    ])->columnSpan(['lg' => 2]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Columna para mostrar el ID del usuario
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                // Columna para mostrar el nombre
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),

                // Columna para mostrar el correo electrónico
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->sortable()
                    ->searchable(),

                // Columna para mostrar el estado (Activo/Inactivo)
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        if ($state == 1) {
                            return 'Activo';
                        } elseif ($state == 2) {
                            return 'Verificar';
                        } else {
                            return 'Inactivo';
                        }
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        1 => 'success',  // Color para 'Activo', puedes usar 'green' o un valor hexadecimal si prefieres
                        2 => 'warning',  // Color para 'Verificar', puedes usar 'yellow' o un valor hexadecimal
                        default => 'danger'  // Color para 'Inactivo', puedes usar 'red' o un valor hexadecimal
                    })

                    ->sortable(),
                // Columna para la fecha de creación
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Filtro para filtrar por estado (Activo/Inactivo)
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        2 => 'Verificar',
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ]),
            ])
            ->actions([
                Action::make('Verficar')
                    ->action(function (User $record) {
                        return redirect()->route('auth.pdf', ['record' => $record->id]);
                    })
                    ->icon('heroicon-o-newspaper')
                    ->visible(fn(User $record) => $record->estado === 2),

            ])
            ->bulkActions([
                // Acción en masa para eliminar registros seleccionados
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
