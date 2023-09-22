"use strict";

// define app.adminDashboardWidgetsCreate object
app.adminDashboardWidgetsCreate = {};

// handle app.adminDashboardWidgetsCreate object configuration
app.adminDashboardWidgetsCreate.config = {
    form: '#admin-dashboard-widgets-create-form',
    route: '/admin/dashboard-widgets/create',
    sumAvgTypesTrigger: [
        'integer',
        'decimal',
        'float'
    ],
    groupPeriodsTrigger: [
        'timestamp'
    ],
    excludedColumns: [
        'id',
        'password',
        'auth_id',
        'authy_status',
        'authy_country_code',
        'authy_phone'
    ],
    charts: [
        'line',
        'bar',
        'pie'
    ],
    defaultFilterData: {
        'days|today':app.trans('Today'),
        'days|7':app.trans('Show last 7 days'),
        'days|14':app.trans('Show last 14 days'),
        'days|30':app.trans('Show last 30 days'),
        'days|60':app.trans('Show last 60 days'),
        'days|90':app.trans('Show last 90 days'),
        'period|last_week':app.trans('Show last week'),
        'period|this_week':app.trans('Show this week'),
        'period|last_month':app.trans('Show last month'),
        'period|this_month':app.trans('Show this month'),
        'period|last_year':app.trans('Show last year'),
        'period|this_year':app.trans('Show this year'),
    },
    yearFilterData: {
        'period|last_week':app.trans('Show last week'),
        'period|this_week':app.trans('Show this week'),
        'period|last_month':app.trans('Show last month'),
        'period|this_month':app.trans('Show this month'),
        'period|last_year':app.trans('Show last year'),
        'period|this_year':app.trans('Show this year'),
        'years|3':app.trans('Last 3 years'),
        'years|5':app.trans('Last 5 years'),
        'years|10':app.trans('Last 10 years'),
    },
    counterFilterData: {
        'string|null':app.trans('Null'),
        'alltime':app.trans('All time'),
        'days|today':app.trans('Today'),
        'minutes|3':app.trans('Show last 3 mins'),
        'minutes|5':app.trans('Show last 5 mins'),
        'minutes|10':app.trans('Show last 10 mins'),
        'minutes|20':app.trans('Show last 20 mins'),
        'minutes|30':app.trans('Show last 30 mins'),
        'days|7':app.trans('Show last 7 days'),
        'days|14':app.trans('Show last 14 days'),
        'days|30':app.trans('Show last 30 days'),
        'days|60':app.trans('Show last 60 days'),
        'days|90':app.trans('Show last 90 days'),
        'period|last_week':app.trans('Show last week'),
        'period|this_week':app.trans('Show this week'),
        'period|last_month':app.trans('Show last month'),
        'period|this_month':app.trans('Show this month'),
        'period|last_year':app.trans('Show last year'),
        'period|this_year':app.trans('Show this year')
    },
};

// handle filter data select options
app.adminDashboardWidgetsCreate.loadOptionsFilterDataSelect = function(filterData) {

    var filterDataElement = $('[name="filter_data"]');

    var options = '';
    $.each(filterData, function(key, value) {

        //for edit page set selected if not undefined and same value
        if(filterDataElement.attr('data-edit-value') !== undefined && filterDataElement.attr('data-edit-value') == key) {
            options += '<option value="'+key+'" selected>'+value+'</option>';
        } else {
            options += '<option value="'+key+'">'+value+'</option>';
        }

        
    });

    filterDataElement.html(options);
};

// handle generating group by select options
app.adminDashboardWidgetsCreate.groupByColumnSelect = function() {
    $(document).delegate('[name="group_by_column"]', 'change', function(e) {
        var val = $(this).val();

        var arr = val.split('|');

        if(arr.shift() == 'YEAR') {
            app.adminDashboardWidgetsCreate.loadOptionsFilterDataSelect(app.adminDashboardWidgetsCreate.config.yearFilterData);
        } else {
            app.adminDashboardWidgetsCreate.loadOptionsFilterDataSelect(app.adminDashboardWidgetsCreate.config.defaultFilterData);
        }
    });
};

// handle generating filter field select options
app.adminDashboardWidgetsCreate.filterFieldSelect = function() {
    $(document).delegate('[name="filter_field"]', 'change', function(e) {
        var val = $(this).val();

        $('[for="filter_data"] .field-name').html(val);
    });
};

