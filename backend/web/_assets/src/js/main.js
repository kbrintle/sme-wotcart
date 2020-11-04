/*
    This is the main JS for misc events, etc
 */
$(() => {
  global.init();
  attributes.init();
  catalog.init();
  banner.init();
  sortable.init();
  benefits.init();
  settings.init();
  rulebuilder.init();
  brands.init();
  imagelibrary.init();
  customer.init();
  product.init();
});

var global = {
  init() {
    this.events();
  },
  events() {
    $('body').on('change', '#store-selector', function() {
      store.setContext($(this).val());
    });
    $(window).on('beforeunload', () => {
      $(window).scrollTop(0);
    });

    // store the currently selected tab in the hash value
    $('ul.nav-tabs > li > a').on('shown.bs.tab', (e) => {
      const id = `#${$(e.target).attr('href').substr(1)}`;
      history.replaceState(undefined, undefined, id);
      $('#current-tab').val(id);
    });

    // on load of the page: switch to the currently selected tab
    if (location.hash !== '') {
      $(`a[href="${location.hash}"]`).tab('show');
    }
    return $('a[data-toggle="tab"]').on('shown', e => location.hash = $(e.target).attr('href').substr(1));


    webshim.setOptions('forms-ext', {
      types: 'date',
      date: {
        openOnFocus: true,
        yearSelect: false
      }
    });
    webshim.polyfill('forms-ext');
  }
};


var benefits = {
  init() {
    benefits.update();
    this.events();
  },
  events() {
    $('#textInput').keyup(() => {
      benefits.update();
    });
  },
  update() {
    const $benefits = $('.benefits-update').length;
    if ($benefits) {
      const remaining = 225 - $('#textInput').val().length;
      $('.countdown').text(`${remaining} characters remaining.`);
    }
  }
};

var settings = {
  init() {
    this.events();
    this.checkShipping();
    this.checkEcom();
  },
  events() {
    $('body').on('change', '#settingsstore-is_cart', function(e) {
      if ($(this).val() == 0) {
        $('.settings-pricing').removeClass('hidden');
      }
      else {
        $('.settings-pricing').addClass('hidden');
        $('#settingsstore-has_pricing').val(1);
      }
    });
    $('body').on('change', '#settingsshipping-free_shipping', (e) => {
      settings.checkShipping();
    });
  },
  checkShipping() {
    if ($('#settingsshipping-free_shipping').val() == 1) {
      $('.settings-free-ship-min').removeClass('hidden');
    }
    else {
      $('.settings-free-ship-min').addClass('hidden');
      $('#settingsshipping-free_shipping_min').val('');
    }

    $('#SettingsShipping').on('click', '#add-shipping-rate', function() {
      let index = parseInt($(this).data('index'));
      let html = '';
      html += '<div id="rate-group-" class="">';
      html += '<span class="col-md-5">';
      html += `if Total >= <input type="number" min="0" step="1" class="form-control" name="ShippingRates[${index}][price]" value="">`;
      html += '</span><span class="col-md-5">';
      html += `Shipping = <input type="number" min="0.00" step="0.01" class="form-control" name="ShippingRates[${index}][cost]" value=""></span></br>`;
      html += `<span class="input-group-btn"><input type="button" data-index= ${index}  class="remove-shipping-rate btn btn-default" value="delete"></span></div>`;
      $('#shipping-rate-select').append(html);
      index++;
      $(this).data('index', index);
    });

    $('#shipping').on('click', '.remove-shipping-rate', function() {
      $(this).parent().parent().remove();
    });
  },
  checkEcom() {
    if ($('#settingsstore-is_cart').val() == 0) {
      $('.settings-pricing').removeClass('hidden');
    }
  }
};

