<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_categorias","view_any_categorias","create_categorias","update_categorias","restore_categorias","restore_any_categorias","replicate_categorias","reorder_categorias","delete_categorias","delete_any_categorias","force_delete_categorias","force_delete_any_categorias","view_color","view_any_color","create_color","update_color","restore_color","restore_any_color","replicate_color","reorder_color","delete_color","delete_any_color","force_delete_color","force_delete_any_color","view_marcas","view_any_marcas","create_marcas","update_marcas","restore_marcas","restore_any_marcas","replicate_marcas","reorder_marcas","delete_marcas","delete_any_marcas","force_delete_marcas","force_delete_any_marcas","view_materiales","view_any_materiales","create_materiales","update_materiales","restore_materiales","restore_any_materiales","replicate_materiales","reorder_materiales","delete_materiales","delete_any_materiales","force_delete_materiales","force_delete_any_materiales","view_precio::producto","view_any_precio::producto","create_precio::producto","update_precio::producto","restore_precio::producto","restore_any_precio::producto","replicate_precio::producto","reorder_precio::producto","delete_precio::producto","delete_any_precio::producto","force_delete_precio::producto","force_delete_any_precio::producto","view_producto","view_any_producto","create_producto","update_producto","restore_producto","restore_any_producto","replicate_producto","reorder_producto","delete_producto","delete_any_producto","force_delete_producto","force_delete_any_producto","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_subcategorias","view_any_subcategorias","create_subcategorias","update_subcategorias","restore_subcategorias","restore_any_subcategorias","replicate_subcategorias","reorder_subcategorias","delete_subcategorias","delete_any_subcategorias","force_delete_subcategorias","force_delete_any_subcategorias","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]},{"name":"Cajero","guard_name":"web","permissions":["view_categorias","view_any_categorias","create_categorias","update_categorias","restore_categorias","restore_any_categorias","replicate_categorias","reorder_categorias","delete_categorias","delete_any_categorias","force_delete_categorias","force_delete_any_categorias","view_color","view_any_color","create_color","update_color","restore_color","restore_any_color","replicate_color","reorder_color","delete_color","delete_any_color","force_delete_color","force_delete_any_color","view_marcas","view_any_marcas","create_marcas","update_marcas","restore_marcas","restore_any_marcas","replicate_marcas","reorder_marcas","delete_marcas","delete_any_marcas","force_delete_marcas","force_delete_any_marcas"]},{"name":"panel_user","guard_name":"web","permissions":[]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
