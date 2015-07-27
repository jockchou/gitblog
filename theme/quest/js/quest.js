var Quest = function ($) {

    return {

        initFormPlaceHolder: function () {
            if (!("placeholder" in document.createElement("input"))) {
                $("input[placeholder], textarea[placeholder]").each(function () {
                    var val = $(this).attr("placeholder");
                    if (this.value == "") {
                        this.value = val;
                    }
                    $(this).focus(function () {
                        if (this.value == val) {
                            this.value = "";
                        }
                    }).blur(function () {
                        if ($.trim(this.value) == "") {
                            this.value = val;
                        }
                    });
                });

                // Clear default placeholder values on form submit
                $('form').submit(function () {
                    $(this).find("input[placeholder], textarea[placeholder]").each(function () {
                        if (this.value == $(this).attr("placeholder")) {
                            this.value = "";
                        }
                    });
                });
            }
        },

        initColorbox: function () {
            $('a.gallery').colorbox({
                rel: 'gallery',
                maxWidth: '95%',
                maxHeight: '90%'
            });
        },

        initTooltip: function () {
            $('a[data-toggle=tooltip]').tooltip();
        },

        initMasonry: function () {
            var $container = $('#grid-container');
            if ($container.length > 0) {
                $container.masonry({
                    itemSelector: '.post-grid-wrap'
                });
            }
        },

        //init image hover effects
        initImageEffects: function () {
            if (Modernizr.touch) {
                $(".close-overlay").removeClass("hidden");
                $(".effects").click(function (e) {
                    if (!$(this).hasClass("hover")) {
                        $(this).addClass("hover");
                    }
                });
                $(".close-overlay").click(function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if ($(this).closest(".effects").hasClass("hover")) {
                        $(this).closest(".effects").removeClass("hover");
                    }
                });
            } else {
                $(".effect").mouseenter(function () {
                    $(this).addClass("hover");
                })
                    .mouseleave(function () {
                        $(this).removeClass("hover");
                    });
            }

        },

        showBackToTop: function () {
            var offset = 300,
            //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
                offset_opacity = 1200,
            //grab the "back to top" link
                $back_to_top = $('.cd-top'),
                $window = $(window);
            ($window.scrollTop() > offset) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
            if ($window.scrollTop() > offset_opacity) {
                $back_to_top.addClass('cd-fade-out');
            }
        },

        initBackToTop: function () {
            //smooth scroll to top
            $('.cd-top').on('click', function (event) {
                event.preventDefault();
                $('body,html').animate({
                    scrollTop: 0,
                }, 700);
            });
        },

        init: function () {
            new WOW({offset: $(window).height() / 3}).init();
            Quest.initImageEffects();
            Quest.initColorbox();
            Quest.initTooltip();
            Quest.initBackToTop();
            Quest.initMasonry();
            Quest.initFormPlaceHolder();
        }

    };

}(jQuery);

var PageBuilder = (function ($) {

    return {

        initEvents: function () {

            $('.sl-slider-wrapper').each(function () {
                var $el = $(this),
                    options = $el.data(),
                    defaults = {
                        autoplay: true,
                        onBeforeChange: function (slide, pos) {
                            $nav.removeClass('nav-dot-current');
                            $nav.eq(pos).addClass('nav-dot-current');
                        }
                    },
                    cnt = $el.find('.sl-slide').length;
                $.extend(defaults, options);

                $el.append('<nav class="nav-dots">' + new Array(cnt + 1).join('<span></span>') + '</nav>');

                var $nav = $el.find('.nav-dots > span');
                $nav.first().addClass('nav-dot-current');

                var slitslider = $el.slitslider(defaults),
                    $next = $el.find('.slit-nav-buttons .next'),
                    $prev = $el.find('.slit-nav-buttons .prev');

                $nav.each(function (i) {

                    $(this).on('click', function (event) {

                        var $dot = $(this);

                        if (!slitslider.isActive()) {

                            $nav.removeClass('nav-dot-current');
                            $dot.addClass('nav-dot-current');

                        }

                        slitslider.jump(i + 1);
                        return false;

                    });

                });

                $next.on('click', function (event) {
                    slitslider.next();
                    return false;
                });

                $prev.on('click', function (event) {
                    slitslider.previous();
                    return false;
                });

            });

        }

    }

})(jQuery);


jQuery(window).scroll(function () {
    Quest.showBackToTop();
});

jQuery(document).ready(function () {
    PageBuilder.initEvents();
    Quest.init();
});