var rulebuilder = {
  init() {
    this.events();
  },
  events() {
    $('body').on('change', '.rule-key', function() {
      const key = $(this).val();
      switch (key) {
        case 'brand':
          $('.value-text').hide().prop('disabled', true);
          $('.value-brand').show().prop('disabled', false);
          $('.value-size').hide().prop('disabled', true);
          $('.value-set').hide().prop('disabled', true);
          break;
        case 'mattress-size':
          $('.value-text').hide().prop('disabled', true);
          $('.value-brand').hide().prop('disabled', true);
          $('.value-size').show().prop('disabled', false);
          $('.value-set').hide().prop('disabled', true);
          break;
        case 'attribute-set':
          $('.value-text').hide().prop('disabled', true);
          $('.value-brand').hide().prop('disabled', true);
          $('.value-size').hide().prop('disabled', true);
          $('.value-set').show().prop('disabled', false);
          break;

        default:
          $('.value-text').show().prop('disabled', false);
          $('.value-brand').hide().prop('disabled', true);
          $('.value-size').hide().prop('disabled', true);
          $('.value-set').hide().prop('disabled', true);
          break;
      }
    });
  }
};

var brands = {
  init() {
    this.events();
    this.toggleLogoUpload('initial');
  },
  events() {
    $('body').on('click', '.carry-brand', function() {
      const $this = $(this);

      $.ajax({
        url: '/admin/brand/carry',
        type: 'POST',
        data: {
          bid: $this.data('id'),
          action: $this.data('action')
        },

        success() {
          switch ($this.data('action')) {
            case 'carry':
              $this.removeClass('btn-primary');
              $this.addClass('btn-secondary');
              $this.data('action', 'remove');
              $this.html('Remove');
              break;
            case 'remove':
              $this.removeClass('btn-secondary');
              $this.addClass('btn-primary');
              $this.data('action', 'add');
              $this.html('Add');
              break;
          }
        }
      });
    });
    $('body').on('change', '#catalogbrand-mattress_line', function() {
      brands.toggleLogoUpload($(this).val());
    });
  },
  toggleLogoUpload(value) {
    if (value == 'initial') {
      value = $('#catalogbrand-mattress_line').val();
    }

    switch (value) {
      case '1':
        $('.field-catalogbrand-logo_grey').prop('disabled', false);
        $('.field-catalogbrand-logo_color').prop('disabled', false);
        $('.brand-logos').show();
        break;
      case '0':
        $('.field-catalogbrand-logo_grey').prop('disabled', true);
        $('.field-catalogbrand-logo_color').prop('disabled', true);
        $('.brand-logos').hide();
        break;
    }
  }
};

var store = {
  setContext(store_id) {
    $.ajax({
      type: 'post',
      url: '/admin/store/assign',

      data: {
        store_id
      },
      success(response) {
        $('#store-selector').val(response);
      },
      fail() {
        alert('Store could not be changed! Please try again, or contact support.');
      }
    });
  }
};

var attributes = {
  init() {
    this.events();
  },
  events() {
    $('body').on('change', '#attributeform-type_id', function() {
      const value = $(this).val();
      if (value == 2 || value == 10) {
        $('#select-attribute').removeClass('hidden');
      }
      else {
        $('#select-attribute').addClass('hidden');
      }
    });
  }
};

