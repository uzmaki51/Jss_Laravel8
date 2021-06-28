/**
 * copyright cib 2015
 */
$(function () {
    var $menuselected = $("#topmenu a.selected");
	//var $homeselected = $("#topmenu a.home-selected").length>0;
	var $currentMenu=$("#topmenu a.selected");

    $("#topmenu>li").mouseenter(function (e) {
		//$homeselected?$currentMenu.removeClass("home-selected"):$currentMenu.removeClass("selected");
        //$currentMenu.removeClass("selected");
        var $submenu = $(this).children(".sub-menu");
        if ($submenu.length == 0) {			
            return false;
        }
        $submenu.show().find(".third-menu").show();
        $submenu.prev("a").addClass("selected");
        e.stopPropagation();
    }).mouseleave(function () {			
            var $submenu = $(this).children(".sub-menu");
            if ($submenu.length == 0) {
                return;
            }
            $submenu.hide(10).find(".third-menu").hide();
            $submenu.prev("a").removeClass("selected");
            
        });

        $( "#news" ).load( "http://www.cnshipnet.com/news/" );
	
});


function exportExcel(tableText, filename, worksheetName) {
    let downloadLink = document.createElement("a");
    let uri = 'data:application/vnd.ms-excel;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body>' + tableText + '</body></html>'
        , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
        , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }

    let ctx = { worksheet: worksheetName || 'Worksheet', table: tableText }
    // window.location.href = uri + base64(format(template, ctx));
    downloadLink.href = uri + base64(format(template, ctx));
    downloadLink.download = (filename) + ".xls";

    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