// handle hide and show implementation base on selected class
app.adminDashboardWidgetsCreate.hideShowWrapperSelect = function(wrapperClass, showHide) 
{   
    if(showHide == 'show') {
        $(wrapperClass).show();

        if($(wrapperClass).find('select').length) {
            $(wrapperClass).find('select').prop('disabled', false);
        }

        if($(wrapperClass).find('input').length) {
            $(wrapperClass).find('input').prop('disabled', false);
        }
        
    } else {
        $(wrapperClass).hide();

        if($(wrapperClass).find('select').length) {
            $(wrapperClass).find('select').prop('disabled', true);
        }

        if($(wrapperClass).find('input').length) {
            $(wrapperClass).find('input').prop('disabled', true);
        }
    }
};

// handle selecting widget type to create
app.adminDashboardWidgetsCreate.widget = function() 
{
    $('[name="type"]').on('click', function() {
        var type = $(this).val();

        if(app.adminDashboardWidgetsCreate.config.charts.includes(type)) {
            app.adminDashboardWidgetsCreate.loadOptionsFilterDataSelect(app.adminDashboardWidgetsCreate.config.defaultFilterData);
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.group-by-column-wrapper', 'show');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.show-blank-data-wrapper', 'show');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.aggregate-function-wrapper', 'show');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.show-filter-data-wrapper', 'show');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.filter-field-wrapper', 'hide');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.total-records-wrapper', 'hide');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.load-relationships-wrapper', 'hide');
            app.adminDashboardWidgetsFieldToShow.hideShowTableWrapper('.field-to-show-table-wrapper', 'hide');
            $('[name="bg_background"]').prop('disabled', true);
            $('[name="icon"]').prop('disabled', true);
            $('[name="view_more"]').prop('disabled', true);
        }

        if(type == 'counter') {
            app.adminDashboardWidgetsCreate.loadOptionsFilterDataSelect(app.adminDashboardWidgetsCreate.config.counterFilterData);
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.group-by-column-wrapper', 'hide');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.show-blank-data-wrapper', 'hide');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.filter-field-wrapper', 'show');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.aggregate-function-wrapper', 'show');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.show-filter-data-wrapper', 'show');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.total-records-wrapper', 'hide');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.load-relationships-wrapper', 'hide');
            app.adminDashboardWidgetsFieldToShow.hideShowTableWrapper('.field-to-show-table-wrapper', 'hide');
            $('[name="bg_background"]').prop('disabled', false);
            $('[name="icon"]').prop('disabled', false);
            $('[name="view_more"]').prop('disabled', true);
        }

        if(type == 'latest_records') {
            app.adminDashboardWidgetsCreate.loadOptionsFilterDataSelect(app.adminDashboardWidgetsCreate.config.defaultFilterData);
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.filter-field-wrapper', 'hide');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.group-by-column-wrapper', 'hide');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.show-blank-data-wrapper', 'hide');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.aggregate-function-wrapper', 'hide');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.total-records-wrapper', 'show');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.load-relationships-wrapper', 'show');
            app.adminDashboardWidgetsFieldToShow.hideShowTableWrapper('.field-to-show-table-wrapper', 'show');
            app.adminDashboardWidgetsCreate.hideShowWrapperSelect('.show-filter-data-wrapper', 'hide');
            $('[name="bg_background"]').prop('disabled', true);
            $('[name="icon"]').prop('disabled', true);
            $('[name="view_more"]').prop('disabled', false);
        }
    });
};

