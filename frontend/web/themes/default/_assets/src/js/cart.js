$(function () {
    shipping.init();
    promocode.init();
    rewardpoints.init();
    cart.init();
    checkout.init();
});

shipping = {
    init: function () {
        this.events();
    },

    events: function () {
        $('body').on('change', '.store-delivery', function () {
            shipping.storeDelivery();
        });
        $('body').on('change', '.store-pickup', function () {
            shipping.storePickup();
        });
    },


    storeDelivery: function () {
        var store = $('#store-url').val();

        $.ajax({
            url: '/' + store + '/checkout/delivery-method',
            type: 'POST',
            data: {
                method: 'store-delivery'
            },

            success: function (response) {
                if (response) {
                    $('#order-summary-container').html(response);
                }
            }
        });
    },

    storePickup: function () {
        var store = $('#store-url').val();

        $.ajax({
            url: '/' + store + '/checkout/delivery-method',
            type: 'POST',
            data: {
                method: 'store-pickup'
            },

            success: function (response) {
                if (response) {
                    $('#order-summary-container').html(response);
                }
            }
        });
    }
};

promocode = {
    init: function () {
        this.events();
    },

    events: function () {
        $('body').on('click', '#promo-apply', function () {
            promocode.process($(this), $('#promo-code').val());
        });
        $('body').on('click', '#promo-remove', function () {
            promocode.process($(this), 'CLEAR');
        });
        $('body').on('click', '.gift-choose', function () {
            promocode.process($(this), {'GIFT_ID': $(this).data('pid')});
        });
    },

    process: function (elem, code) {
        $.ajax({
            'url': $(elem).data('action'),
            'type': 'POST',
            'data': {'code': code},

            success: function (response) {
                response = $.parseJSON(response);

                if (/<[a-z][\s\S]*>/i.test(response)) {
                    $("#freeProduct").html(response).modal("show");
                    return true;
                }
                if (response.discount === 'NONE' && response.code !== 'CLEAR') {
                    var message_box = $('#promo .cart-summary-label small')
                    message_box.html('Sorry, that code has expired or is invalid.');
                    setTimeout(function () {
                        message_box.html("");
                    }, 4000);
                } else {
                    if ($('#freeProduct').hasClass('in')) {
                        $('#freeProduct').modal("hide");
                    }
                    cart.process($("#promo"), null, 'refresh', null, null);
                }
            }
        });

        return false;
    }
};


rewardpoints = {
    init: function () {
        this.events();
    },

    events: function () {
        $('body').on('click', '#reward-apply', function () {
            rewardpoints.process($(this), $("#reward-points").val());
        });
        $('body').on('click', '#reward-remove', function () {
            rewardpoints.process($(this), 'CLEAR');
        });
    },

    process: function (elem, points) {
        $.ajax({
            url: $(elem).data("action"),
            type: 'POST',
            data: {points: points},

            success: function (response) {
                response = $.parseJSON(response);

                var rewardError = $("#reward .reward-label .reward-error");
                if (response.code === "ERROR") {
                    rewardError.addClass("error");
                    if (response.discount === "OVER") {
                        rewardError.html(" Must be " + response.points + " or less");
                        return;
                    }
                    if (response.discount === "NOT_INT") {
                        rewardError.html("Must be a whole number");
                        return;
                    }
                    if (response.discount === "CART_VALUE") {
                        rewardError.html("Cannot apply more points then your cart is worth");
                        return;
                    }
                } else {
                    rewardError.removeClass("error");
                    rewardError.html("success");
                    setTimeout(function () {
                        rewardError.html("");
                    }, 3000);
                    cart.process($("#promo"), null, 'refresh', null, null);
                }
            }
        });

        return false;
    }
};

