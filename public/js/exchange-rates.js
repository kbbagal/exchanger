$(document).ready(function () {
    //$('#accordion').accordion({collapsable: true});
    $('#refresh-rates').click(function () {
        //getLatestCurrencyRates();
    });
});
/**
 * Function to get JSON data
 */
function getLatestCurrencyRates() {
    $.ajax({
        'type': 'GET',
        'url': 'latestrates',
        'success': function (latestRates) {
            console.log(latestRates);
            $('#accordion').html(prepareRatesMarkup($.parseJSON(latestRates)));
            $('#accordion').accordion("refresh");
            $('#accordion').children('.ui-accordion-header').first().trigger('click');
        },
        'error': function () {
            alert('An error occured, please try again!');
        }
    });
}

/**
 * Function to prepare HTML from JSON data received
 */
function prepareRatesMarkup(rawForecasts) {
    var foreCastMarkup = '';
    for (var index = 0; index < rawForecasts['cnt']; index++) {
        foreCastMarkup += '<h3>' + rawForecasts['list'][index]['name'] + '</h3>';
        foreCastMarkup += '<div><p> Weather : ' + rawForecasts['list'][index]['weather'][0]['main'];
        foreCastMarkup += ' [' + rawForecasts['list'][index]['weather'][0]['description'] + ']<br/>';
        foreCastMarkup += ' Temperature : ' + rawForecasts['list'][index]['main']['temp'] + '<br/>';
        foreCastMarkup += ' Humidity : ' + rawForecasts['list'][index]['main']['humidity'] + '<br/>';
        foreCastMarkup += ' </p></div>';
    }

    return foreCastMarkup;
}

