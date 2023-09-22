<?php

namespace Modules\Pages\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Modules\EmailTemplates\Models\EmailTemplate;

class CreatePageContactUsMessageTableSeeder extends Seeder
{
    /**
     * Subscription email templates
     */
    protected $templates = [
        [
            'name' => 'Contact us message',
            'subject' => 'You received a new message from your contact us form',
            'content' => 'contact-us'
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
            $path = '/Modules/Pages/Resources/views/admin/email-templates/' . $template['content'] . '.blade.php';
            $content = '';
            if ($filesystem->exists(base_path($path))) {
                $content = $filesystem->get(base_path($path));
            }
            $template['content'] = $content;
            EmailTemplate::create($template);
        }
    }
}
