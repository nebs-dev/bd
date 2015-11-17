$(function () {

    $('.form_location_ajax').on('submit', function() {
        $('.regionsSelect').attr('disabled', false);
        $('.subregionsSelect').attr('disabled', false);
        $('.citiesSelect').attr('disabled', false);
    });

    $('#accommodationEdit').on('submit', function() {
        $('.countriesSelect').attr('disabled', true);
        $('.regionsSelect').attr('disabled', true);
        $('.subregionsSelect').attr('disabled', true);
        $('.citiesSelect').attr('disabled', false);
    });

    $('.countrySelect').on('click', function (e) {
        if ($(this).val() == '') {
            $('.regionsSelect').prop("disabled", true);
            $('.subregionsSelect').prop("disabled", true);
            $('.citiesSelect').prop("disabled", true);
        } else {
            var typeClass = ($(this).attr('class').split(' ')[1]);
            type = typeClass.substring(1);

            $('.regionsSelect').prop("disabled", false);
            $('.subregionsSelect').prop("disabled", true);
            $('.citiesSelect').prop("disabled", true);
            getRegions($(this).val(), type, typeClass);
        }
    });

    //$('#countryCity').on('change', function (e) {
    //    if ($(this).val() == '') {
    //        $('.regionsSelect').prop("disabled", true);
    //        $('.subregionsSelect').prop("disabled", true);
    //        $('.citiesSelect').prop("disabled", true);
    //    } else {
    //        $('.regionsSelect').prop("disabled", false);
    //        $('.subregionsSelect').prop("disabled", true);
    //        $('.citiesSelect').prop("disabled", true);
    //        getRegionsCity($(this).val());
    //    }
    //});

    $('body').on('change', '.regionsSelect', function (e) {
        if ($(this).val() == '') {
            $('.subregionsSelect').prop("disabled", true);
            $('.citiesSelect').prop("disabled", true);
        } else {
            $('.subregionsSelect').prop("disabled", false);
            $('.citiesSelect').prop("disabled", false);

            var typeClass = ($(this).attr('class').split(' ')[1]);
            type = typeClass.substring(1);

            getSubregions($(this).val(), type, typeClass);
            getCitiesRegion($(this).val(), type, typeClass);
        }
    });

    $('body').on('change', '.subregionsSelect', function (e) {
        var typeClass = ($(this).attr('class').split(' ')[1]);
        type = typeClass.substring(1);

        if ($(this).val() == '') {
            //$('.citiesSelect').prop("disabled", true);
            var regionId = $('.regionsSelect.' + typeClass).val();
            getCitiesRegion(regionId, type, typeClass, true);
        } else {
            //$('.citiesSelect').prop("disabled", false);
            getCitiesSubregion($(this).val(), type, typeClass, false);
        }
    });
});


function getRegions(countryId, type, typeClass) {
    $.post(appVar.rootPath + '/location/get_regions', {
        countryId: countryId,
        type: type,
        typeClass: typeClass

    }, function (data) {
        data = JSON.parse(data);

        if (data.error) {
            var msg = '<div class="alert alert-danger alert-width"><button type="button" class="close" data-dismiss="alert">&times;</button>' + data.error + '</div>';
            $('#errorMsg').html('');
            $('#errorMsg').html(msg);

        } else {
            $('.regionsDiv').html('');
            $('.regionsDiv').html(data);
        }
    });
}

function getSubregions(regionId, type, typeClass) {
    $.post(appVar.rootPath + '/location/get_subregions', {
        regionId: regionId,
        type: type,
        typeClass: typeClass

    }, function (data) {
        data = JSON.parse(data);

        if (data.error) {
            var msg = '<div class="alert alert-danger alert-width"><button type="button" class="close" data-dismiss="alert">&times;</button>' + data.error + '</div>';
            $('#errorMsg').html('');
            $('#errorMsg').html(msg);

        } else {
            $('.subregionsDiv').html('');
            $('.subregionsDiv').html(data);
        }
    });
}

function getCitiesSubregion(subregionId, type, typeClass) {
    $.post(appVar.rootPath + '/location/get_cities', {
        subregionId: subregionId,
        type: type,
        typeClass: typeClass

    }, function (data) {
        data = JSON.parse(data);

        if (data.error) {
            var msg = '<div class="alert alert-danger alert-width"><button type="button" class="close" data-dismiss="alert">&times;</button>' + data.error + '</div>';
            $('#errorMsg').html('');
            $('#errorMsg').html(msg);

        } else {
            $('.citiesDiv').html('');
            $('.citiesDiv').html(data);
        }
    });
}

function getCitiesRegion(id, type, typeClass, subregion) {
    $.post(appVar.rootPath + '/location/get_cities_region', {
        id: id,
        type: type,
        typeClass: typeClass,
        subregion: subregion

    }, function (data) {
        data = JSON.parse(data);

        if (data.error) {
            var msg = '<div class="alert alert-danger alert-width"><button type="button" class="close" data-dismiss="alert">&times;</button>' + data.error + '</div>';
            $('#errorMsg').html('');
            $('#errorMsg').html(msg);

        } else {
            $('.citiesDiv').html('');
            $('.citiesDiv').html(data);
        }
    });
}