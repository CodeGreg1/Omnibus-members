<!-- Start Content Header Section -->
@if(!isset($removePageHeader))
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h1 class="page-title mb-0">{{ isset($pageTitle) ? $pageTitle : '' }}</h1>

            @include('partials.breadcrumbs')
            
        </div>
        <div class="page-rightheader">
            @include('partials.module-actions')
        </div>
    </div>
@endif
<!-- End Content Header Section -->