cart = {
    init: function () {
        this.events();
    },

    badge: function (count) {

        if (parseInt(count, 10) > 0) {
            $("#cartBadge").html("Cart (" + count + ")");
        } else {
            $("#cartBadge").html("Cart");
        }
    },

    calcPrice: function () {
        var basePrice = 0;
        if ($("#itemPrice").length !== 0) {
            var itemPrice = $("#itemPrice")
            basePrice = parseFloat(itemPrice.data("price"));
            $(".custom-option-sel").each(function () {
                if ($(this).is(":radio")) {
                    if ($(this).is(":checked")) {
                        basePrice += parseFloat($(this).data("price"));
                    }
                } else if ($(this).is("select")) {
                    basePrice += parseFloat($(this).find(":selected").data("price"));
                } else if ($(this).is(":checkbox")) {
                    if ($(this).is(":checked")) {
                        basePrice += parseFloat($(this).data("price"));
                    }
                }
            });
            itemPrice.html("$" + basePrice.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        }
    },
    events: function () {
        $(".custom-option-sel").change(function () {
            cart.calcPrice();
        });

        $("body").on("click", ".cart-add", function () {
            var $this = $(this);
            if ($this.data("pid") && $this.data("sku")) {
                var sku = $this.data("sku");
                var qty = $(".grouped-item-sel").val();
                if ($this.prop("id") === "addCart") { // (CPM WOT #AddCart check because the model with .cart-add can exist on the same page as this button)
                    if ($("#options").length !== 0) {
                        var valid = options.checkRequired();
                        if (valid) {
                            sku = options.addSkus(sku);
                        } else {
                            return;
                        }
                    }
                    if ($("#grouped").length !== 0) {
                        var skus = [];

                        $(".grouped-item-sel").each(function () {
                            var $gthis = $(this);
                            if ($gthis.val() > 0) {
                                skus.push({"sku": $gthis.data("sku"), "qty": $gthis.val(), "pid": $gthis.data("pid")});
                            }
                        });
                        if (skus.length > 0) {
                            cart.process($this, $this.data("pid"), "addGrouped", skus, null);
                        }
                    } else {
                        if ($("#quantity").val()) { //WOT CPM validate quantity
                            var qtyElement = $("#quantity");
                            var val = qtyElement.val();
                            if (!isNaN(val)) {
                                val = Math.round(val);
                                qty = val;
                            } else {
                                qtyElement.val(null);
                                qtyElement.attr("placeholder", "invalid amount");
                                qtyElement.css("border", "1px solid red");
                                setTimeout(function () {
                                    qtyElement.attr("placeholder", "");
                                    qtyElement.css("border-color", "#d2d2d2");
                                }, 2000);
                                return;
                            }
                        }
                        if ($this.data("free")) {
                            promocode.process($this, {
                                'GIFT_SKU': {
                                    "sku": sku,
                                    "pid": $this.attr('data-pid')
                                }
                            });
                        } else {
                            cart.process($this, $this.data("pid"), 'add', sku, qty);
                        }
                    }
                } else {
                    cart.process($this, $this.data("pid"), 'add', sku, 1);
                }
            }
        });
        $("body").on('click', '.cart-sub', function () {
            var sku = $(this).data("sku");
            cart.process($(this), $(this).data("pid"), 'sub', sku, 1);
        });
        $("body").on('click', '.cart-remove', function () {
            var sku = $(this).data("sku");
            cart.process($(this), $(this).data("pid"), 'remove', sku, 1);
        });
        $("body").on('click', ".cart-clear", function () {
            cart.clear($(this));
        });
        $("body").on("click", ".child-select", function () {
            cart.addChild($(this));
        });
    },

    process: function (elem, pid, action, sku, qty) {
        var form = $("#product-form");
        var url = 0;
        var page = "cart";
        if ($("form#form-checkout").length > 0) {
            page = "checkout";
        }
        if (form.length > 0) {
            if (form.find(".has-error").length) {
                return false;
            }
            if (!form.data("action")) {
                return false;
            } else {
                url = form.data("action");
            }
        } else {
            if (elem.data("action")) {
                url = elem.data("action");
            }
        }

        if (url.length > 0) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    pid: pid,
                    sku: sku,
                    qty: qty,
                    action: action,
                    page: page
                },

                success: function (response) {
                    response = $.parseJSON(response);
                    cart.badge(response.items.totalQty);
                    $('#cart_line_items').html(response.lineItemHTML);
                    $('#order_summary').html(response.orderSumHTML);
                    $('#cart_modal_line_items').html(response.lineItemHTML);

                    if (response.items.totalQty <= 0) {
                        $("#order_summary").hide();
                        $(".checkout-buttons").hide();
                    }
                    if ((action === 'add' || action === 'addGrouped') && $('.cart-page-no-modal').length === 0) {
                        cart.cartModal();
                    }
                }
            });
        } else {
            return false;
        }
    },

    cartModal:

        function () {
            $('#cartModal').modal('show');
            setTimeout(function () {
                $('#cartModal').modal('hide');
            }, 5000);
        },

    addLineItem: function (cb) {
        var store_url = window.location.pathname.split('/', 2)[1];

        $.ajax({
            url: '/' + store_url + '/cart/lineitems',
            type: 'GET',
            success: function (response) {
                cb(response);
            }
        });
    },


    getLineItemsWithOrderSummary: function (cb) {
        var store_url = window.location.pathname.split('/', 2)[1];

        $.ajax({
            url: '/' + store_url + '/cart/line-items-with-order-summary',
            type: 'GET',
            success: function (response) {
                cb(response);
            }
        });
    }
    ,

    addChild: function (elem) {
        var child_id = elem.data('id');

        $('.btn-pivot').html(elem.html() + '<span class="caret"></span>');

        if ($('#box-spring-' + child_id).length > 0) {
            $('.box-spring').addClass('hidden');
            $('#box-spring-' + child_id).removeClass('hidden');
        }

        $('#price').html('<span class="product-price"></span>');
        $('.product-price').html(elem.data('price').toFixed(2));

        $('.cart-add').data('pid', child_id);
        $('.cart-add').removeAttr('disabled');
        //boxspring.price('set');

        return false;
    }
    ,

    hideCart: function (hide) {
        if (hide) {
            $('.cart-summary').hide();
            $('.cart-overview').hide();
        } else {
            $('.cart-summary').show();
            $('.cart-overview').show();
        }
    }
    ,

    clear: function (elem) {
        var $this = this;

        var form = $(elem).closest('form');
        if (form.find('.has-error').length) {
            return false;
        }

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: {
                clear: true,
                action: 'clear'
            },

            success: function (response) {
                $('.cart-count').html(response);

                $this.addLineItem(function (response) {
                    $('#cart_line_items').html(response);
                    $('#cart_modal_line_items').html(response);
                });

                cart.process($("#promo"), null, 'refresh', null, null);

                cart.badge(response.total);
            }
        });

        return false;
    }
};

