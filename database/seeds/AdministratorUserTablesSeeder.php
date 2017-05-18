<?php

use Illuminate\Database\Seeder;

use APPJU\Models\Security\User;

/**
 * Add administrator user for main application 
 *
 * @author Raja Sihombing
 * @version 1.0.0
 * @since 1
 */
class AdministratorUserTablesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $user = new User();
        $user->level = 1;
        $user->name = '4dm1nAPpJU';
        $user->password = bcrypt('password123.');
        $user->email = 'apppju@gmail.com';
        $user->first_name = 'Administrator';
        $user->last_name = 'PJU';
        $user->status = 1;
        $user->save();
    }
}
