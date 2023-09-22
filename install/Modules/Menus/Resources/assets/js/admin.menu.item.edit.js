"use strict";

// initialize javascript function after the document is loaded
$(document).ready(function() {
    // define menu item ouput and generates menu items
    var editMenuItemOutput = function(e) {
        var lang = e.length ? e.attr('data-lang') : $(e.target).attr('data-lang');

        var list = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {

            var data = app.adminMenus.generateData(document.querySelector('#edit-menu-item-'+lang+' .dd-list'));

            output.val(window.JSON.stringify(data)); //, null, 2));
            $('#menu-edit-output-'+lang).val(JSON.stringify(data));
        } else {
            output.val(app.trans('JSON browser support required'));
        }
    };

    // get language content status
    var languageActive = function() {
        return $("#language-content > .tab-pane.active.show").attr('id');
    };

    // initialize nestable
    if(typeof $.fn.nestable !== 'undefined') {
        if($('.edit-menu-item').length) {

            $.each($('.edit-menu-item'), function() {
                var id = $(this).attr('id');
                var lang = $(this).attr('data-lang');

                $('#'+id).nestable({
                    group: 1,
                    maxDepth: 3
                }).on('change', editMenuItemOutput);

                editMenuItemOutput($('#edit-menu-item-'+lang).data('output', $('#menu-edit-output-'+lang)));
            });
        }
    }

    // add menu item click event
    $(document).delegate('#menu-edit-form .add-menu-item', 'click', function() {
        // form reset
        app.adminMenus.formReset('#menu-item-edit-form');
        // Show add modol item
        $('#add-menu-item-modal').modal('show');
        // Remove editing menu item trigger
        $('#menu-item-edit-form').removeAttr('data-edit');
        // Remove editing id
        $('#menu-item-edit-form').removeAttr('data-id');

        $('#menu-item-edit-form [name=\"link_type\"]').val('Default').trigger( 'change' );
        $('#menu-item-edit-form [name=\"name\"]').val('')
    });

    // add menu item event
    $("#menu-item-edit-form").on('submit', function(e) {
        e.preventDefault();

        if ($("#menu-item-edit-form")[0].checkValidity()) {
            var lang = languageActive();

            var name = $('#menu-item-edit-form' + ' [name="name"]').val();
            var icon = $('#menu-item-edit-form' + ' [name="icon"]').val();
            var className = $('#menu-item-edit-form' + ' [name="class"]').val();
            var link = $('#menu-item-edit-form' + ' [name="link"]').val();
            var type = $('#menu-item-edit-form' + ' [name="link_type"]').val();
            if (type === 'Custom') {
                link = $('#menu-item-edit-form' + ' [name="url"]').val();
            }

            var target = 0;
            if($('#menu-item-edit-form' + ' [name="target"]').is(':checked')) {
                target = 1;
            }

            var status = 0;
            if($('#menu-item-edit-form' + ' [name="status"]').is(':checked')) {
                status = 1;
            }

            if(!$('#menu-item-edit-form').attr('data-edit')) {
                var html = '<li class="dd-item dd3-item" data-name="'+name+'" data-icon="'+icon+'" data-link="'+link+'" data-type="'+type+'" data-className="'+className+'" data-target="'+target+'" data-status="'+status+'">';
                    html += '<div class="dd-handle dd3-handle"></div>';
                    html += '<div class="dd3-content">';
                        html += '<span class="item-content"><span class="'+icon+'"></span> ' + name + '</span>  <span class="float-right menu-item-actions"><i class="fas fa-edit edit-menu-item-data" title="Edit"></i><i class="fas fa-times remove-menu-item" title="Remove"></i></span>';
                    html += '</div>';
                html += '</li>';

                $('#edit-menu-item-'+lang+' .dd-empty').remove();

                $('#edit-menu-item-'+lang+'>.dd-list').append(html);
            } else {
                $.each($('#edit-menu-item-'+lang+' .dd-item'), function() {
                    var editingId = $(this).attr('data-id');
                    var currentEditingId = $('#menu-item-edit-form').attr('data-id');

                    if(editingId == currentEditingId) {
                        $(this).attr('data-name', name);
                        $(this).attr('data-type', type);
                        $(this).attr('data-link', link);
                        $(this).attr('data-icon', icon);
                        $(this).attr('data-className', className);
                        $(this).attr('data-target', target);
                        $(this).attr('data-status', status);
                        $(this).find('>.dd3-content>.item-content').html('<span class="'+icon+'"></span> ' + name + '</span>');
                    }
                });
            }

            app.closeModal();

            editMenuItemOutput($('#edit-menu-item-'+lang).data('output', $('#menu-edit-output-'+lang)));
        }

        // app.adminMenus.formReset('#menu-item-edit-form');
    });

    // Edit menu item data 
    $(document).delegate('#menu-edit-form .edit-menu-item-data', 'click', function() {

        var formId = '#menu-item-edit-form';

        // form reset
        app.adminMenus.formReset(formId);
        // Show add modol item
        $("#add-menu-item-modal").modal('show');

        var ddItem      = $(this).parents('.dd-item');

        var id          = ddItem.attr('data-id');
        var name        = ddItem.attr('data-name');
        var icon        = ddItem.attr('data-icon');
        var type        = ddItem.attr('data-type');
        var link        = ddItem.attr('data-link');
        var linkTitle   = ddItem.attr('data-link-title');
        var className   = ddItem.attr('data-classname');
        var target      = ddItem.attr('data-target');
        var status      = ddItem.attr('data-status');

        $(formId + ' [name=\"name\"]').val(name);
        $(formId + ' [name=\"icon\"]').val(icon).trigger( 'change' );
        $(formId + ' [name=\"class\"]').val(className);
        $(formId + ' [name=\"link_type\"]').val(type).trigger( 'change' );

        if (type === 'Custom') {
            $(formId + ' [name=\"url\"]').val(link);
        } else {
            if (linkTitle) {
                $(formId + ' [name=\"link\"]').append($('<option>', {
                    value: link,
                    text: linkTitle
                }));
            }
            $(formId + ' [name=\"link\"]').val(link).trigger( 'change' );
        }

        if(target == 1) {
            $(formId + ' [name=\"target\"]').prop('checked', true);
        }

        if(status == 1) {
            $(formId + ' [name=\"status\"]').prop('checked', true);
        } else {
            $(formId + ' [name=\"status\"]').prop('checked', false);
        }

        // trigger that editing the menu item
        $(formId).attr('data-edit', 1);
        $(formId).attr('data-id', id);
    });

    // Remove menu item with ajax request
    $(document).delegate('.edit-menu-item .remove-menu-item', 'click', function() {
        var that = $(this);

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("Your about to remove this item!"),
            buttons: {
                confirm: {
                    label: app.trans('Yes'),
                    className: 'btn-danger'
                },
                cancel: {
                    label: app.trans('No'),
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if ( result ) {

                    var menuItem = that.parent().parent().parent();

                    $.ajax({
                        type: 'delete',
                        url: '/admin/menus/menu-items/delete',
                        data: {id:menuItem.attr('data-id')},
                        success: function (response, textStatus, xhr) {
                            var lang = languageActive();
                            menuItem.remove();
                            editMenuItemOutput($('#edit-menu-item-'+lang).data('output', $('#menu-edit-output-'+lang)));
                            app.notify(response.message);
                        },
                        error: function (response) {
                            app.notify(response.message);
                        }
                    });
                }
            }
        });
            
    });

    // ajax request updating menu
    $('#menu-edit-form').on('submit', function(e) {
        e.preventDefault();

        var $self = this;
        var $button = $('#menu-edit-form').find(':submit');
        var $route = $('#menu-edit-form').data('action');
        var $data = $('#menu-edit-form').serializeArray();
        var $content = $button.html();

        $.ajax({
            type: 'POST',
            url: $route,
            data: $data,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.redirect(response.data.redirectTo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors('#menu-edit-form', response.responseJSON, response.status);
                // Custom element error
                app.adminMenus.groupError(response.responseJSON, 'menu-edit-output');
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 
    });
});
