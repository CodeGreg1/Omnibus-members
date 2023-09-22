"use strict";

// define app.adminRoles object
app.adminRoles = {}

// handle on showing role grouping error
app.adminRoles.groupError = function(response) {
    if(response.errors.permissions) {
        if($('#accordion-permissions .group-error').length) {
            $('#accordion-permissions .group-error').remove();
        }

        $.each(response.errors.permissions, function(k,v) {
            $('#accordion-permissions').prepend('<p class="group-error">'+v+'</p>');
        });

        setTimeout(function() {
            $('.group-error').hide();
        }, 3000);
    }
};

// handle on multi check with table
app.adminRoles.tableCheckAll = function() {
    var tableSelector = '#table-role-permissions';

    $.each( $( '.select-all-module-permissions' ).parents('tr'), function() {
      var that = $(this);
      var selectAllPermissionsClass = that.find('.select-all-module-permissions').attr('data-class');

      $.each(that.find('.select-module-permission'), function() {
        var selectPermissionClass = $(this).attr('data-class');

        $( tableSelector ).TableCheckAll({
          checkAllCheckboxClass: '.' + selectAllPermissionsClass,
          checkboxClass: '.' + selectPermissionClass
        });
      });
      
    });

    $( tableSelector ).TableCheckAll({
        checkAllCheckboxClass: '.select-all-permissions',
        checkboxClass: '.select-permission'
    });
};

// initialize functions of app.adminRoles object
app.adminRoles.init = function() {
    app.adminRoles.tableCheckAll();
};

// initialize app.adminRoles object until the document is loaded
$(document).ready(app.adminRoles.init());
