<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommentSetting;

class CommentSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'customer' => [
                'enabled'      => true,
                'max_length'   => 5000,
                'allow_delete' => true,
                'allow_html'   => true,
                'label'        => 'Comments',
            ],
            'product' => [
                'enabled'      => true,
                'max_length'   => 2000,
                'allow_delete' => true,
                'allow_html'   => false,   // product notes — plain text only
                'label'        => 'Notes',
            ],
            'invoice' => [
                'enabled'      => true,
                'max_length'   => 3000,
                'allow_delete' => false,   // invoice comments — audit trail, no delete
                'allow_html'   => true,
                'label'        => 'Remarks',
            ],
        ];

        foreach ($modules as $module => $config) {
            CommentSetting::updateOrCreate(
                ['module' => $module],
                ['configuration' => $config]
            );
        }

        $this->command->info('Comment settings seeded for: ' . implode(', ', array_keys($modules)));
    }
}