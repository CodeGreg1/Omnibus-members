app.packageFeatureReorder = {};

app.packageFeatureReorder.sort = function() {
    var href = $("#package-feature-reorder-list").data('href');
    $( "#package-feature-reorder-list" ).sortable({
        delay: 150,
        handle: '.drag-handle',
        cursor: 'move',
        placeholder:'must-have-class',
        stop: function() {
            var dataOrdering = new Array();
            $('#package-feature-reorder-list li').each(function() {
                var row = $(this);
                var handleCol = row.find('.package-feature-handle-column');
                var id = handleCol.find('>span').attr('data-id');
                var ordering = handleCol.find('>span').attr('data-ordering');

                dataOrdering.push({
                    id: id,
                    ordering: ordering
                });
            });

            if (href) {
                $.ajax({
                    type: 'POST',
                    url:href,
                    dataType: "json",
                    data: {ordering: dataOrdering},
                    success:function(response) {
                        app.notify(response.message);
                        window.location = window.location;
                    }
                })
            }
        }
    });
};

app.packageFeatureReorder.init = function() {
    if(typeof $.fn.sortable !== 'undefined') {
        app.packageFeatureReorder.sort();
    }
};

$(document).ready(app.packageFeatureReorder.init());
