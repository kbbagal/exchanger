$(document).ready(function(){
    $('#exchange-rates-data').DataTable({
        "paging": false,
        "ordering": true,
        "searching": false,
        "info": false
    });
    $('#add-exchg-rate').click(function(){
        resetAddRateForm();
        $('#save-exchange-form').dialog();
    });
    $('#save-exchg-rate').click(function(){
        saveExchangeRate($('#save-exchg-rate').val(), $('#save-exchg-rate').attr('data-attrib'));
    });
    
    $('body').on('click', '.fa-trash', function(){
        isConfirmed = confirm('Are you sure to delete?');
        if(isConfirmed == true) {
            removeExhangeRate($(this).attr('data-attrib'));
        }
    });
    $('body').on('click', '.fa-edit', function(){
        prepareExchangeRateUpdateForm($(this));
    });
    
    getSavedExchangeRates();
});

/**
 * Function to create/update destnation
 */
function saveExchangeRate(action, id){
    var currency = $('#currency').val();
    var exchangeRate = $('#exchg-rate').val();
    var url = '/exchange/add';
    var requestType = 'POST';
    if(currency === '') {
        alert('Please provide currency name');
        return;
    }
    if(exchangeRate === '' || ! $.isNumeric(exchangeRate)) {
        alert('Please provide valid exchange rate');
        return;
    }
    if(action === 'Update') {
        url = '/exchange/update';
        requestType = 'PUT';
    }
    
    $.ajax({
        'type' : requestType,
        'url' : url,
        'data' : 'currency=' + currency + '&exchangeRate=' + exchangeRate + '&id=' + id,
        'success' : function() {
            alert('Exchange rate data saved!');
            $('#save-exchange-form').dialog('close');
            getSavedExchangeRates();
        },
        'error' : function() {
            alert('An error occured, please try again!');
        }
    });
}

/**
 * Function to delete destination
 */
function removeExhangeRate(id) {
    $.ajax({
        'type' : 'DELETE',
        'url' : '/exchange/remove',
        'data' : 'id=' + id,
        'success' : function() {
            alert('Exchange rate data deleted!');
            getSavedExchangeRates();
        },
        'error' : function() {
            alert('An error occured, please try again!');
        }
    });
}

/**
 * Function to update destination form
 */
function prepareExchangeRateUpdateForm(exchangeRate) {
    var siblings = $(exchangeRate).parent().siblings('td');
    $('#currency').val($(siblings[0]).text());
    $('#exchg-rate').val($(siblings[1]).text());
    $('#save-exchg-rate').val('Update');
    $('#save-exchg-rate').attr('data-attrib', $(exchangeRate).attr('data-attrib'));
    $('#save-exchange-form').attr('title','Update exchange rate');
    $('#save-exchange-form').dialog();
}

/**
 * Function to reset/clear destination form
 */
function resetAddRateForm() {
    $('#currency').val('');
    $('#exchg-rate').val('');
    $('#dest-desc').val('');
    $('#save-exchg-rate').removeAttr('data-attrib');
    $('#save-exchange-form').attr('title','Add exchange rate');
    $('#save-exchg-rate').val('Save');    
}

/**
 * Function to load saved exchange rates
 */
function getSavedExchangeRates() {
    $.ajax({
        'type' : 'get',
        'url' : '/exchange/getalldata',
        'success' : function(data) {
            prepareExchangeRatesMarkup($.parseJSON(data));
        },
        'error' : function() {
            alert('An error occured, please try again!');
        }
    });
}

/**
 * Function to prepare exchange rates dashboard markup
 */
function prepareExchangeRatesMarkup(exchangeRatesData) {
    var exchangeRateMarkup = '<thead><tr><th>Currency</th><th>Exchange Rate</th><th>Created On</th>\n\
                             <th>Last updated On</th>';
    if($('#user-action').val() == 'edit') {
        exchangeRateMarkup += '<th>Action</th>'
    }
    exchangeRateMarkup += '</tr></thead><tbody>';
    for(var index = 0; index < exchangeRatesData.length; index++) {
        if(exchangeRatesData[index]['updatedDatetime'] == null) {
            exchangeRatesData[index]['updatedDatetime'] = '';
        }
        exchangeRateMarkup += "<tr><td class='data-row'>" + exchangeRatesData[index]['currency'] + "</td>";
        exchangeRateMarkup += "<td class='data-row'>" + exchangeRatesData[index]['exchangeRate'] + "</td>";
        exchangeRateMarkup += "<td class='data-row'>" + exchangeRatesData[index]['createdDatetime'] + "</td>";
        exchangeRateMarkup += "<td class='data-row'>" + exchangeRatesData[index]['updatedDatetime'] + "</td>";
        if($('#user-action').val() == 'edit') {
            exchangeRateMarkup += "<td class='data-row'><i class='fa fa-edit' style='cursor:pointer' data-attrib='" + exchangeRatesData[index]['id'] + "'  title='Click to edit'></i> | <i class='fa fa-trash fa-2' aria-hidden='true' style='cursor:pointer' data-attrib='" + exchangeRatesData[index]['id'] + "'  title='Click to delete'></i></td>";
        }
        exchangeRateMarkup += "</tr>";
    }
    exchangeRateMarkup += "</tbody>";
    $('#exchange-rates-data').html(exchangeRateMarkup);
}