"use strict";

app.userAffiliateReferralDatatable = {};

app.userAffiliateReferralDatatable.config = {
    levelSelect: '#affiliate-referral-level',
    datatable: '#user-affiliate-referrals-datatable',
    options: {
        src: '/user/affiliates/referrals/datatable',
        resourceName: { singular: app.trans("referral"), plural: app.trans("referrals") },
        columns: [
            {
                title: app.trans('User'),
                key: 'user',
                classes: 'user-affiliate-referral-user-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`
                        <div class="d-flex flex-column">
                            <strong>${ row.user }</strong>
                            <span class="text-muted text-sm">${ row.email }</span>
                        </div>
                    `);
                }
            },
            {
                title: app.trans('For verification'),
                key: 'verification',
                classes: 'admin-affiliate-referral-verification-row'
            },
            {
                title: app.trans('Withdrawable'),
                key: 'withdrawable',
                classes: 'admin-affiliate-referral-withdrawable-row'
            },
            {
                title: app.trans('Completed'),
                key: 'completed',
                classes: 'admin-affiliate-referral-completed-row'
            },
            {
                title: app.trans('Rejected'),
                key: 'rejected',
                classes: 'admin-affiliate-referral-rejected-row'
            },
            {
                title: app.trans('Created'),
                key: 'created',
                classes: 'admin-affiliate-referral-created-row text-right'
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No referred users were found!")
        },
        query: {
            level: 1
        },
        selectable: false,
        showSearchQuery: false,
        limit: 25
    }
};

app.userAffiliateReferralDatatable.levelChange = function() {
    const self = this;
    $(self.config.levelSelect).on('change', function() {
        const level = this.value;
        self.table.setQuery({level}, true);
    });
};

app.userAffiliateReferralDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.userAffiliateReferralDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.userAffiliateReferralDatatable.init = function() {
    this.initDatatable();
    this.levelChange();
};

$(document).ready(app.userAffiliateReferralDatatable.init());
