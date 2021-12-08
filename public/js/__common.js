var prevTime;
var checkTime = 180000;
var amountDecimals = [];
var priceDecimals = [];
var balanceDecimals = [];


$(function() {
    let windowWithd = window.innerWidth;
    if(windowWithd > 768) {
        alpha = 0;
    } else 
        alpha = 0;

    let setHeight = parseInt($('.inner-wrap').innerHeight()) - 120 - alpha;
    $('.common-list').css({'height': setHeight + 'px'});

    checkDecisionRecord();
    setInterval(checkDecisionRecord, checkTime);
    offAutoCmplt();
});

$(window).resize(function(e) {
    e.preventDefault();
    let windowWithd = window.innerWidth;
    if(windowWithd > 768) {
        alpha = 0;
    } else 
        alpha = 0;
    let setHeight = parseInt($('.inner-wrap').innerHeight()) - 140 - alpha;
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

function _number_format(src, decimals) {
    if (decimals == 0) {
        return number_format(src, 0);
    }

    return removeTrailingZero(number_format(src, decimals));
}

function removeTrailingZero(src) {
    var i = src.length - 1;
    for (; i >= 0; i --) {
        if (src[i] == '.' || src[i] != 0) break;
    }
    if (src[i] == '.') {
        return src.substr(0, i);
    }
    return src.substr(0, i + 1);
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

function __noticeAudio() {
    var count = 1;
    var audio = document.getElementById('alert-audio');
        audio.onended = function() {
            if(count <= 2){
            count++;
            this.play();
        }
    };
    audio.play();
}

function __parseFloat(value) {
    if(value == undefined || value == null || isNaN(value) || value == '' || value == 'Infinity') 
        return 0;

    return parseFloat(value);
}

function __parseStr(value) {
    if(value == undefined || value == null || value == 0 || value == '') return '';

    return value;
}

function formatRate(rate) {
    if (rate==null||rate==undefined||rate=='') return '';
    return parseFloat(rate).toFixed(4);
}

function prettyValue2(value)
{
    if(value == undefined || value == null) return '';
    return parseFloat(value).toFixed(0).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,");
}

function checkDecisionRecord() {
    $.ajax({
        url: BASE_URL + 'ajax/check/report',
        type: 'get',
        success: function(data) {
            if(!data) {
                $('#unread_receive').hide();
            } else {
                let beforeCnt = __parseFloat($('#unread_receive').attr('data-val'));

                $('#unread_receive').attr('data-val', data);
                // if(beforeCnt < data)
                 __noticeAudio();
                if(data >= 100)
                    $('#unread_receive').text('+99');
                else if(data != 0)
                    $('#unread_receive').text(data);
                $('#unread_receive').show();
            }
        }
    })
}

function offAutoCmplt() {
    $('.remark').attr('autocomplete', 'off');
    $('input').attr('autocomplete', 'off');
    $('textarea').attr('autocomplete', 'off');
}
$('.hamburger-input').on('change', function() {
    if($(this).prop('checked')) {
        $('#overlay-div').addClass('overlay-show');
        $('#overlay-div').show();
    } else {
        $('#overlay-div').removeClass('overlay-show');
        $('#overlay-div').hide();
    }
});

// $('.hamburger-input').on('click', function() {
//     if($(this).prop('checked')) {
//         $('.hamburger-input').prop('checked', false);
//     } else {
//         $('.hamburger-input').prop('checked', true);
//     }
// })

$(document).mouseup(function(e) {
    var container = $("#menuToggle");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        $('.hamburger-input').prop('checked', false);
        $('#overlay-div').removeClass('overlay-show');
        $('#overlay-div').hide();
    }
});

function _overflowContainter(flow = true) {
    // if(flow == true) {
    //     $('.head-fix-div').css('overflow-y', 'visible');
    // } else if(flow == false) {
    //     $('.head-fix-div').css('overflow-y', 'auto');
    // }
}
