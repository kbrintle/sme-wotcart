$(function () {
    global.init();
    favorites.init();
    quotes.init();
    accountLocator.init();
    newsletter.init();
    masonry.init();
    email_modals.init();
    mobileShop.init();
    comViewer.init();
});

global = {
    init: function () {
        this.events();
    },
    events: function () {

        $(".navbar-collapse").css({maxHeight: $(window).height() - $(".navbar-header").height() + "px"});

        $('.flexslider').flexslider({
            controlNav: false,
            animation: "slide",
            animationLoop: false,
            itemWidth: 210,
            itemMargin: 5
        });

        $(document).on('click', '.panel[data-href]', function () {
            window.location = $(this).attr('data-href');
        });

        $(document).ready(function () {
            $("img.zoom")
                .wrap("<span style='cursor: -moz-zoom-in;cursor: -webkit-zoom-in;cursor: zoom-in;display:inline-block;'></span>")
                .css("display", "block")
                .parent()
                .zoom();
        });

        $('body').on('click', '.product-gallery--thumbs li img', function () {
            // alert("adsasdasd");
            // alert($(this).attr('src'));


            $('.product-gallery--img img').prop('src', $(this).attr('src'))
        });

        $('body').on('click', '.pagination-top', function () {
            $(window).scrollTop(0);
        });
        $('body').on('click', '.pagination-top', function () {
            $(window).scrollTop(0);
        });
    }
},
    email_modals = {
        init: function () {
            this.events();
        },
        events: function () {
            $('#email-store').on('submit', function (e) {
                e.preventDefault();
                if ($(this).attr('id') !== 'searchForm') {  //some check
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'post',
                        data: $(this).serialize(),
                        success: function () {
                            $('#emailStore .message ').removeClass('hidden');
                            $('#emailStore .message').text("Your message has been sent to this location.");
                        },
                        error: function () {
                            $('#emailStore .message').removeClass('hidden');
                            $('#emailStore .message').text("Your message was not sent successfully. Try again later.");
                        },
                    });
                    return false;
                }
            });
        }
    };

options = {

    checkRequired: function () {
        var valid = true;
        $(".option-group").each(function () {

            var id = $(this).attr("id");
            if ($(this).attr("isrequired") === "1") {
                switch ($(this).attr("type")) {
                    case "radio":
                        if (!$("#" + id + " input[name = " + id + "]:checked").val()) {
                            if ($("#" + id + " > #req-" + id).length === 0) {
                                $(this).prepend("<span id='req-" + id + "' style='color:red'>Required<br><span>");
                            }
                            //$(this).css("border", "1px solid red");
                            valid = false;
                        } else {
                            if ($("#" + id + " > #req-" + id).length !== 0) {
                                $("#" + id + " > #req-" + id).remove();
                            }
                        }
                        break;
                    case "dropdown":
                        if ($("#" + id + " > select[name = " + id + "] > option:selected").attr("name") === "null") {
                            if ($("#" + id + " > #req-" + id).length === 0) {
                                $(this).prepend("<span id='req-" + id + "' style='color:red'>Required<br><span>");
                            }
                            //$(this).css("border", "1px solid red");
                            valid = false;
                        } else {
                            if ($("#" + id + " > #req-" + id).length !== 0) {
                                $("#" + id + " > #req-" + id).remove();
                            }
                        }
                        break;
                    case "checkbox":
                        if ($("#" + id + " input[name = " + id + "]:checkbox:checked").length === 0) {
                            if ($("#" + id + " > #req-" + id).length === 0) {
                                $(this).prepend("<span id='req-" + id + "' style='color:red'>Required<br><span>");
                            }
                            //$(this).css("border", "1px solid red");
                            valid = false;
                        } else {
                            if ($("#" + id + " > #req-" + id).length !== 0) {
                                $("#" + id + " > #req-" + id).remove();
                            }
                        }
                        break;
                    default:
                        break;
                }
            }
        });

        return valid;
    },

    addSkus: function (sku) {
        var delimiter = $("#options").data("delimiter");
        var $this = "";
        $(".custom-option-sel").each(function () {
            $this = $(this);
            if ($this.is(":radio")) {
                if ($this.is(":checked")) {
                    sku += delimiter + $this.attr("data-sku");
                }
            } else if ($this.is("select")) {
                if ($this.find(":selected").attr("data-sku")) {
                    sku += delimiter + $this.find(":selected").attr("data-sku");
                }
            } else if ($this.is(":checkbox")) {
                if ($this.is(":checked")) {
                    sku += delimiter + $this.attr("data-sku");
                }
            }
        });

        console.log(sku);
        return sku;
    }
};

