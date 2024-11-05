<?php

namespace App\Providers\Filament;
use App\Filament\Pages\Settings;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
#use App\Filament\AvatarProviders\BoringAvatarsProvider;
use App\Http\Middleware\CheckUserRole;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\MenuItem;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->font('Inter')
            ->emailVerification()
            ->breadcrumbs(true)
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Red,
            ])
            ->collapsibleNavigationGroups(TRUE)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->databaseNotifications(true)
            #->profile()
            #  ->topNavigation()
            #->sidebarWidth('15rem')
            #  ->brandLogo('')
            # ->defaultAvatarProvider(BoringAvatarsProvider::class)
            #->collapsedSidebarWidth('9rem')
            ->sidebarFullyCollapsibleOnDesktop()
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                CheckUserRole::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->plugins([
                    FilamentEditProfilePlugin::make()
                        ->slug('mi-perfil')
                        ->setTitle('Mi perfil')
                        ->setSort(3)
                        ->shouldRegisterNavigation(false)
                        ->shouldShowDeleteAccountForm(false)
                        ->shouldShowBrowserSessionsForm()
                        ->shouldShowAvatarForm(
                            true,
                            'avatars',
                            'mimes:jpeg,png,jpg1max:1024'
                        ),
                    FilamentShieldPlugin::make()

                        ->gridColumns([
                            'default' => 1,
                            'sm' => 1,
                            'lg' => 2
                        ])
                        ->sectionColumnSpan(2)
                        ->checkboxListColumns([
                            'default' => 1,
                            'sm' => 1,
                            'lg' => 2,
                        ])
                        ->resourceCheckboxListColumns([
                            'default' => 1,
                            'sm' => 1
                        ]),
                     
                ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => auth()->user()->name)
                    ->url(fn() => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle'),

            ]);
        ;
    }
}
