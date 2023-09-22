<?php

namespace Modules\Deposits\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Modules\Deposits\Models\DepositEmailType;
use Modules\EmailTemplates\Models\EmailTemplate;
use Modules\Wallet\Support\WalletEmailTemplateContent;

class CreateDepositEmailTemplatesTableSeeder extends Seeder
{
    /**
     * Subscription email templates
     */
    protected $templates = [
        [
            'name' => DepositEmailType::APPROVAL,
            'subject' => 'New deposit request for approval',
            'content' => 'approval'
        ],
        [
            'name' => DepositEmailType::APPROVED,
            'subject' => 'Your deposit request was approved',
            'content' => 'approved'
        ],
        [
            'name' => DepositEmailType::REJECTED,
            'subject' => 'You\'re deposit request was rejected',
            'content' => 'rejected'
        ],
        [
            'name' => DepositEmailType::COMPLETED,
            'subject' => 'Successfully deposited money',
            'content' => 'completed'
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
            $path = '/Modules/Deposits/Resources/views/admin/email-templates/' . $template['content'] . '.blade.php';
            $content = '';
            if ($filesystem->exists(base_path($path))) {
                $content = $filesystem->get(base_path($path));
            }
            $template['content'] = $content;
            EmailTemplate::create($template);
        }
    }
}