quotes = {
    init: function () {
        this.events();
    },
    events: function () {

        $(document).on('click', ' #get-quote', function () {
            var $this = $(this);
            var valid = options.checkRequired();
            if (valid) {
                $.ajax({
                    url: $this.data("url"),
                    type: 'POST',
                    data: {
                        sku: $this.data("sku")
                    },
                    success: function (response) {
                        $($this.data("target")).html(response).modal('show');
                    }
                });

            }
        });

        $(document).on('click', '#quote-submit', function () {
            var form = $("form#quote-form");
            // return false if form still have some validation errors
            if (form.find('.has-error').length) {
                return false;
            }

            var skus = [];
            if ($("#grouped").length !== 0) {
                $(".grouped-item-sel").each(function () {
                    if ($(this).val() > 0) {
                        skus.push({"pid": $(this).data("pid"), "qty": $(this).val()});
                    }
                });

                if (skus.length > 0) {
                    $("#getquoteform-product").val(JSON.stringify(skus));
                }
            }

            if ($("#options").length !== 0) {
                var valid = options.checkRequired();
                if (valid) {
                    var qty = $("#quantity");
                    skus.push({
                        "pid": qty.data("pid"),
                        "sku": options.addSkus(qty.data("sku")),
                        "qty": qty.val()
                    });
                    if (skus.length > 0) {
                        $("#getquoteform-product").val(JSON.stringify(skus));
                    }
                } else {
                    return;
                }
            }

            // submit form
            $.ajax({
                url: form.attr('action'),
                type: 'post',
                data: form.serialize(),
                success: function (response) {
                    $("#getQuoteModal .success").removeClass("hidden");
                    $("#getQuoteModal .fields").addClass("hidden");
                    $("#getQuoteModal .btn-primary").addClass("hidden");
                },
                error: function () {
                    console.log('internal server error');
                }
            });
            return false;
        });
    }
};

favorites = {
    init: function () {
        this.events();
    },


    events: function () {

        if ($(".favorite-list").length > 0) {
            $('#favorite-update').height($('#favorite-update').height());
            $('tbody').sortable({});

        }
        if ($("#favorite-update").length > 0) {
            $(document).on('click', '.submit-move-favorites', function () {
                var form = $("#favorite-update form");
                $.ajax({
                    url: form.attr('action'),
                    type: 'post',
                    data: form.serialize() + "&move_to=" + $('#lists').val(),
                    success: function (response) {
                        $("#moveToModal").modal("hide");
                        if (response !== "1") {
                            var html = '<div class="alert-danger text-center alert fade in">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                                response + '</div>';
                            $(html).insertBefore(".page-content");s
                        } else {
                            $('input[type="checkbox"]').each(function () {
                                form.hide();
                                var $this = $(this);
                                if ($this.is(":checked")) {
                                    $this.parents().eq(1).remove();
                                    console.log($("tbody .ui-sortable").text());
                                    if ($("tbody .ui-sortable").text() == '') {
                                        console.log($("tbody").text());
                                        location.reload();
                                    }
                                }
                            });
                        }
                    }
                });
            });
        }

        $(document).on('click', '.favorite >.modal-click', function () {
            var $this = $(this);
            $.ajax({
                url: $this.data('url'),
                type: 'POST',
                data: {
                    sku: $this.data('sku')
                },
                success: function (response) {
                    $($this.data('target')).html(response);
                }
            });
        });

        $(document).on("click", ".submit-favorites", function () {
            var skus = [];
            var sku = $(this).data("sku");
            var pid = $(this).data("pid");
            var items = $(".grouped-item-sel");
            if (items.length === 0) {
                if ($("#options").length !== 0) { //custom options
                    var valid = options.checkRequired();
                    if (valid) {
                        sku = options.addSkus(sku);
                    } else {
                        return;
                    }
                }
                skus.push({"sku": sku, "qty": $("#quantity").val(), "pid": pid});

            } else { //grouped products
                var $this;
                items.each(function () {
                    if ($(this).val() > 0) {
                        $this = $(this);
                        skus.push({"sku": $this.data('sku'), "qty": $this.val(), "pid": $this.data("pid")});
                    }
                });
            }
            if (skus.length > 0) {
                favorites.process(skus);
            }
        });
    },

    ajaxBtn: function (text) {
        var submitBtn = $(".submit-favorites");
        submitBtn.css("padding", "12px 48.46px");
        submitBtn.html(text);
        setTimeout(function () {
            $("#favoritesModal").modal("hide");
            setTimeout(function () {
                submitBtn.css("padding", "");
                submitBtn.html("ADD TO FAVORITES");
            }, 700);
        }, 700);
    },

    process: function (sku) {
        var form = $(".modal form");
        $.ajax({
            url: form.data("action"),
            type: "post",
            data: {
                skus: sku,
                folder: $("#Folders").find(":selected").val()
            },

            success: function () {
                favorites.ajaxBtn("success");
            },
            error: function () {
                favorites.ajaxBtn("error");
            }
        });
    }
};

