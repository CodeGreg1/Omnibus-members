<div class="card card-form" id="company">
    <div class="card-header">
        <h4><i class="fas fa-briefcase"></i> @lang('Company')</h4>
    </div>

    <form id="profile-company-form" method="post">
        <input value="{{ $company ? $company->id : '' }}" 
            name="company_id" 
            class="form-control" 
            type="hidden">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="company_name">@lang('Company name') </label>
                        <input value="{{ $company ? $company->name : '' }}" 
                            name="company_name" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="company_number">@lang('Company number')</label>
                        <input value="{{ $company ? $company->number : '' }}" 
                            name="company_number" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="tax_number">@lang('Tax number')</label>
                        <input value="{{ $company ? $company->tax_number : '' }}"  
                            name="tax_number" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group mb-0">
                        <label for="phone">@lang('Phone')</label>
                        <input value="{{ $company ? $company->phone : '' }}" 
                            name="phone" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>
            </div>
        </div>
         
        <div class="card-footer bg-whitesmoke">
            <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
            <!-- <button class="btn btn-secondary btn-lg float-right"  type="button"><i class="fa fa-remove"></i> Cancel</button> -->
        </div>
    </form>
          
</div>