<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // dashboard
        Permission::create(['name' => 'akses-dashboard']);

        // User Management
        Permission::create(['name' => 'melihat-user']);
        Permission::create(['name' => 'membuat-user']);
        Permission::create(['name' => 'mengedit-user']);
        Permission::create(['name' => 'menghapus-user']);

        // User Profile
        Permission::create(['name' => 'akses-profile']);

        // Role Management
        Permission::create(['name' => 'melihat-role']);
        Permission::create(['name' => 'membuat-role']);
        Permission::create(['name' => 'mengedit-role']);
        Permission::create(['name' => 'menghapus-role']);

        // Permission Management
        Permission::create(['name' => 'permission-access']);
        Permission::create(['name' => 'permission-store']);
        Permission::create(['name' => 'permission-update']);
        Permission::create(['name' => 'permission-destroy']);

        // Data Department
        Permission::create(['name' => 'melihat-department']);
        Permission::create(['name' => 'membuat-department']);
        Permission::create(['name' => 'mengedit-department']);
        Permission::create(['name' => 'menghapus-department']);

        // Data Karyawan
        Permission::create(['name' => 'melihat-karyawan']);
        Permission::create(['name' => 'membuat-karyawan']);
        Permission::create(['name' => 'mengedit-karyawan']);
        Permission::create(['name' => 'menghapus-karyawan']);

        // Data Jabatan
        Permission::create(['name' => 'melihat-jabatan']);
        Permission::create(['name' => 'membuat-jabatan']);
        Permission::create(['name' => 'mengedit-jabatan']);
        Permission::create(['name' => 'menghapus-jabatan']);

        // Data Cuti
        Permission::create(['name' => 'melihat-cuti']);
        Permission::create(['name' => 'membuat-cuti']);
        Permission::create(['name' => 'mengedit-cuti']);
        Permission::create(['name' => 'menghapus-cuti']);
        Permission::create(['name' => 'menyetujui-cuti']);
        Permission::create(['name' => 'menolak-cuti']);



        // Data Izin
        // Permission::create(['name' => 'melihat-izin']);
        // Permission::create(['name' => 'membuat-izin']);
        // Permission::create(['name' => 'mengedit-izin']);
        // Permission::create(['name' => 'menolak-izin']);
        // Permission::create(['name' => 'menyetujui-izin']);
        // Permission::create(['name' => 'menghapus-izin']);

        // // Data Cuti
        // Permission::create(['name' => 'melihat-cuti']);
        // Permission::create(['name' => 'membuat-cuti']);
        // Permission::create(['name' => 'mengedit-cuti']);
        // Permission::create(['name' => 'menolak-cuti']);
        // Permission::create(['name' => 'menyetujui-cuti']);
        // Permission::create(['name' => 'menghapus-cuti']);

        // // Data Lembur
        // Permission::create(['name' => 'melihat-lembur']);
        // Permission::create(['name' => 'membuat-lembur']);
        // Permission::create(['name' => 'mengedit-lembur']);
        // Permission::create(['name' => 'menolak-lembur']);
        // Permission::create(['name' => 'menyetujui-lembur']);
        // Permission::create(['name' => 'menghapus-lembur']);

        // Report
        Permission::create(['name' => 'melihat-laporan']);
    }
}