accountLocator = {
    init: function () {
        this.events();
    },
    selectors: {
        form: $('form#zip-form'),
        errorMessage: $('#zip-error-message')
    },
    events: function () {
        var $this = this;
        $('body').on('beforeSubmit', '#zip-form', function (e) {
            var form = $(this);

            // return false if form still have some validation errors
            if (form.find('.has-error').length) {
                return false;
            }

            // submit form
            $this.removeErrorMessage();
            $this.clearZipFound();
            $.ajax({
                url: form.attr('action'),
                type: 'post',
                data: form.serialize(),
                success: function (response) {
                    console.log(response);
                    var res = JSON.parse(response);
                    console.log(res);
                    if ($.isArray(res.store)) {
                        $this.populateZipFound(res);
                    } else {
                        window.location = res.storeUrl + "" + res.redirectUrl;
                    }
                },
                fail: function (jqXHR, textStatus) {
                    console.error(jqXHR, textStatus);
                    $this.updateErrorMessage('There was an error trying to find stores');
                }
            });
            return false;
        });
    },
    populateZipFound: function (res) {
        var output = '';

        $.each(res.store, function (i, store) {
            output += '<div class="row form-group">';
            output += '<div class="col-xs-12 col-md-6"><h4>' + store.store.name + '</h4></div>';
            output += '<div class="col-xs-12 col-md-6"><h5>' + (Math.round(store.distance * 100) / 100) + ' Miles</h5><h6>Zip: ' + store.zip_code + '</h6></div>';
            output += '<div class="col-xs-12"><a href="/' + store.store.url + res.redirectUrl + '" class="btn col-xs-12">Set as my Store</a></div>';
            output += '</div>';
        });

        $('#zip_found').html(output);
    },
    clearZipFound: function () {
        $('#zip_found').empty();
    },
    updateErrorMessage: function (message) {
        this.selectors.errorMessage.removeClass('hidden').text(message);
    },
    removeErrorMessage: function () {
        this.selectors.errorMessage.addClass('hidden').text('');
    }
};


newsletter = {
    init: function () {
        this.events();
    },
    events: function () {

        $('body').on('beforeSubmit', '#newsletter-form', function (e) {

            e.preventDefault();
            e.stopPropagation();
            var form = $(this);

            console.log(form);
            // return false if form still have some validation errors
            if (form.find('.has-error').length) {
                return false;
            }
            // submit form

            $.ajax({
                url: form.attr('action'),
                type: 'post',
                data: form.serialize(),
                success: function (response) {
                    $('#newsletter-success').modal('show')
                    console.log(response);
                    // do something with response
                }
            });
            return false;
        });

    }
}

