<?php

namespace Modules\EmailTemplates\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailSender extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;

    protected $data = [];

    /**
     * @var array $files
     * 
     * Sample: [
     *  'public_file_path1',
     *  'public_file_path2'
     * ]
     */
    public $files = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->subject = $data['subject'];
        $this->files = $data['attachments'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->view('emailtemplates::emails.send')
            ->with($this->data);
            
        if ($this->files) {
            foreach ($this->files as $file) {
                $mail->attach($file);
            }
        }

        return $mail;
    }
}
