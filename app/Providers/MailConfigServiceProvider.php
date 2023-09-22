<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setMailDriver();

        switch (setting('mail_driver', config('mail.default'))) {
            case 'smtp':
                $this->setMailStmp();
                break;
            case 'ses':
                $this->setMailSes();
                break;
            case 'mailgun':
                $this->setMailgun();
                break;
            case 'postmark':
                $this->setMailPostmark();
                break;
            case 'sendmail':
                $this->setMailSendmail();
                break;
            case 'log':
                $this->setMailLog();
                break;
        }

        $this->setMailFrom();
    }

    protected function setMailDriver() 
    {
        config([
            'mail.default' => 
                setting(
                    'mail_driver', 
                    env('MAIL_MAILER')
                )
        ]);
    }

    protected function setMailStmp() 
    {
        config([
            'mail.default' => 
                setting(
                    'mail_driver', 
                    env('MAIL_MAILER')
                )
        ]);

        config([
            'mail.mailers.smtp.host' => 
                setting(
                    'mail_smtp_host', 
                    env('MAIL_HOST')
                )
        ]);

        config([
            'mail.mailers.smtp.port' => 
                setting(
                    'mail_smtp_port', 
                    env('MAIL_PORT')
                )
        ]);

        config([
            'mail.mailers.smtp.encryption' => 
                setting(
                    'mail_smtp_encryption', 
                    env('MAIL_ENCRYPTION')
                )
        ]);

        config([
            'mail.mailers.smtp.username' => 
                setting(
                    'mail_smtp_username', 
                    env('MAIL_USERNAME')
                )
        ]);

        config([
            'mail.mailers.smtp.password' => 
                setting(
                    'mail_smtp_password', 
                    env('MAIL_PASSWORD')
                )
        ]);
    }

    protected function setMailSes() 
    {
        config([
            'services.ses.key' => 
                setting(
                    'mail_ses_key', 
                    env('AWS_ACCESS_KEY_ID')
                )
        ]);

        config([
            'services.ses.secret' => 
                setting(
                    'mail_ses_secret', 
                    env('AWS_SECRET_ACCESS_KEY')
                )
        ]);

        config([
            'services.ses.region' => 
                setting(
                    'mail_ses_region', 
                    env('AWS_DEFAULT_REGION')
                )
        ]);
    }

    protected function setMailgun() 
    {
        config([
            'services.mailgun.domain' => 
                setting(
                    'mail_mailgun_domain', 
                    env('MAILGUN_DOMAIN')
                )
        ]);

        config([
            'services.mailgun.secret' => 
                setting(
                    'mail_mailgun_secret', 
                    env('MAILGUN_SECRET')
                )
        ]);

        config([
            'services.mailgun.endpoint' => 
                setting(
                    'mail_mailgun_endpoint', 
                    env('MAILGUN_ENDPOINT')
                )
        ]);
    }

    protected function setMailPostmark() 
    {
        config([
            'services.postmark.token' => 
                setting(
                    'mail_postmark_token', 
                    env('POSTMARK_TOKEN')
                )
        ]);
    }

    protected function setMailSendmail() 
    {
        config([
            'mail.mailers.sendmail.path' => 
                setting(
                    'mail_sendmail_path', 
                    env('MAIL_SENDMAIL_PATH')
                )
        ]);
    }

    protected function setMailLog() 
    {
        config([
            'mail.mailers.log.channel' => 
                setting(
                    'mail_log_channel', 
                    env('MAIL_LOG_CHANNEL')
                )
        ]);
    }

    protected function setMailFrom() 
    {
        config([
            'mail.from.address' => 
                setting(
                    'mail_sender_email', 
                    env('MAIL_FROM_ADDRESS')
                )
        ]);

        config([
            'mail.from.name' => 
                setting(
                    'mail_sender_name', 
                    env('MAIL_FROM_NAME')
                )
        ]);
    }
}