var catalog = {
  init() {
    this.events();
  },

  submitCategoriesForm(saveButton) {
    const save = $('#save');
    save.hide();
    const form = $('#w0')[0];
    const formData = new FormData(form);
    $.ajax({
      url: saveButton.attr('href'), // Server script to process data
      type: 'POST',
      data: formData,
      success(response) {
        $('#updatePanel').html(response);
        const category = $('#catalogcategory-description');
        if (category.val().length > 150) {
          category.height(`${category.val().length / 2.3}px`);
        }
        const catUpdate = $('#catUpdate');
        catUpdate.html('Success').css('color', '#3c763d').css('background-color', '#dff0d8').css('border-color', '#d0e9c6');
        setTimeout(() => {
          dataTableInit(); // this will reinitialize datatables on the products grid
          catUpdate.html('Update').css('color', '').css('background-color', '').css('border-color', '');
        }, 3000);
        save.show();
        setTimeout(() => {
          save.fadeOut(2000);
        }, 5000);
      },
      error() {
      },
      cache: false,
      contentType: false,
      processData: false
    });
  },


  tier_prices() {
    $('#prices').on('click', '#add-tier-price', function() {
      let index = parseInt($(this).data('index'));
      let html = '';
      html += '<div id="option-group-" class="">';
      html += '<span class="col-md-5">';
      html += `if Quantity >= <input type="number" min="0" step="1" class="form-control" name="AttributeForm[tier-pricing][${index}][qty]" value="">`;
      html += '</span><span class="col-md-5">';
      html += `Value = <input type="number" min="0.00" step="0.01" class="form-control" name="AttributeForm[tier-pricing][${index}][value]" value=""></span></br>`;
      html += `<span class="input-group-btn"><input type="button" data-index= ${index}  class="remove-tier-price btn btn-default" value="delete"></span></div>`;
      $('#tier-price-select').append(html);
      index++;
      $(this).data('index', index);
    });

    $('#prices').on('click', '.remove-tier-price', function() {
      $(this).parent().parent().remove();
    });
  },

  options() {
    $('#options').on('click', '#add-option', function() {
      const index = $(this).data('index');
      $('#select-options #sortable').append(`<div class="input-group" id="option-group-${index}"><span class="input-group-btn sortable_handler"><i class="material-icons">drag_handle</i></span><input type="text" class="form-control" name="AttributeForm[options][]" placeholder="Option text..."><span class="input-group-btn"><button type="button" id="remove-option" data-index="${index}" class="btn btn-default"><i class="material-icons">delete</i></button></span></div>`);
      $(this).data('index', index + 1);
    });

    $('#options').on('click', '#add-product-custom-option', function() {
      let index = parseInt($(this).attr('data-index'));
      let html = '';
      html += `<div id="option-group-${index}" class="panel panel-default"><div class="panel-body">`;
      html += '<div class="input-group"><span class="input-group-btn sortable_handler"><i class="material-icons">drag_handle</i></span>';
      html += `<span class = "col-md-2">Title:<input type="text" class="form-control" name="AttributeForm[options][${index}][title]" placeholder="Title"></span>`;
      html += `<span class = "col-md-2">Input Type:<select class="form-control" name="AttributeForm[options][${index}][type]" placeholder="Input Type">`
          + '</optgroup><optgroup label="Select">'
          + '<option value="dropdown">Drop-down</option>'
          + '<option value="radio">Radio Buttons</option>'
          + '<option value="checkbox">Checkbox</option>'
          // "<option value=\"multiple\">Multiple Select</option>" +
          + '</optgroup></select></span>';
      html += `<span class = "col-md-2">Is Required:<select class="form-control" name="AttributeForm[options][${index}][is_required]" placeholder="Is Required">`
          + '<option value="0">No</option>'
          + '<option value="1">Yes</option>'
          + '</select></span>';
      html += `<span class = "col-md-2"><input type="button" id="add-product-row" data-option-index=${index} data-row-index=0 class="btn btn-primary margin-top-20" value="Add Row"></span>`;
      html += `<span class="input-group-btn"><input type="button" id="remove-option" data-index=${index}  class="btn btn-default" value = "delete"></span>`;
      html += `</div><div class="panel-body"><div id="options-rows-${index}"><div id="sortable" class="ui-sortable row"></div></div></div>`;
      html += '</div></div>';
      $('#select-options .top').append(html);
      index++;
      $(this).attr('data-index', index);
    });

    $('#options').on('click', '#add-product-row', function() {
      const optionIndex = parseInt($(this).attr('data-option-index'));
      let rowIndex = parseInt($(this).attr('data-row-index'));
      $(`#options-rows-${optionIndex} #sortable`).append(`<div class="input-group" id="option-row-${rowIndex}"><span class="input-group-btn sortable_handler"><i class="material-icons">drag_handle</i></span>`
          + `<span class = "col-md-3"><input type="text" class="form-control" name="AttributeForm[options][${optionIndex}][values][${rowIndex}][title]" placeholder="Option Title"></span>`
          + `<span class = "col-md-3"><input type="text" class="form-control" name="AttributeForm[options][${optionIndex}][values][${rowIndex}][price]" placeholder="Option Price"></span>`
          + `<span class = "col-md-3"><input type="text" class="form-control" name="AttributeForm[options][${optionIndex}][values][${rowIndex}][sku]" placeholder="Option Sku"></span>`
          + `<span class="input-group-btn"><button type="button" id="remove-option-row" data-index=${rowIndex} class="btn btn-default"><i class="material-icons">delete</i></button></span></div>`);
      rowIndex++;
      $(this).attr('data-row-index', rowIndex);
    });

    $('#options').on('click', '#remove-option-row', function() {
      $(this).parent().parent().remove();
    });

    $('#options').on('click', '#remove-option', function() {
      $(this).parent().parent().parent()
          .parent()
          .remove();
    });
  },

  stores() {
    $('#Stores').on('click', 'input[name*="stores[0]"]', function() {
      if ($(this).is(':checked')) {
        $('#Stores input').each(function() {
          $(this).prop('checked', true);
        });
      }
      else {
        $('#Stores input').each(function() {
          $(this).prop('checked', false);
        });
      }
    });
  },

  productRelationAjax(checkBox) {
    const save = $('#save');
    save.hide();
    let isChecked = false;
    if (checkBox.prop('checked') == true) {
      isChecked = true;
    }
    $.ajax({
      url: checkBox.attr('href'),
      type: 'POST',
      data: {
        isChecked,
        id1: checkBox.attr('id1'),
        id2: checkBox.attr('id2'),
        type: checkBox.attr('relationtype')
      },
      success() {
        const row = checkBox.parents().eq(1);
        row.addClass('success');
        save.show();
        setTimeout(() => {
          save.fadeOut(2000);
        }, 5000);
        setTimeout(() => {
          row.removeClass('success');
        }, 1000);
      }
    });
  },

  associatedProducts() {
    $('#grouped-table').on('click', '.kv-row-checkbox', function() {
      catalog.productRelationAjax($(this));
    });
  },


  relatedProducts() {
    $('#related-table').on('click', '.kv-row-checkbox', function() {
      catalog.productRelationAjax($(this));
    });
  },

  attachments() {
    $('#attachment-table').on('click', '.kv-row-checkbox', function() {
      const save = $('#save');
      save.hide();
      const that = $(this);
      let isChecked = false;
      if (that.prop('checked') == true) {
        isChecked = true;
      }
      $.ajax({
        url: that.attr('href'),
        type: 'POST',
        data: {
          isChecked,
          product_id: that.data('pid'),
          attachment_id: that.attr('attachment_id')
        },
        success() {
          save.show();
          setTimeout(() => {
            save.fadeOut(2000);
          }, 5000);
          const row = that.parents().eq(1);
          row.addClass('success');
          setTimeout(() => {
            row.removeClass('success');
          }, 1000);
        }
      });
    });
  },

  productImages() {
    $('#done').on('click', () => {
      $.pjax.reload({ container: '#gridview-pjax' });
    });

    $('#setDefaultBtn').on('click', function() {
      const Btn = $(this);
      $('.kv-grid-table input:checkbox').each(function() {
        if (this.checked) {
          const that = $(this);
          $.ajax({
            url: Btn.attr('href'),
            type: 'POST',
            data: {
              id: that.attr('id'),
              product_id: that.data('pid')
            },

            success(response) {
              $.pjax.reload({ container: '#gridview-pjax' });
            }
          });
        }
      });
    });

    $('#deleteBtn').on('click', function() {
      const btn = $(this);
      $('.kv-grid-table input:checkbox').each(function() {
        if (this.checked) {
          const that = $(this);
          $.ajax({
            url: `${btn.attr('href')}?name=${that.attr('value')}`,
            type: 'POST',
            data: {
              id: that.attr('id'),
              product_id: that.data('pid')
            },
            success(response) { // TODO CPM should make a multi delete
              $.pjax.reload({ container: '#gridview-pjax' });
            }
          });
        }
      });
    });

    $('#images .kv-row-checkbox').on('click', () => {
      const checkedBoxs = $('#images .kv-grid-table input:checked').length;
      const deleteBtn = $('#deleteBtn');
      const setDefaultBtn = $('#setDefaultBtn');
      if (checkedBoxs > 0) {
        if (deleteBtn.hasClass('hidden')) {
          deleteBtn.removeClass('hidden');
        }
        if (checkedBoxs === 1) {
          if (setDefaultBtn.hasClass('hidden')) {
            setDefaultBtn.removeClass('hidden');
          }
        }
        if (checkedBoxs > 1) {
          if (!setDefaultBtn.hasClass('hidden')) {
            setDefaultBtn.addClass('hidden');
          }
        }
      }
      else {
        if (!deleteBtn.hasClass('hidden')) {
          deleteBtn.addClass('hidden');
        }
        if (!setDefaultBtn.hasClass('hidden')) {
          setDefaultBtn.addClass('hidden');
        }
      }
    });
  },

  toggleActive() { // this function allows is_active to be toggled from the attachments grid view
    const icon = $(this);
    let is_active = 1;
    if ($(this).hasClass('text-success')) {
      is_active = 0;
    }
    const post = {};
    post.hasEditable = 1;
    post.editableIndex = 0;
    post.editableKey = parseInt($(this).parent().parent().attr('data-key'));
    post.editableAttribute = 'is_active';
    post.CatalogAttachment = { 0: { is_active } };
    $.ajax({
      url: document.URL,
      type: 'post',
      data: post,
      success(response) {
        response = JSON.parse(response);
        if (response.message === '') {
          if (is_active === 1) {
            icon.addClass('glyphicon-ok text-success');
            icon.removeClass('glyphicon-remove text-danger');
          }
          else {
            icon.removeClass('glyphicon-ok text-success');
            icon.addClass('glyphicon-remove text-danger');
          }
        }
      }
    });
  },

  deleteProductRow() {
    $('.delete-product-row').on('click', (e) => {
      const $row = $(e.currentTarget).closest('tr');
      $row.css('background', 'red');
      $row.find('input.text-center').val(0);
      $('form#w0').submit();
    });
  },

  addToOrder() {
    $(document).on('click', '#add-to-order', (e) => {
      e.preventDefault();
      const $modal = $('#add-product-modal');
      if ($modal.hasClass('in')) {

      }
      else {
        $modal.modal('show');
        $.ajax({
          url: '/admin/product/json',
          method: 'GET',
          data: {
            _csrf: '<?= Yii::$app->request->getCsrfToken()?>'
          },
          success(response) {
            console.log(response);
          },
          error(exception) {
            console.log(exception);
          }
        });
      }
    });
  },

  events() {
    if ($('#options').length !== 0) {
      catalog.options();
    }

    if ($('#prices').length !== 0) {
      catalog.tier_prices();
    }

    if ($('#Stores').length !== 0) {
      catalog.stores();
    }

    if ($('#related-products').length !== 0) {
      catalog.relatedProducts();

      $('#related-products').on('pjax:success', () => {
        catalog.relatedProducts();
      });
    }

    if ($('#images').length !== 0) {
      catalog.productImages();

      $('#images').on('pjax:success', () => {
        catalog.productImages();
      });
    }

    if ($('#associated-products').length !== 0) {
      catalog.associatedProducts();

      $('#associated-products').on('pjax:success', () => {
        catalog.associatedProducts();
      });
    }

    if ($('#product-attachments').length !== 0) {
      catalog.attachments();
    }

    if ($('.delete-product-row').length) {
      catalog.deleteProductRow();
    }

    if ($('#add-to-order').length) {
      catalog.addToOrder();
    }


    $('body').on('change', '.list-product', function() {
      if ($(this).is(':checked')) {
        var does = '?action=add';
      }
      else {
        var does = '?action=remove';
      }

      $.ajax({
        url: $(this).data('url') + does,
        type: 'POST',
        data: { pid: $(this).data('id') },

        success(response) {
        }
      });
    });
    if ($('.catalog-category-index').length !== 0) {
      $(document.body).on('click', '#catUpdate', function() {
        catalog.submitCategoriesForm($(this));
      });
    }
  }
};


