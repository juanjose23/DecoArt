<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum CompraStatus: int implements HasColor, HasIcon, HasLabel
{
    case Nueva = 1;       // Nueva compra pendiente
    case Aprobada = 2;    // Aprobada por el área administrativa
    case EnProceso = 3;    // En proceso de adquisición
    case Enviada = 4;     // Enviada por el proveedor
    case Cancelada = 5;   // Cancelada por administración

    public function getLabel(): string
    {
        return match ($this) {
            self::Nueva => 'Nueva Compra',
            self::Aprobada => 'Aprobada',
            self::EnProceso => 'En Proceso',
            self::Enviada => 'Enviada',
            self::Cancelada => 'Cancelada',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Nueva => 'info',
            self::Aprobada => 'primary',
            self::EnProceso => 'warning',
            self::Enviada => 'success',
            self::Cancelada => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Nueva => 'heroicon-o-document',
            self::Aprobada => 'heroicon-m-check',
            self::EnProceso => 'heroicon-m-arrow-path',
            self::Enviada => 'heroicon-o-truck',
            self::Cancelada => 'heroicon-o-no-symbol',
        };
    }

    // Método para obtener solo las opciones específicas
    public static function getLimitedOptions(): array
    {
        return [
            self::Nueva->value => [
                'label' => self::Nueva->getLabel(),
                'color' => self::Nueva->getColor(),
                'icon' => self::Nueva->getIcon(),
            ],
            self::Aprobada->value => [
                'label' => self::Aprobada->getLabel(),
                'color' => self::Aprobada->getColor(),
                'icon' => self::Aprobada->getIcon(),
            ],
            self::Enviada->value => [
                'label' => self::Enviada->getLabel(),
                'color' => self::Enviada->getColor(),
                'icon' => self::Enviada->getIcon(),
            ],
        ];
    }
}
