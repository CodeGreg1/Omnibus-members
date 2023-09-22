(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
'use strict';

(function ($) {
    $.fn.stickySidebar = function stickySidebar(settings) {
        // Cache the element
        var $elem = this;

        // Cache the container height
        var $containerHeight = $(settings.container).height();

        // If the element exists on page
        if ($elem.length) {
            var defaults = {
                side: 'left',
                topSpacing: 30,
                disableAt: 785,
                callback: function callback() {}
            };
            var options = Object.assign({}, defaults, settings);

            var applyFixed = function applyFixed(containerLeftPos, sidebarWidth, sidebarHeight) {
                // Apply fixed to sidebar
                $elem.addClass('is-fixed');
                $elem.removeClass('stick-footer');

                $elem.css({
                    position: 'relative'
                });

                // On window resize get sidebar (parent) width
                if (sidebarWidth !== $elem.width()) sidebarWidth = $elem.width();

                // Apply styles to this div
                $(options.sidebarInner).css({
                    position: 'fixed',
                    top: options.topSpacing + 'px',
                    width: sidebarWidth,
                    height: sidebarHeight,
                    display: 'inline-table'
                });

                // If the sidebar is on the right
                if (options.side === 'right') {
                    $(options.sidebarInner).css({ right: containerLeftPos, left: 'initial' });
                } else {
                    // $(options.sidebarInner).css({ left: containerLeftPos });
                }

                // Fire callback
                options.callback();
            };

            var distanceCheck = function distanceCheck(resize) {
                // Distance scrolled from top of screen
                var distanceFromTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;

                // Container height
                var containerHeight = $(options.container).height();

                // Container distance from left
                var containerLeftPos = document.querySelector(options.container).offsetLeft;

                // Container distance from top
                var containerTopPos = document.querySelector(options.container).offsetTop;

                // Container bottom
                var containerBottomPos = containerHeight + containerTopPos;

                // Sidebar height & width
                var sidebarHeight = $(options.sidebarInner).outerHeight();
                var sidebarWidth = $(options.sidebarInner).outerWidth();

                // Stop position
                var stopPos = containerBottomPos - sidebarHeight;

                /**
                 * On window resize run applyFixed
                 */
                if (resize) {
                    applyFixed(containerLeftPos, sidebarWidth, sidebarHeight);
                }

                /**
                 * Scroll past
                 */
                if (distanceFromTop + options.topSpacing > containerTopPos && distanceFromTop + options.topSpacing < stopPos) {
                    if (!$elem.hasClass('is-fixed')) {
                        applyFixed(containerLeftPos, sidebarWidth, sidebarHeight);
                    }
                }

                /**
                 * Scrolled back to header
                 */
                if (distanceFromTop + options.topSpacing < containerTopPos) {
                    if ($elem.hasClass('is-fixed')) {
                        $elem.removeClass('is-fixed');
                        // Remove inline styles
                        $elem.attr('style', '');
                        $(options.sidebarInner).attr('style', '');
                    }
                }

                /**
                 * On container height resize
                 */
                if ($containerHeight === $(options.container).height()) {
                    /**
                     * Scrolled passed container
                     */
                    if (distanceFromTop + options.topSpacing > stopPos) {
                        if (!$elem.hasClass('stick-footer')) {
                            // Make sidebar fixed to bottom of container
                            $elem.removeClass('is-fixed');
                            $elem.addClass('stick-footer');
                            $(options.sidebarInner).css({
                                position: 'relative',
                                top: containerHeight - sidebarHeight,
                                // left: 0,
                                width: '100%',
                                height: sidebarHeight
                            });
                        }
                    }
                } else {
                    /**
                     * Container height has changed, reset cached height
                     */
                    $containerHeight = $(options.container).height();

                    // Remove stick
                    $elem.removeClass('is-fixed');
                    $elem.attr('style', '');
                    $(options.sidebarInner).attr('style', '');
                }
            };

            // Trigger on scroll
            $(window).on('scroll', function () {
                if (window.innerWidth > options.disableAt) {
                    distanceCheck();
                }
            });

            // Trigger on resize
            $(window).on('resize', function () {
                if (window.innerWidth > options.disableAt) {
                    distanceCheck(true);
                } else {
                    // Remove stick
                    $elem.removeClass('is-fixed');
                    $elem.attr('style', '');
                    $(options.sidebarInner).attr('style', '');
                }
            });

            // Trigger on load
            if (window.innerWidth > options.disableAt) {
                setTimeout(function () {
                    distanceCheck();
                }, 0);
            }
        }
        return this;
    };
})(jQuery);

},{}]},{},[1]);
