<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'id'                   => 1,
                'name'                 => 'Super Super Stockist',
                'code'                 => 'SSS',
                'description'          => 'Top level of the hierarchy',
                'parent_id'            => null,
                'level'                => 1,
                'can_have_children'    => true,
                'is_active'            => true,
                'visible_in_hierarchy' => true,
                'sort_order'           => 1,
            ],
            [
                'id'                   => 2,
                'name'                 => 'Super Stockist',
                'code'                 => 'SS',
                'description'          => 'Reports to Super Super Stockist',
                'parent_id'            => 1,
                'level'                => 2,
                'can_have_children'    => true,
                'is_active'            => true,
                'visible_in_hierarchy' => true,
                'sort_order'           => 2,
            ],
            [
                'id'                   => 3,
                'name'                 => 'Stockist',
                'code'                 => 'ST',
                'description'          => 'Reports to Super Stockist',
                'parent_id'            => 2,
                'level'                => 3,
                'can_have_children'    => true,
                'is_active'            => true,
                'visible_in_hierarchy' => true,
                'sort_order'           => 3,
            ],
            [
                'id'                   => 4,
                'name'                 => 'Super Distributor',
                'code'                 => 'SD',
                'description'          => 'Reports to Stockist',
                'parent_id'            => 3,
                'level'                => 4,
                'can_have_children'    => true,
                'is_active'            => true,
                'visible_in_hierarchy' => true,
                'sort_order'           => 4,
            ],
            [
                'id'                   => 5,
                'name'                 => 'Distributor',
                'code'                 => 'DT',
                'description'          => 'Bottom level distributor',
                'parent_id'            => 4,
                'level'                => 5,
                'can_have_children'    => false,
                'is_active'            => true,
                'visible_in_hierarchy' => true,
                'sort_order'           => 5,
            ],
        ];

        DB::table('user_categories')->insert($categories);
    }
}