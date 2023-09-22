<div class="wizard-steps">
    <div class="wizard-step{{ isset($install) && $install ? ' wizard-step-active' : '' }}">
        <div class="wizard-step-label">
            Checking Requirements
        </div>
    </div>
    <div class="wizard-step{{ isset($requirements) && $requirements ? ' wizard-step-active' : '' }}">
        <div class="wizard-step-label">
            System Requirements
        </div>
    </div>
    <div class="wizard-step{{ isset($permissions) && $permissions ? ' wizard-step-active' : '' }}">
        <div class="wizard-step-label">
            Directory Permissions
        </div>
    </div>
    <div class="wizard-step{{ isset($database) && $database ? ' wizard-step-active' : '' }}">
        <div class="wizard-step-label">
            Configure Database
        </div>
    </div>
    <div class="wizard-step{{ isset($installation) && $installation ? ' wizard-step-active' : '' }}">
        <div class="wizard-step-label">
            Start The Installation
        </div>
    </div>
    <div class="wizard-step{{ isset($completed) && $completed ? ' wizard-step-active wizard-step-success' : '' }}{{ isset($error) && $error ? ' wizard-step-warning' : '' }}">
        <div class="wizard-step-label">
            Installation Success
        </div>
    </div>
</div>