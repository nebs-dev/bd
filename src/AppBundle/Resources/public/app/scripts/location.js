$(document).ready(function() {

    $('.locationAutocomplete').autocomplete({
       serviceUrl: appVar.rootPath + '/search/autocomplete/location',
       type: 'POST',
       dataType: 'json',
       lookupFilter: function(suggestion, originalQuery, queryLowerCase) {
           var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
           return re.test(suggestion.value);
       },
       onSelect: function(suggestion) {
           $('#selction-ajax').html('You selected: ' + suggestion.value + ' - ' + suggestion.data);
       },
       onHint: function (hint) {
           $('#autocomplete-ajax-x').val(hint);
       },
       onInvalidateSelection: function() {
           $('#selction-ajax').html('You selected: none');
       }
    });

});
