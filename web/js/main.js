$('.set-size-type-subcategory').on('click', function (e) {
    e.preventDefault();
    var id_subcategory = $(this).data('id_subcategory');
    var size_type = $(this).data('size_type');

    $.ajax({
        url: '/subcategories/set-size-type',
        data: {id_subcategory: id_subcategory, size_type: size_type},
        type: 'get',
        success: function (res) {

        },

        error: function () {
            alert('error')
        }
    });
    this.removeClass('btn-default');
    this.addClass('btn-primary');
});

$('.set-size-type-category').on('click', function (e) {
    e.preventDefault();
    var id_category = $(this).data('id_category');
    var size_type = $(this).data('size_type');

    $.ajax({
        url: '/categories/set-size-type',
        data: {id_category: id_category, size_type: size_type},
        type: 'get',
        success: function (res) {

        },

        error: function () {
            alert('error')
        }
    });
    this.removeClass('btn-default');
    this.addClass('btn-primary');
});

$('.set-attr-value').on('click', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var attr = $(this).data('attr');
    var value = $(this).data('value');

    $.ajax({
        url: '/products/set',
        data: {id: id, attr: attr, value: value},
        type: 'get',
        success: function (res) {

        },

        error: function () {
            alert('error')
        }
    });
    this.disabled;

})
;
$('.set-attr-value-category').on('click', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var attr = $(this).data('attr');
    var value = $(this).data('value');

    $.ajax({
        url: '/categories/set-attr',
        data: {id: id, attr: attr, value: value},
        type: 'get',
        success: function (res) {

        },

        error: function () {
            alert('error')
        }
    });
    this.disabled;

})

;$('.set-attr-value-subcategory').on('click', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var attr = $(this).data('attr');
    var value = $(this).data('value');

    $.ajax({
        url: '/subcategories/set-attr',
        data: {id: id, attr: attr, value: value},
        type: 'get',
        success: function (res) {

        },

        error: function () {
            alert('error')
        }
    });
    this.disabled;

})
;

$('.set-attr-value-mainsubcategory').on('click', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var attr = $(this).data('attr');
    var value = $(this).data('value');

    $.ajax({
        url: '/main-subcategories/set-attr',
        data: {id: id, attr: attr, value: value},
        type: 'get',
        success: function (res) {

        },

        error: function () {
            alert('error')
        }
    });
    this.disabled;

});
$('.set-attr-value-maincategory').on('click', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var attr = $(this).data('attr');
    var value = $(this).data('value');

    $.ajax({
        url: '/main-categories/set-attr',
        data: {id: id, attr: attr, value: value},
        type: 'get',
        success: function (res) {

        },

        error: function () {
            alert('error')
        }
    });
    this.disabled;

});

$('.set-many').on('click', function (e) {
    e.preventDefault();
    var attr = $(this).data('attr');
    var value = $(this).data('value');

    $.ajax({
        url: '/products/set-many',
        data: {attr: attr, value: value},
        type: 'get',
        success: function (res) {
            alert(res);
        },

        error: function () {
            alert('error')
        }
    });
    this.disabled;

})
;
$('.set-model-attr-value').on('click', function (e) {
    e.preventDefault();
    var model_name = $(this).data('model_name');
    var id = $(this).data('id');
    var attr = $(this).data('attr');
    var value = $(this).data('value');

    $.ajax({
        url: '/actions/set',
        data: {model: model, id: id, attr: attr, value: value},
        type: 'get',
        success: function (res) {
            $().alert(res)
        },

        error: function () {
            alert('error')
        }
    });
    this.disabled;

})
;
$('.pick-color').on('click', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var id_color = $(this).data('id_color');

    $.ajax({
        url: '/products/set-color',
        data: {id_color: id_color, id: id},
        type: 'get',
        success: function (res) {

        },

        error: function () {
            alert('error')
        }
    });
    this.disabled;

})
;
