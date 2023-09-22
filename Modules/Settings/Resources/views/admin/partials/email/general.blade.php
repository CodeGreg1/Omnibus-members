<div class="card">
                            
    <div class="card-header">
        <h4>@lang('Email Settings')</h4>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="mail_driver">@lang('Mail driver') <span class="text-muted">(@lang('Required'))</span></label>
                    <select name="mail_driver" class="form-control select2">
                        @foreach($drivers as $driverKey => $driverDesc)
                            <option 
                                value="{{ $driverKey }}"
                                {{ 
                                    $driverKey == setting('mail_driver')
                                    ? 'selected' 
                                    : '' 
                                }}
                            >{{ $driverDesc }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row smtp-wrapper @if (setting('mail_driver', config('mail.default')) != 'smtp') display-none @endif">
            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="mail_smtp_host">@lang('Host')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="mail_smtp_host" class="form-control" value="{{ setting('mail_smtp_host') }}">
                </div>
            </div>

            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="mail_smtp_port">@lang('Port')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="number" name="mail_smtp_port" class="form-control" value="{{ setting('mail_smtp_port') }}">
                </div>
            </div>
        </div>

        <div class="row smtp-wrapper @if (setting('mail_driver', config('mail.default')) != 'smtp') display-none @endif">
            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="mail_smtp_username">@lang('Username')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="mail_smtp_username" class="form-control" value="{{ protected_data(setting('mail_smtp_username'), 'mail username') }}">
                </div>
            </div>

            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="mail_smtp_password">@lang('Password')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="mail_smtp_password" class="form-control" value="{{ protected_data(setting('mail_smtp_password'), 'mail password') }}">
                </div>
            </div>
        </div>

        <div class="row ses-wrapper @if (setting('mail_driver', config('mail.default')) != 'ses') display-none @endif">
            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="mail_ses_key">@lang('Key')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="mail_ses_key" class="form-control" value="{{ protected_data(setting('mail_ses_key'), 'sey key') }}">
                </div>
            </div>

            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="mail_ses_secret">@lang('Secret')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="password" name="mail_ses_secret" class="form-control" value="{{ protected_data(setting('mail_ses_secret'), 'ses secret') }}">
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="mail_ses_region">@lang('Region')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="password" name="mail_ses_region" class="form-control" value="{{ setting('mail_ses_region', 'us-east-1') }}">
                </div>
            </div>
        </div>

        <div class="row mailgun-wrapper @if (setting('mail_driver', config('mail.default')) != 'mailgun') display-none @endif">
            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="mail_mailgun_domain">@lang('Domain')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="mail_mailgun_domain" class="form-control" value="{{ setting('mail_mailgun_domain') }}">
                </div>
            </div>

            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="mail_mailgun_secret">@lang('Secret')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="password" name="mail_mailgun_secret" class="form-control" value="{{ protected_data(setting('mail_mailgun_secret'), 'mailgun secret') }}">
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="mail_mailgun_endpoint">@lang('Endpoint')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="mail_mailgun_endpoint" class="form-control" value="{{ setting('mail_mailgun_endpoint', 'api.mailgun.net') }}">
                </div>
            </div>
        </div>

        <div class="row postmark-wrapper @if (setting('mail_driver', config('mail.default')) != 'postmark') display-none @endif">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="mail_postmark_token">@lang('Token')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="password" name="mail_postmark_token" class="form-control" value="{{ protected_data(setting('mail_postmark_token'), 'postmark token') }}">
                </div>
            </div>
        </div>

        <div class="row log-wrapper @if (setting('mail_driver', config('mail.default')) != 'log') display-none @endif">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="mail_log_channel">@lang('Log channel')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="mail_log_channel" class="form-control" value="{{ setting('mail_log_channel') }}">
                </div>
            </div>
        </div>

        <div class="row sendmail-wrapper @if (setting('mail_driver', config('mail.default')) != 'sendmail') display-none @endif">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="mail_sendmail_path">@lang('Sendmail path')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="mail_sendmail_path" class="form-control" value="{{ setting('mail_sendmail_path', '/usr/sbin/sendmail -bs -i') }}">
                    <small class='form-text text-muted form-help'>@lang('Default: /usr/sbin/sendmail -bs -i')</small>
                </div>
            </div>
        </div>

        <div class="row smtp-wrapper @if (setting('mail_driver', config('mail.default')) != 'smtp') display-none @endif">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="mail_smtp_encryption">@lang('Encryption') <span class="text-muted">(@lang('Required'))</span></label>
                    <select name="mail_smtp_encryption" class="form-control select2">
                        @foreach($encryptions as $encryptionKey => $encryptionDesc)
                            <option 
                                value="{{ $encryptionKey }}"
                                {{ 
                                    $encryptionKey == setting('mail_smtp_encryption')
                                    ? 'selected' 
                                    : '' 
                                }}
                            >{{ $encryptionDesc }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 col-md-6">
                <div class="form-group mb-0">
                    <label for="mail_sender_name">@lang('Sender name')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="mail_sender_name" class="form-control" value="{{ setting('mail_sender_name') }}">
                </div>
            </div>

            <div class="col-6 col-md-6">
                <div class="form-group mb-0">
                    <label for="mail_sender_email">@lang('Sender email')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="mail_sender_email" class="form-control" value="{{ setting('mail_sender_email') }}">
                </div>
            </div>
        </div>


        
    </div>

</div>