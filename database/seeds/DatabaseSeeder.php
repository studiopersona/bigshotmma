<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Bsmma\User;
use Bsmma\UserRole;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->userRoleTable();
        $this->userTable();

        Model::reguard();
    }

    public function userRoleTable()
    {
    	 DB::table('user_roles')->delete();

        $user_roles = [
        	['user_role' => 'player'],
        	['user_role' => 'administrator'],
        ];

        foreach ($user_roles as $role)
        {
        	UserRole::create($role);
        }
    }

    public function userTable()
    {
    	DB::table('users')->delete();

        $users = array(
                [	'firstname' => 'Ryan',
                	'lastname' => 'Chenkie',
                	'email' => 'ryanchenkie@gmail.com',
                	'password' => Hash::make('secret'),
                	'address1' => '123 Main St',
                	'address2' => 'Suite 2',
                	'city' => 'Fort Lauderdale',
                	'state' => 'FL',
                	'zipcode' => '33304',
                	'phone' => '9548544742',
                	'balance' => 100,
                	'status' => 1,
                	'user_role_id' => '000001'
                ],
                [	'firstname' => 'Chris',
                	'lastname' => 'Sevilleja',
                	'email' => 'chris@scotch.io',
                	'password' => Hash::make('secret'),
                	'address1' => '123 Main St',
                	'address2' => 'Suite 2',
                	'city' => 'Fort Lauderdale',
                	'state' => 'FL',
                	'zipcode' => '33304',
                	'phone' => '9548544742',
                	'balance' => 100,
                	'status' => 1,
                	'user_role_id' => '000001'
                ],
                [	'firstname' => 'Holly',
                	'lastname' => 'Lloyd',
                	'email' => 'holly@scotch.io',
                	'password' => Hash::make('secret'),
                	'address1' => '123 Main St',
                	'address2' => 'Suite 2',
                	'city' => 'Fort Lauderdale',
                	'state' => 'FL',
                	'zipcode' => '33304',
                	'phone' => '9548544742',
                	'balance' => 100,
                	'status' => 1,
                	'user_role_id' => '000001'
                ],
                [	'firstname' => 'Adnan',
                	'lastname' => 'Kukic',
                	'email' => 'adnan@scotch.io',
                	'password' => Hash::make('secret'),
                	'address1' => '123 Main St',
                	'address2' => 'Suite 2',
                	'city' => 'Fort Lauderdale',
                	'state' => 'FL',
                	'zipcode' => '33304',
                	'phone' => '9548544742',
                	'balance' => 100,
                	'status' => 1,
                	'user_role_id' => '000001'
                ],
        );

        // Loop through each user above and create the record for them in the database
        foreach ($users as $user)
        {
            User::create($user);
        }
    }

}
