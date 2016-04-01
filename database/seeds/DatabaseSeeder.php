<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Bsmma\User;
use Bsmma\UserRole;
use Bsmma\Promoter;
use Bsmma\ContestType;

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

        //$this->userRoleTable();
        //$this->userTable();
        $this->promotersTable();
        $this->contestTypesTable();

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

    public function promotersTable()
    {
        DB::table('promoters')->delete();

        $data = [
            [   'promoter'=>'UFC',
                'slug' => 'ufc'
            ],
            [   'promoter'=>'Bellator',
                'slug' => 'bellator'
            ],
            [   'promoter'=>'XFC',
                'slug' => 'xfc'
            ],
            [   'promoter'=>'King of the Cage',
                'slug' => 'kotc'
            ],
            [   'promoter'=>'WSOF',
                'slug' => 'wsof'
            ],
        ];

        foreach ( $data as $row )
        {
            Promoter::create($row);
        }
    }

    public function contestTypesTable()
    {
        DB::table('contest_types')->delete();

        $data = [
            [   'contest_type_name'=>'Old School',
                'contest_type_rules' => '<p>Players finishing in the top 20% receive a prize.</p> <p>The <a href="">top tier</a> adjusted<br />depending on the number of players in each contest.</p>',
                'image_name' => 'classic-controller.png'
            ],
            [   'contest_type_name'=>'50/50',
                'contest_type_rules' => "<p>Players finishing in the top 50% receive a prize.<br />All others receive nothing.</p><p>Good day, sir!<nt /><em>(or ma’am)</em></p>",
                'image_name' => 'pot-o-gold.png'
            ],
            [   'contest_type_name'=>'Monarch',
                'contest_type_rules' => '<p>Only the top ranked player receives a prize.</p><p>In the event of a tie, the top prize will be split evenly among the winners.<em>(Hence the name)</em></p>',
                'image_name' => 'crown.png'
            ],
        ];

        foreach ( $data as $row )
        {
            ContestType::create($row);
        }
    }

}