// handle ajax request for geting table columns
app.adminDashboardWidgetsCreate.model = function() 
{
    $(document).delegate('[name="data_source_model"]', 'change', function(e) {
        
        var table = $(this).val();

        $.ajax({
            type: 'GET',
            url: '/admin/dashboard-widgets/table-columns',
            data: {table:table},
            contentType: false,
            cache: false,
            processData: true,
            beforeSend: function() {
                $('[name="aggregate_function"]').html('<option value="">Loading...</option>');
                $('[name="aggregate_function"]').prop('disabled', true);

                $('[name="group_by_column"]').html('<option value="">Loading...</option>');
                $('[name="group_by_column"]').prop('disabled', true);

                $('[name="filter_field"]').html('<option value="">Loading...</option>');
                $('[name="filter_field"]').prop('disabled', true);
            },
            success: function (response, textStatus, xhr) {
                
                var aggregateFunctionOptions = '<option value="count">count()</option>';
                var groupByColumnOptions = '';
                var filterFieldOptions = '';
                var fieldToShow = '';

                $.each(response, function(column, option) {
                    // exclude column with _id string
                    if(!app.adminDashboardWidgetsCreate.config.excludedColumns.includes(column) && !column.includes('_id') && app.adminDashboardWidgetsCreate.config.sumAvgTypesTrigger.includes(option['type'])) {
                        aggregateFunctionOptions += '<option value="sum|'+column+'">sum('+column+')</option>';
                        aggregateFunctionOptions += '<option value="avg|'+column+'">avg('+column+')</option>';
                    }

                    // exclude column with _id string
                    if(!column.includes('_id') && app.adminDashboardWidgetsCreate.config.groupPeriodsTrigger.includes(option['type'])) {
                        groupByColumnOptions += '<option value="DAY|'+column+'">DAY('+column+')</option>';
                        groupByColumnOptions += '<option value="WEEK|'+column+'">WEEK('+column+')</option>';
                        groupByColumnOptions += '<option value="MONTH|'+column+'">MONTH('+column+')</option>';
                        groupByColumnOptions += '<option value="YEAR|'+column+'">YEAR('+column+')</option>';
                    }

                    if(!app.adminDashboardWidgetsCreate.config.excludedColumns.includes(column)) {
                        filterFieldOptions += '<option value="'+column+'" '+(column=="created_at"?"selected":"")+'>'+column+'</option>';
                    }

                    if(!app.adminDashboardWidgetsCreate.config.excludedColumns.includes(column)) {
                        fieldToShow += app.adminDashboardWidgetsFieldToShow.template(column, option);
                    }
                    
                });

                $.each(response, function(column, option) {
                    // exclude column with _id string
                    if(!app.adminDashboardWidgetsCreate.config.excludedColumns.includes(column) && !column.includes('_id') && !app.adminDashboardWidgetsCreate.config.groupPeriodsTrigger.includes(option['type'])) {
                        groupByColumnOptions += '<option value="'+column+'">'+column+'</option>';
                    }
                });

                $('[name="aggregate_function"]').html(aggregateFunctionOptions);
                $('[name="group_by_column"]').html(groupByColumnOptions);
                $('[name="filter_field"]').html(filterFieldOptions);
                $('#fields-table-to-show tbody').html(fieldToShow);
                
                $('[name="aggregate_function"]').prop('disabled', false);
                $('[name="group_by_column"]').prop('disabled', false);
                
                // remove disabled if type is counter
                if($('[name="type"]:checked').val() == 'counter') {
                    $('[name="filter_field"]').prop('disabled', false);
                }

                // load select2 for field to show select
                app.adminDashboardWidgetsFieldToShow.initSelect2();
            },
            error: function() {
                $('[name="aggregate_function"]').prop('disabled', false);
                $('[name="group_by_column"]').prop('disabled', false);
                $('[name="filter_field"]').prop('disabled', false);
            }
        });
    });
};

// handle ajax request for saving new widget
app.adminDashboardWidgetsCreate.ajax = function() {

    $(app.adminDashboardWidgetsCreate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminDashboardWidgetsCreate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminDashboardWidgetsCreate.config.route,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.redirect(response.data.redirectTo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminDashboardWidgetsCreate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

// handle initializing available functions for app.adminDashboardWidgetsCreate object
app.adminDashboardWidgetsCreate.init = function() {
    app.adminDashboardWidgetsCreate.loadOptionsFilterDataSelect(app.adminDashboardWidgetsCreate.config.defaultFilterData);
    app.adminDashboardWidgetsCreate.groupByColumnSelect();
    app.adminDashboardWidgetsCreate.filterFieldSelect();
    app.adminDashboardWidgetsCreate.widget();
    app.adminDashboardWidgetsCreate.model();
    app.adminDashboardWidgetsCreate.ajax();
};

// initialize app.adminDashboardWidgetsCreate object until the document is loaded
$(document).ready(app.adminDashboardWidgetsCreate.init());

// handle initializing select2 with icon
$(document).ready(function() {
    if(typeof $.fn.select2 !== 'undefined') {
        $('.icon-widget-select2').select2({
            escapeMarkup: function (text) { 
                return '<span class="fa-2x '+text+'"></span> ' + text.replace('fas', '');
            }
        });
    }
});