/**
 * #sortable -> grid-item
 * @type {{init: sortable.init, updateOrder: sortable.updateOrder, callbacks: {attribute_sets: sortable.callbacks.attribute_sets}}}
 */
var sortable = {
  init() {
    const $this = this;
    $this.updateOrder();

    $('#sortable').sortable({
      stop(event, ui) {
        $this.updateOrder(event, ui, (data) => {
          const callback_identifier = $('#sortable').data('callback');
          if ($this.callbacks[callback_identifier]) {
            $this.callbacks[callback_identifier](data);
          }
        });
      }
    });
    $('#sortable').disableSelection();
  },
  updateOrder(event, ui, cb) {
    const data = [];
    $('#sortable input[name*="_order"]').each(function(i, e) {
      $(this).val((i + 1));

      const value = $(this).data('value');
      data.push({
        order: (i + 1),
        value
      });
    });

    if (cb && $.isFunction(cb)) {
      cb(data);
    }
  },
  callbacks: {

    attribute_sets(data) {
      $.ajax({
        url: '/admin/attributeset/order',
        method: 'POST',
        data: {
          order: data
        }
      })
          .done((response) => {
          })
          .fail((jqXHR, textStatus) => {
          });
    }

  }
};

var imagelibrary = {
  init() {
    $order = 0;
    this.events();
  },
  events() {
    $('body').on('click', '.image-library-view', function() {
      $order = $(this).data('order');
      $('.image-library-title').html(`Select an Image for Position ${$order}`);
    });
    $('body').on('click', '.image-library-use', function() {
      $.ajax({
        url: '/admin/promotions/use-image',
        method: 'POST',
        data: {
          id: $(this).data('id'),
          order: $order
        },
        success() {
          return true;
        }
      });
    });
    $('body').on('click', '.delete-promo-image', function(e) {
      imagelibrary.delete(e, false, 0, $(this).attr('href'));
    });
    $('body').on('focusout', '.link-test-input', function() {
      $('.link-test').attr('href', $(this).val());
    });
  },
  delete(e, run, loop, href) {
    switch (run) {
      case true:
        window.location.href = href;
        break;
      case false:
        e.preventDefault();
        if (!loop) {
          imagelibrary.delete(e, confirm('Are you sure you want to delete this image?'), loop + 1, href);
        }
        return false;
        break;
    }
  }
};