checkout = {
    init: function () {
        if ($("#form-checkout")) {
            this.events();
        }
    },

    events: function () {
        $(document).on('change', 'select[name="fill_address"]', function () {
            checkout.fillShippingBillingAjax($(this));
        });

        $(document).on('submit', "#form-checkout", function (e) {
            e.preventDefault();
            $(".checkout-buttons button[type='submit']").prop('disabled', true);
            this.submit();
        });
    },

    fillShippingBillingAjax: function ($this) {
        type = $this.data("type");
        $.ajax({
            url: "checkout/fill-shipping-billing-ajax",
            type: 'POST',
            data: {
                addressId: $this.val()
            },
            success: function (response) {
                response = JSON.parse(response);
                $("#checkoutform-" + type + "_first_name").val(response.firstname);
                $("#checkoutform-" + type + "_last_name").val(response.lastname);
                $("#checkoutform-" + type + "_street_address").val(response.address_1);
                $("#checkoutform-" + type + "_apartment_suite").val(response.address_2);
                $("#checkoutform-" + type + "_city").val(response.city);
                $("#checkoutform-" + type + "_subregion_id").val(response.region_id);
                $("#checkoutform-" + type + "_zipcode").val(response.postcode);
                $("#checkoutform-" + type + "_phone").val(response.phone);
            }
        });
    }
};