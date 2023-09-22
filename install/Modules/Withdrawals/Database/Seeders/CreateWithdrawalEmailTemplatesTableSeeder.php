<?php

namespace Modules\Withdrawals\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Modules\EmailTemplates\Models\EmailTemplate;
use Modules\Withdrawals\Models\WithdrawalEmailType;

class CreateWithdrawalEmailTemplatesTableSeeder extends Seeder
{
    /**
     * Subscription email templates
     */
    protected $templates = [
        [
            'name' => WithdrawalEmailType::APPROVAL,
            'subject' => 'New withdraw request for approval',
            'content' => 'approval'
        ],
        [
            'name' => WithdrawalEmailType::APPROVED,
            'subject' => 'Your withdraw request was approved',
            'content' => 'approved'
        ],
        [
            'name' => WithdrawalEmailType::REJECTED,
            'subject' => 'Your withdraw request was rejected',
            'content' => 'rejected'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $filesystem = new Filesystem;

        foreach ($this->templates as $key => $template) {
            $path = '/Modules/Withdrawals/Resources/views/admin/email-templates/' . $template['content'] . '.blade.php';
            $content = '';
            if ($filesystem->exists(base_path($path))) {
                $content = $filesystem->get(base_path($path));
            }
            $template['content'] = $content;
            EmailTemplate::create($template);
        }
    }
}
