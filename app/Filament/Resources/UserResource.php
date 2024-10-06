<?php

namespace App\Filament\Resources;
use Filament\Support\Colors\Color;
use Rawilk\FilamentPasswordInput\Password;
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
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ActionGroup;
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'auth/users';
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

                                Toggle::make('generar')
                                    ->label('Generar Contraseña'),


                                Password::make('password')
                                    ->label('Contraseña')
                                    ->regeneratePassword()
                                    ->maxLength(8)
                                    ->inlineSuffix(),
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



                            ])->columns(2),
                    ])->columnSpan(['lg' => 2]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


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
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Rol')
                    ->badge()
                    ->sortable(),
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
                        1 => 'success',
                        2 => 'warning',
                        default => 'danger'
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
                #  Tables\Actions\ViewAction::make(),
               
                ActionGroup::make([
                    Tables\Actions\EditAction::make() 
                    ->icon('heroicon-m-pencil-square'),
                    Tables\Actions\DeleteAction::make()
                ])
                    ->visible(fn(User $record) => $record->roles !== 2),
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
                 
                    RestoreBulkAction::make(),
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
