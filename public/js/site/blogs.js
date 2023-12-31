"use strict";
(String.prototype.getValueByKey = function (t) {
    var o = new RegExp("\\b" + t + "\\b", "gi");
    return -1 != this.search(o) ? decodeURIComponent(this.substr(this.search(o) + t.length + 1).substr(0, this.substr(this.search(o) + t.length + 1).search(/(&|;|$)/))) : "";
}),
    (app.blogs = {}),
    (app.blogs.config = { categorySelect: "#post-category" }),
    (app.blogs.categoryChange = function () {
        $(this.config.categorySelect).on("change", function () {
            var t = "/blogs?page=1";
            this.value && (t += "&category=" + this.value), (window.location = t);
        });
    }),
    (app.blogs.loadCategory = function () {
        var t = window.location.search.getValueByKey("category");
        t || (t = "all"), $(this.config.categorySelect).val(t);
    }),
    (app.blogs.sharer = function () {
        var t = 780,
            o = 550;
        $(document).on("click", ".social-button", function (e) {
            var a = Math.floor(($(window).width() - t) / 2),
                i = Math.floor(($(window).height() - o) / 2),
                n = window.open($(this).prop("href"), "social", "width=" + t + ",height=" + o + ",left=" + a + ",top=" + i + ",location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1");
            n && (n.focus(), e.preventDefault());
        });
    }),
    (app.blogs.init = function () {
        this.categoryChange(), this.loadCategory(), this.sharer();
    }),
    $(document).ready(app.blogs.init());
