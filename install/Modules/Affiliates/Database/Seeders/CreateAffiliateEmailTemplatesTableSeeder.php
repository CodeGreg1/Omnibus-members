<?php

namespace Modules\Affiliates\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Modules\EmailTemplates\Models\EmailTemplate;
use Modules\Affiliates\Models\AffiliateEmailType;

class CreateAffiliateEmailTemplatesTableSeeder extends Seeder
{
    /**
     * Subscription email templates
     */
    protected $templates = [
        [
            'name' => AffiliateEmailType::AFFILIATE_MEMBERSHIP,
            'subject' => 'New affiliate membership request',
            'content' => 'affiliate-membership'
        ],
        [
            'name' => AffiliateEmailType::AFFILIATE_MEMBERSHIP_APPROVED,
            'subject' => 'Affiliate membership request approved',
            'content' => 'affiliate-membership-approved'
        ],
        [
            'name' => AffiliateEmailType::AFFILIATE_MEMBERSHIP_REJECTED,
            'subject' => 'Affiliate membership request rejected',
            'content' => 'affiliate-membership-rejected'
        ],
        [
            'name' => AffiliateEmailType::INCOMING_COMMISSION,
            'subject' => 'New commission for verification',
            'content' => 'incoming-commission'
        ],
        [
            'name' => AffiliateEmailType::COMMISSION_APPROVED,
            'subject' => 'Commission approved',
            'content' => 'commission-approved'
        ],
        [
            'name' => AffiliateEmailType::COMMISSION_REJECTED,
            'subject' => 'Commission rejected',
            'content' => 'commission-rejected'
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
            $path = '/Modules/Affiliates/Resources/views/admin/email-templates/' . $template['content'] . '.blade.php';
            $content = '';
            if ($filesystem->exists(base_path($path))) {
                $content = $filesystem->get(base_path($path));
            }
            $template['content'] = $content;
            EmailTemplate::create($template);
        }
    }
}
