<?php

namespace Modules\Wallet\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Models\WalletEmailType;
use Modules\EmailTemplates\Models\EmailTemplate;

class CreateWalletEmailTemplatesTableSeeder extends Seeder
{
    /**
     * Subscription email templates
     */
    protected $templates = [
        [
            'name' => WalletEmailType::COMPLETED,
            'subject' => 'Wallet conversion completed',
            'content' => 'completed'
        ],
        [
            'name' => WalletEmailType::SEND_MONEY,
            'subject' => 'You\'ve successfully sent money',
            'content' => 'sent-money'
        ],
        [
            'name' => WalletEmailType::RECEIVED_MONEY,
            'subject' => 'You received money',
            'content' => 'received-money'
        ],
        [
            'name' => WalletEmailType::OTP,
            'subject' => 'Your One-Time Password',
            'content' => 'otp'
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
            $path = '/Modules/Wallet/Resources/views/admin/email-templates/' . $template['content'] . '.blade.php';
            $content = '';
            if ($filesystem->exists(base_path($path))) {
                $content = $filesystem->get(base_path($path));
            }
            $template['content'] = $content;
            EmailTemplate::create($template);
        }
    }
}
