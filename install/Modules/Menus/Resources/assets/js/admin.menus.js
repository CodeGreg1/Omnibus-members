"use strict";

// define app.adminMenus object
app.adminMenus = {}

// handle generating data for menu
app.adminMenus.generateData = function(element, pid = 0) {
    return [...element.children].reduce((r, li) => {

        const p = li.querySelector('.dd3-content .item-content');
        const ol = li.querySelector('ol.dd-list');
        const obj = {pid};

        $.each(li.attributes, function() {
            if(this.name.includes("data-")) {
                var key = this.name.replace('data-', '');
                obj[key] = this.value;
            }
        });

        if (ol) obj.children = app.adminMenus.generateData(ol, pid + 1);

        r.push(obj);
        return r;
    }, [])
};

// handle group error
app.adminMenus.groupError = function(response, key) {
    if(response.errors !== undefined && response.errors[key]) {
        if($('#language-tab').parents('.col-md-12').find('.group-error').length) {
            $('#language-tab').parents('.col-md-12').find('.group-error').remove();
        }

        $.each(response.errors[key], function(k,v) {
            $('#language-tab').parents('.col-md-12').prepend('<p class="group-error">'+v+'</p>');
        });

        setTimeout(function() {
            $('.group-error').hide();
        }, 3000);
    }
};

// handle form reset
app.adminMenus.formReset = function(selector) {
    app.formReset(selector);
    app.adminMenus.resetIconSelect2();
};

// handle initializing select2 with icon
app.adminMenus.resetIconSelect2 = function() {
    if($('.icon-select2').length) {
        $('.icon-select2').val('').trigger( 'change' );
    }
};

app.adminMenus.resetLinkSelect2 = function() {
    if($('#menu-links').length) {
        $('#menu-links').select2({
            ajax: {
                url: '/admin/menus/menu-lists',
                data: function (params) {
                    var query = {
                        page: params.page || 1,
                        limit: 100,
                        search: params.term,
                        type: $('[name="link_type"]').val()
                    }

                    $('#select2-menu-links-results').append($('<li class="select2-results__option select2-results__message select2-loader"><i class="fas fa-spinner fa-spin"></i></li>'));

                    return query;
                },
                processResults: function (data) {
                    $('#select2-menu-links-results').find('.select2-loader').remove();
                    return data;
                },
                cache: true
            },
            placeholder: 'Search for a link',
        });
    }
};

// initialize select2 after the document is loaded
$(document).ready(function() {
    if(typeof $.fn.select2 !== 'undefined') {
        $('.icon-select2').select2({
            escapeMarkup: function (text) {
                return '<span class="fa-2x '+text+'"></span> ' + text.replace('fas', '');
            }
        });

        app.adminMenus.resetLinkSelect2();

        $('[name="link_type"]').on('change', function() {
            $('#menu-links').val(null).trigger('change');
            if (this.value === 'Custom') {
                $('.menu-link-route-type').addClass('d-none');
                $('.menu-link-url-type').removeClass('d-none');
                $('[name="link"]').removeAttr('required');
                $('[name="url"]').prop('required', true);
            } else {
                app.adminMenus.resetLinkSelect2();
                $('.menu-link-route-type').removeClass('d-none');
                $('.menu-link-url-type').addClass('d-none');
                $('[name="link"]').prop('required', true);
                $('[name="url"]').removeAttr('required');

                var $select2 = $('#menu-links').next();
                if (this.value === 'Default') {
                    $select2.find('.select2-selection__placeholder').html(app.trans('Search for links'));
                } else if (this.value === 'Blog') {
                    $select2.find('.select2-selection__placeholder').html(app.trans('Search for blogs'));
                } else {
                    $select2.find('.select2-selection__placeholder').html(app.trans('Search for pages'));
                }
            }
        });

        $('#menu-links').on('change', function() {
            var $name = $(this).closest('form').find('[name="name"]');
            if ($name.length && !$name.val()) {
                $name.val(this.textContent.trim());
            }
        });

        $('[name="type"]').on('change', function() {
            const $linlTypeEl = $('[name="link_type"]');
            if ($linlTypeEl.length) {
                if (this.value === 'Frontend') {
                    $linlTypeEl.find('option').map(function() {
                        if (['Blog', 'Page'].includes(this.innerText)) {
                            $(this).removeAttr('disabled');
                        }
                    });
                } else {
                    $linlTypeEl.find('option').map(function() {
                        if (['Blog', 'Page'].includes(this.innerText)) {
                            $(this).prop('disabled', true);
                        }
                    });
                }
            }

            $linlTypeEl.val($linlTypeEl.find("option:first").val());
        });
    }
} );