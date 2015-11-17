$("#custom-accordion-show").click(function() {
    $("#custom-accordion").toggle();
    $("#rotate-arrow").find("i").toggleClass("fa-caret-right");
    $(".site-info").toggle();
    $(".search-button").toggle();
});

$(".filter-divider-top").click(function() {
    $(".filter-body").toggle();
    $(".filter-divider-down").toggle();
});

$(".filter-divider-down").click(function() {
    $(".filter-body").toggle();
    $(".filter-divider-down").hide();

});

$(".heart-fav ").click(function(event) {
    event.preventDefault();
    $(this).toggleClass("toggle-heart-red");
});




var icheck = $('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    // increaseArea: '20%' // optional
});

var datePicker = $('.datepicker').datetimepicker({
    timepicker: false,
    format: "d/m/Y",
    minDate: 0,
    scrollMonth: false,
    scrollTime: false,
    scrollInput: false
});
var timePicker = $('.timepicker').datetimepicker({
    datepicker: false,
    format: 'H:i',
    scrollMonth: false,
    scrollTime: false,
    scrollInput: false
});

var priceMin = $(".priceMin").ionRangeSlider({
    min: 0,
    max: 200

});

var priceMax = $(".priceMax").ionRangeSlider({
    min: 0,
    max: 200

});




var timeZone = $(".timeRange").ionRangeSlider({
    values: [
        "00:00", "01:00", "02:00",
        "03:00", "04:00", "05:00",
        "06:00", "07:00", "08:00",
        "09:00", "10:00", "11:00",
        "12:00", "13:00", "14:00",
        "15:00", "16:00", "17:00",
        "18:00", "19:00", "20:00",
        "21:00", "22:00", "23:00"
    ]
});




// Can also be used with $(document).ready()
$(window).load(function() {
    // The slider being synced must be initialized first
    $('#carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 90,
        itemMargin: 10,
        asNavFor: '#slider',
        directionNav: false
    });

    $('#slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: true,
        slideshow: false,
        sync: "#carousel",
        slideshowSpeed: 7000,
        animationSpeed: 600
    });
    $('.flexslider-unit').flexslider({
        animation: "slide",
        animationLoop: true,
        itemWidth: 395,
        minItems: 3,
        maxItems: 3,
        itemMargin: 5,
        directionNav: false,
        controlNav: false



    });
});


// $('#map-canvas')
// .css({
//   visibility: 'hidden',
//   height: 0
// });

// var hidemapOnLoad = $('#map-canvas')
// .css({
//   visibility: 'hidden',
//   height: 0
// });



// var showmapButton = $(".showmap-button").click(function() {
//      $('#map-canvas')
// .css({
//   visibility: 'visible',
//   height: '500px'
// });
// });
//
