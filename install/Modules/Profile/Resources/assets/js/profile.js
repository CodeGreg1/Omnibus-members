"use strict";

// define app.profile object
app.profile = {};

// handle app.profile object with general sections setting as active
app.profile.generalSections = function() {
    $('.general-profile-sections a, .security-profile-sections a').click(function(){
        $('.general-profile-sections a, .security-profile-sections a').removeClass("active");
        $(this).addClass("active");
    });
};

// handle app.profile object sticky sidebar
app.profile.sticky = function(e) {
    if(typeof $.fn.stickySidebar !== 'undefined') {
        $('.sidebar-item').stickySidebar({
            topSpacing: 0,
            container: '.wrapper-main',
            sidebarInner: '.make-me-sticky'
        });
    }
};

// initialize functions of app.profile object 
app.profile.init = function() {
	this.sticky();
    this.generalSections();
};

// initialize app.profile object until the document is loaded
$( document ).ready( app.profile.init() );