var banner = {
  init() {
    this.events();
  },

  events() {
    if ($('#bannerIndex').length !== 0) {
      $(document).on('click', '.modal-click', function() {
        $(`#${$(this).attr('modal')}`).modal('show');
      });

      $(document).on('click', '.banner-action', function() {
        banner.submitBannerForm($(this));
      });
    }
  },

  submitBannerForm(saveButton) {
    const save = $('#save');
    save.hide();
    const pageLocation = saveButton.attr('page-location');
    const form = $(`#${pageLocation}${saveButton.attr('banner-id')}`)[0];
    const formData = new FormData(form);
    let isCategoryDetail = false;
    if (pageLocation === 'category' || pageLocation === 'detail') {
      isCategoryDetail = true;
    }
    $.ajax({
      url: saveButton.attr('href'),
      type: 'POST',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success(response) {
        response = JSON.parse(response);
        const container = $(`#${pageLocation}-container${saveButton.attr('banner-id')}`)[0];
        if (pageLocation === 'category' || pageLocation === 'detail') {
          isCategoryDetail = true;
        }
        if (response.action === 'deleted') {
          if (isCategoryDetail) {
            container.remove();
          }
          else {
            saveButton.addClass('hidden');
          }
        }
        else {
          if (saveButton.attr('banner-id') === 'new') {
            $(container).before(response.banner);
            if (isCategoryDetail) {
              $(`#${pageLocation}-modal`).before(response.modal);
              $(`#${pageLocation}-modal`).replaceWith(response.newModal);
            }
          }
          else if (isCategoryDetail) {
            $(container).replaceWith(response.banner);
          }
          else {
            $(container).html(response.banner);
          }
          $('#storebanner-id').val(response.id);
          saveButton.parent().find('.btn-danger').removeClass('hidden');
        }
        save.show();
        setTimeout(() => {
          save.fadeOut(2000);
        }, 5000);
      }
    });
  }
};