var masonry = {
    selectors: {
        parent: '.masonry-grid',
        child: '.grid-item'
    },
    init: function () {
        this.layout();
    },
    layout: function () {
        var $this = this;

        $(this.selectors.parent).masonry({
            itemSelector: this.selectors.child
        });

    },
    reLayout: function () {

    }
};

var pjaxLoader = {
    start: function () {
        $('#loading_modal').addClass('active');
    },
    end: function () {
        $('#loading_modal').removeClass('active');
    }
};

var mobileShop = {
    elem: '.mobile_filters',
    active_class: 'active',
    limit: 185,
    init: function () {
        var $this = this;
        $(window).scroll(function (e) {
            var scroll_distance = $(this).scrollTop();
            $this.process(scroll_distance);
        });
    },
    process: function (scroll_distance) {
        var $this = this;
        if (scroll_distance >= $this.limit)
            $($this.elem).addClass($this.active_class);
        else
            $($this.elem).removeClass($this.active_class);
    }
};


//animations for site/index

var animation_elements = document.querySelectorAll('.animate');

animation_elements = Array.prototype.slice.call(animation_elements); // Make animation_elements a true array so we can iterate through it

var check_if_in_view = function () {
    var window_height = window.innerHeight;
    var window_top_position = document.body.scrollTop;
    var window_bottom_position = (window_top_position + window_height);

    animation_elements.forEach(function (e) {
        var element_height = e.offsetHeight;
        var element_top_position = parseInt(e.offsetTop);
        var element_bottom_position = (element_top_position + element_height);

        // Check to see if this current container is within viewport
        if ((element_bottom_position >= window_top_position) &&
            (element_top_position <= window_bottom_position)) {
            e.classList.add('in-view');
        }
    });
}

window.addEventListener('scroll', check_if_in_view);
window.addEventListener('resize', check_if_in_view);

// Check for any animated elements already visible when the page is loaded
document.addEventListener('load', check_if_in_view);

// Com Viewer
var comViewer = {
    init: function () {
        this.events();
        this.resize();
    },
    events: function () {
        $('body').on('click', '.com-thumb', function () {
            var src = $(this).attr('data-com');
            $('.com-main-vid').html(src);

            $('.com-main-vid iframe').attr('width', '100%');
            $('.com-main-vid iframe').attr('height', '340');

            $('.com-thumb').each(function () {
                $(this).removeClass('active');
            });
            $(this).addClass('active');
        });
    },
    resize: function () {

        $('.com-main-vid iframe').attr('width', '100%');
        $('.com-main-vid iframe').attr('height', '340');

        $('.thumb iframe').each(function () {
            console.log($(this).attr('src'));
            $(this).attr('width', '150');
            $(this).attr('height', '85');
        });
    }
};

var buttonUp = function () {
    var inputVal = $('.searchbox-input').val();
    inputVal = $.trim(inputVal).length;
    if (inputVal !== 0) {
        $('.searchbox-icon').css('display', 'none');
    } else {
        $('.searchbox-input').val('');
        $('.searchbox-icon').css('display', 'block');
    }
}


// keep divs same height

$(document).ready(function () {
    var height = Math.max($("#account-left").height(), $("#account-right").height());
    $("#account-left").height(height);
    $("#account-right").height(height);
});

//toggle guest/login modals

$(document).ready(function () {
    $("#login-link").click(function () {
        $("#modal-guest").hide();
        $("#modal-login").show();
    });

    $("#guest-link").click(function () {
        $("#modal-login").hide();
        $("#modal-guest").show();
    });
});

// Show Call Us btn on mobile on scroll
$(window).scroll(function () {

    clearTimeout($.data(this, "scrollCheck"));
    $('.tel-number-mobile').slideUp();
    $.data(this, "scrollCheck", setTimeout(function () {
        $('.tel-number-mobile').slideDown();
    }, 350));

});


// When the user clicks on pagination, scroll to the top of the document
$(document).ready(function () {
    $('.pagination-top').on("click", function () {
        $(window).scrollTop(0);
    });
});


// Slide mobile nav from right
jQuery(function ($) {
    $('.navbar-toggle').click(function () {
        $('.navbar-collapse').toggleClass('right');
        $('.navbar-toggle').toggleClass('indexcity');
    });
});


