"use strict";

String.prototype.getValueByKey = function (k) {
    var p = new RegExp('\\b' + k + '\\b', 'gi');
    return this.search(p) != -1 ? decodeURIComponent(this.substr(this.search(p) + k.length + 1).substr(0, this.substr(this.search(p) + k.length + 1).search(/(&|;|$)/))) : "";
};

app.blogs = {};

app.blogs.config = {
    categorySelect: '#post-category'
};

app.blogs.categoryChange = function() {
    $(this.config.categorySelect).on('change', function() {
        var route = "/blogs?page=1";
        if (this.value) {
            route += "&category="+this.value;
        }

        window.location = route;
    });
};

app.blogs.loadCategory = function() {
    var category = window.location.search.getValueByKey('category');
    if (!category) {
        category = 'all';
    }
    $(this.config.categorySelect).val(category);
};

app.blogs.sharer = function() {
        var popupSize = {
        width: 780,
        height: 550
    };

    $(document).on('click', '.social-button', function (e) {
        var verticalPos = Math.floor(($(window).width() - popupSize.width) / 2),
            horisontalPos = Math.floor(($(window).height() - popupSize.height) / 2);

        var popup = window.open($(this).prop('href'), 'social',
            'width=' + popupSize.width + ',height=' + popupSize.height +
            ',left=' + verticalPos + ',top=' + horisontalPos +
            ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

        if (popup) {
            popup.focus();
            e.preventDefault();
        }

    });
}

app.blogs.init = function() {
    this.categoryChange();
    this.loadCategory();
    this.sharer();
};

$(document).ready(app.blogs.init());
