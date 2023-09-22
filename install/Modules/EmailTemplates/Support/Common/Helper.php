<?php 

use Modules\EmailTemplates\Repositories\EmailTemplateRepository;

const EMAIL_ADMINS = 'admins';
const EMAIL_USERS = 'users';

if (!function_exists('email_template_merge_shortcode')) {
    /**
     * Email template code generator
     * 
     * @return string|null
     */
    function email_template_merge_shortcode(array $arrays)
    {
        $buf = [];
        foreach($arrays as $arr){
            foreach($arr as $v){
                $buf[$v] = true;
            }
        }
        return array_keys($buf);
    }
}

if (!function_exists('email_template_code')) {
    /**
     * Email template code generator
     * 
     * @return string|null
     */
    function email_template_code()
    {
        $emailTemplate = new EmailTemplateRepository;

        do {
            $code = random_int(100000, 999999);
        } while ($emailTemplate->where("code", "=", $code)->first());
  
        return $code;
    }
}