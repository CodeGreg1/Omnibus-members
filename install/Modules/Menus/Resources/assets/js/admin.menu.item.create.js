"use strict";

// initialize javascript function after the document is loaded
$(document).ready(function() {
    // define menu item ouput and generates menu items
    var createMenuItemOutput = function(e) {
        var lang = e.length ? e.attr('data-lang') : $(e.target).attr('data-lang');

        var list = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {

            var data = app.adminMenus.generateData(document.querySelector('#create-menu-item-'+lang+' .dd-list'));

            output.val(window.JSON.stringify(data)); //, null, 2));
            $('#menu-create-output-'+lang).val(JSON.stringify(data));
        } else {
            output.val(app.trans('JSON browser support required.'));
        }
    };

    // get language content status
    var languageActive = function() {
        return $("#language-content > .tab-pane.active.show").attr('id');
    };

    // initialize nestable
    if(typeof $.fn.nestable !== 'undefined') {
        if($('.create-menu-item').length) {

            $.each($('.create-menu-item'), function() {
                var id = $(this).attr('id');
                var lang = $(this).attr('data-lang');

                $('#'+id).nestable({
                    group: 1,
                    maxDepth: 3
                }).on('change', createMenuItemOutput);

                createMenuItemOutput($('#create-menu-item-'+lang).data('output', $('#menu-create-output-'+lang)));
            });
        }
    }

    // add menu item click event
    $(document).delegate('#menu-create-form .add-menu-item', 'click', function() {
        // form reset
        app.adminMenus.formReset('#menu-item-create-form');
        // Show add modol item
        $('#add-menu-item-modal').modal('show');
        // Remove editing menu item trigger
        $('#menu-item-create-form').removeAttr('data-edit');
        // Remove editing id
        $('#menu-item-create-form').removeAttr('data-id');

        $('#menu-item-create-form [name=\"link_type\"]').val('Default').trigger( 'change' );
        $('#menu-item-create-form [name=\"name\"]').val('')
    });

    // add menu item event
    $("#menu-item-create-form").on('submit', function(e) {
        e.preventDefault();

        if ($("#menu-item-create-form")[0].checkValidity()) {
            var lang = languageActive();

            var name = $('#menu-item-create-form' + ' [name="name"]').val();
            var link = $('#menu-item-create-form' + ' [name="link"]').val();
            var type = $('#menu-item-create-form' + ' [name="link_type"]').val();
            if (type === 'Custom') {
                link = $('#menu-item-create-form' + ' [name="url"]').val();
            }
            var className = $('#menu-item-create-form' + ' [name="class"]').val();
            var icon = $('#menu-item-create-form' + ' [name="icon"]').val();

            var target = 0;
            if($('#menu-item-create-form' + ' [name="target"]').is(':checked')) {
                target = 1;
            }

            var status = 0;
            if($('#menu-item-create-form' + ' [name="status"]').is(':checked')) {
                status = 1;
            }

            if(!$('#menu-item-create-form').attr('data-edit')) {

                var id = $('#create-menu-item-'+lang+'>.dd-list .dd-item').length;
                id = id + 1;

                var id = $('#create-menu-item-'+lang+'>.dd-list .dd-item').length;
                var html = '<li class="dd-item dd3-item" data-id="'+id+'" id="'+id+'" data-name="'+name+'" data-icon="'+icon+'" data-type="'+type+'" data-link="'+link+'" data-className="'+className+'" data-target="'+target+'" data-status="'+status+'">';
                    html += '<div class="dd-handle dd3-handle"></div>';
                    html += '<div class="dd3-content">';
                        html += '<span class="item-content"><span class="'+icon+'"></span> ' + name + '</span>  <span class="float-right menu-item-actions"><i class="fas fa-edit edit-menu-item-data" title="'+app.trans('Edit')+'"></i><i class="fas fa-times remove-menu-item" title="'+app.trans('Remove')+'"></i></span>';
                    html += '</div>';
                html += '</li>';

                $('#create-menu-item-'+lang+' .dd-empty').remove();

                $('#create-menu-item-'+lang+'>.dd-list').append(html);
            } else {
                $.each($('#create-menu-item-'+lang+' .dd-item'), function() {
                    var editingId = $(this).attr('data-id');
                    var currentEditingId = $('#menu-item-create-form').attr('data-id');
                    if(editingId == currentEditingId) {
                        $(this).attr('data-name', name);
                        $(this).attr('data-icon', icon);
                        $(this).attr('data-type', type);
                        $(this).attr('data-link', link);
                        $(this).attr('data-className', className);
                        $(this).attr('data-target', target);
                        $(this).attr('data-status', status);
                        $(this).find('>.dd3-content>.item-content').html('<span class="'+icon+'"></span> ' + name + '</span>');
                    }
                });
            }

            // close modal
            app.closeModal();

            // rest menu item output
            createMenuItemOutput($('#create-menu-item-'+lang).data('output', $('#menu-create-output-'+lang)));

            // form reset
            app.adminMenus.formReset('#menu-item-create-form');
        } else {
            $("#menu-item-create-form")[0].reportValidity();
        }
        
    });


    // Remove menu item 
    $(document).delegate('.create-menu-item .remove-menu-item', 'click', function() {

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
                    var lang = languageActive();
                    that.parent().parent().parent().remove();
                    createMenuItemOutput($('#create-menu-item-'+lang).data('output', $('#menu-create-output-'+lang)));
                    app.notify(app.trans('Menu item deleted successfully.'));
                }
            }
        });

    });

    // button edit menu item data
    $(document).delegate('#menu-create-form .edit-menu-item-data', 'click', function() {
        // form reset
        app.adminMenus.formReset('#menu-item-create-form');
        // Show add modol item
        $("#add-menu-item-modal").modal('show');

        var ddItem      = $(this).parents('.dd-item');

        var id          = ddItem.attr('data-id');
        var name        = ddItem.attr('data-name');
        var icon        = ddItem.attr('data-icon');
        var type        = ddItem.attr('data-type');
        var link        = ddItem.attr('data-link');
        var className   = ddItem.attr('data-classname');
        var target      = ddItem.attr('data-target');
        var status      = ddItem.attr('data-status');

        $('#menu-item-create-form [name=\"name\"]').val(name);
        $('#menu-item-create-form [name=\"icon\"]').val(icon).trigger( 'change' );
        $('#menu-item-create-form [name=\"link_type\"]').val(type).trigger( 'change' );
        if (type === 'Custom') {
            $('#menu-item-create-form [name=\"url\"]').val(link);
        } else {
            $('#menu-item-create-form [name=\"link\"]').val(link).trigger( 'change' );
        }
        $('#menu-item-create-form [name=\"class\"]').val(className);

        if(target == 1) {
            $('#menu-item-create-form [name=\"target\"]').prop('checked', true);
        }

        if(status == 1) {
            $('#menu-item-create-form [name=\"status\"]').prop('checked', true);
        } else {
            $('#menu-item-create-form [name=\"status\"]').prop('checked', false);
        }

        // trigger that editing the menu item
        $('#menu-item-create-form').attr('data-edit', 1);
        $('#menu-item-create-form').attr('data-id', id);
    });

    // ajax request on creating menu
    $('#menu-create-form').on('submit', function(e) {
        e.preventDefault();

        var $self = this;
        var $button = $('#menu-create-form').find(':submit');
        var $route = $('#menu-create-form').data('action');
        var $data = $('#menu-create-form').serializeArray();
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
                app.formErrors('#menu-create-form', response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
                // Custom element error
                app.adminMenus.groupError(response.responseJSON, 'menu-create-output');
            }
        }); 
    });
});