var customer = {

  init() {
    this.events();
  },

  events() {
    const ajax_url = '/admin/customer/ajax-address';
    if ($('#addressTab').length !== 0) {
      $('#left-address-column').click((e) => {
        const target = $(e.target);
        if (target.is('.address-select')) {
          const flashcontainer = $('#background-flash');
          flashcontainer.removeClass('flash');
          $.ajax({
            url: ajax_url,
            type: 'post',
            data: { action: 'loadform', id: target.attr('address-id') },
            success(form) {
              if ($('#address-form').attr('address-id') !== target.attr('address-id')) {
                $('#address-form-container').html(form);
                $('.selected').removeClass('selected');
                target.addClass('selected');
                flashcontainer.addClass('flash');
                flashcontainer.parent().show();
              }
            }
          });
        }
        else if (target.is('.default-billing')) {
          if (target.is(':checked')) {
            $.ajax({
              url: ajax_url,
              type: 'post',
              data: {
                action: 'defaultBilling',
                id: target.val(),
                userId: $('#addresses').attr('user-id')
              },
              success() {
                $('.default-billing').each(function() {
                  if (target.val() !== $(this).val()) {
                    this.checked = false;
                  }
                });
              }
            });
          }
        }
        else if (target.is('.default-shipping')) {
          if (target.is(':checked')) {
            $.ajax({
              url: ajax_url,
              type: 'post',
              data: {
                action: 'defaultShipping',
                id: target.val(),
                userId: $('#addresses').attr('user-id')
              },
              success() {
                $('.default-shipping').each(function() {
                  if (target.val() !== $(this).val()) {
                    this.checked = false;
                  }
                });
              }
            });
          }
        }
        else if (target.is('.address-delete')) {
          $('#model-delete-address').attr('address-id', target.attr('address-id'));
          $('#delete-address').modal('show');
        }
      });

      $('#address-save').on('click', function() {
        const $this = $(this);
        const save = $('#save');
        save.hide();
        const formId = $('#address-form').attr('address-id');
        let selector = $(`.address-select[address-id='${formId}']`);
        selector.removeClass('flash');
        const form = $('#address-form')[0];
        const formData = new FormData(form);
        $this.removeClass('flash');
        $.ajax({
          url: ajax_url,
          type: 'post',
          data: formData,
          success(response) {
            selector.parent().parent().html(response);
            selector = $(`.address-select[address-id='${formId}']`);
            selector.addClass('flash');
            selector.addClass('selected');
            save.show();
            setTimeout(() => {
              save.fadeOut(2000);
            }, 5000);
          },
          error() {
          },
          cache: false,
          contentType: false,
          processData: false
        });
      });

      $('#address-create').on('click', () => {
        $.ajax({
          url: ajax_url,
          type: 'post',
          data: { action: 'create', id: $('#addresses').attr('user-id') },
          success(response) {
            response = JSON.parse(response);
            const flashcontainer = $('#background-flash');
            $('#left-address-column').append(`<div>${response.info}</div>`);
            $('#address-form-container').html(response.form);
            const formId = $('#address-form').attr('address-id');
            const selector = $(`.address-select[address-id='${formId}']`);
            $('.selected').removeClass('selected');
            selector.addClass('selected');
            flashcontainer.parent().show();
          }
        });
      });

      $('#model-delete-address').on('click', function() {
        const $this = $(this);
        const id = $this.attr('address-id');
        $.ajax({
          url: ajax_url,
          type: 'post',
          data: { action: 'delete', id },
          success(response) {
            $(`.address-delete[address-id='${id}']`).parent().remove();
            if ($('#address-form').attr('address-id') == id) {
              const container = $('#address-form-container');
              container.html('');
              $('#background-flash').parent().hide();
            }
          }
        });
      });

      $('body').on('click', '#reward_points_save', () => {
        const form = $('form#reward-points-form');
        $.ajax({
          url: form.attr('action'),
          type: 'post',
          data: form.serialize(),
          success(response) {
            if (response) {
              $('#usable-points').text(response);
              $.pjax.reload({ container: '#reward-points-pjax' });
              $('#reward-points-modal').modal('hide');
            }
          }
        });
      });
    }
  }
};

var product = {
  init() {
    this.events();
  },

  events() {
    if ($('#productIndex').length !== 0) {
      $('#productIndex').on('click', '.kv-editable-value', function(e) {
        let value = $(this).html();
        if (value == '<em>(not set)</em>') {
          value = '';
        }
        const container = $(this).data('target');
        $(`${container} .kv-editable-input`).val(value);
      });
    }

    if ($('#delete-product-ajax').length !== 0) {
      $(document).on('click', '#delete-product-ajax', (e) => {
        const deleteButton = $('#delete-product-ajax');
        $('#delete-product-modal').modal('hide');
        $('#product_panel').html('<i class="fas fa-spinner-third fa-3x fast-spin"></i>');
        $.ajax({
          url: deleteButton.attr('action'),
          type: deleteButton.attr('method'),
          data: { id: deleteButton.data('id') },
          success(response) {
            console.log('success');
          }
        });
      });
    }
  }
};
