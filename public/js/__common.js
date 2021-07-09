// Coded by H(S
var prevTime;
var checkTime = 5000;
var amountDecimals = [];
var priceDecimals = [];
var balanceDecimals = [];


$(function() {
    let setHeight = parseInt($('.inner-wrap').innerHeight()) - 140;
    $('.common-list').css({'height': setHeight + 'px'});

    checkDecisionRecord();
    setInterval(checkDecisionRecord, checkTime);
});

$(window).resize(function(e) {
    e.preventDefault();
    let setHeight = parseInt($('.inner-wrap').innerHeight()) - 140;
    $('.common-list').css({'height': setHeight + 'px'});
});

function number_format (number, decimals, dec_point = '.', thousands_sep = ',') {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function copyStringToClipboard (str) {
    var el = document.createElement('textarea');
    el.value = str;
    el.setAttribute('readonly', '');
    el.style = {position: 'absolute', left: '-9999px'};
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
}

String.prototype.padLeft = function (length, character) {
    return new Array(length - this.length + 1).join(character || '0') + this;
}

function showToast(msg, title, type) {
    if (type == 'warning') {
        toastr.warning(msg, title, {
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            positionClass: 'toast-bottom-center',
            timeOut: 3000
        });
    }
    else if (type == 'success') {
        toastr.success(msg, title, {
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            positionClass: 'toast-bottom-center',
            timeOut: 3000
        });
    }
}

function getMasterData() {
    $.ajax({
        url: BASE_URL + 'ajax/common/getMasterData',
        type: 'POST',
        success: function(result) {
            amountDecimals = result['amount_decimals'];
            priceDecimals = result['price_decimals'];
            balanceDecimals = result['balance_decimals'];
        },
        error: function(err) {
            //bootbox.alert('Getting master data has failed with error.');
            console.log(err);
        }
    });
}

function g_exportExcel(tableId, strFileName, strSheetName) {
    $(tableId).table2excel({
        name: strSheetName,
        filename: strFileName //do not include extension
    });
}

function goBack() {
    window.history.back();
}

function showOverlay(obj, flag, text = '') {
    if (flag == true) {
        obj.LoadingOverlay('show', {
            text : text
        });
    }
    else {
        obj.LoadingOverlay('hide');
    }
}

function getValueOfObjs(objs) {
    result = [];
    for (let i = 0; i < objs.length; i ++) {
        result.push($(objs[i]).val());
    }
    return result;
}

function _convertDate(value, format = '/') {
    let date = new Date(value);

    return date.getFullYear() + format + (date.getMonth() + 1) + format + date.getDate();
}

function daysInMonth(month, year) {
    return new Date(year, month, 0).getDate();
}

function checkDecisionRecord() {
    $.ajax({
        url: BASE_URL + 'ajax/check/report',
        type: 'post',
        success: function(data) {
            if(!data) {
                $('#unread_receive').hide();
            } else {
                if(data >= 100)
                    $('#unread_receive').text('+99');
                else if(data != 0)
                    $('#unread_receive').text(data);
                $('#unread_receive').show();
            }
        }
    })
}

$(".modal-draggable").draggable({
	helper: 'move',
	cursor: 'move',
	tolerance: 'fit',
	revert: "invalid",
	revert: false
});

function __alertAudio() {
    document.getElementById('warning-audio').play();
}

function __parseFloat(value) {
    if(value == undefined || value == null || isNaN(value) || value == '' || value == 'Infinity') 
        return 0;

    return parseFloat(value);
}

function __parseStr(value) {
    if(value == undefined || value == null) return '';

    return value;
}