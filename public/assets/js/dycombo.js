        for (const selector of document.querySelectorAll(".dynamic-select-wrapper")) {
            selector.addEventListener('click', function() {
                this.firstElementChild.classList.toggle('open');
                //selector.querySelector('.dynamic-options').classList.add("dynamic-popup-reverse");
            })
        }

        addCustomEvent();

        function openDynamicPopup(type) {
            $('#dynamic-type').val(type);
            
            var url;
            if (type == 'nationality') {
                url = BASE_URL + 'ajax/getNationality';
            }

            $.ajax({
                url: BASE_URL + 'ajax/getDynamicData',
                type: 'post',
                data: {
                    type: type
                },
                success: function(data, status, xhr) {
                    $('#dynamic-data').val('');
                    $('#dynamic-default').html('');
                    for (var i = 0; i < data.length; i ++) {
                        $('#dynamic-data').val($('#dynamic-data').val() + data[i].name + "\n");
                        if (data[i].isDefault)
                            $('#dynamic-default').html($('#dynamic-default').html() + '<option selected value="' + i + '">' + data[i].name + '</option>');
                        else
                            $('#dynamic-default').html($('#dynamic-default').html() + '<option value="' + i + '">' + data[i].name + '</option>');
                    }
                    $('#modal-dynamic').modal('show');
                },
                error: function(error, status) {
                    //alert(error);
                }
            });
        }

        $('#dynamic-data').change(function () {
            var data = $('#dynamic-data').val();
            var str = data.replace(/(?:\r\n|\r|\n)/g, ',');
            var list = str.split(',');
            $('#dynamic-default').html('');
            for (var i=0;i<list.length;i++)
                $('#dynamic-default').html($('#dynamic-default').html() + '<option value="' + i + '">' + list[i] + '</option>');
        });

        function dynamicSubmit() {
            var type = $('#dynamic-type').val();
            var data = $('#dynamic-data').val();
            var def = $('#dynamic-default').val();
            var str = data.replace(/(?:\r\n|\r|\n)/g, ',');
            var list = str.split(',');

            setDynamicData(type, list, def);
        }

        function updatePage(element, list, def)
        {
        }

        function setDynamicData(type, list, def) {
            $("#modal-dynamic").modal("hide");
            $.ajax({
                url: BASE_URL + 'ajax/setDynamicData', 
                type: 'post',
                data: {
                    list: list,
                    type: type,
                    default: def,
                },
                success: function(data, status, xhr) {
                    if (data != '-1') {
                        //alert("Success!");
                        var type = $('#dynamic-type').val();
                        var id='';
                        if (type == 'nationality') {
                            id = 'Nationality';
                        }
                        var dest = $('input[name="' + id + '"]').closest('.dynamic-select');
                        dest.find('.dynamic-select__trigger input').val(list[def]);
                        dest.children(":first").val(list[def]);
                        dest = dest.find('.dynamic-options-scroll');
                        dest.html('');
                        dest.html(dest.html() + '<span class="dynamic-option" data-value="" data-text="">&nbsp;</span>');
                        for (var i=0;i<list.length;i++)
                            if (i == def)
                                dest.html(dest.html() + '<span class="dynamic-option selected" data-value="' + list[i] + '" data-text="' + list[i] + '"' + '">' + list[i] + '</span>');
                            else
                                dest.html(dest.html() + '<span class="dynamic-option" data-value="' + list[i] + '" data-text="' + list[i] + '"' + '">' + list[i] + '</span>');
                        
                        addCustomEvent();
                    }
                },
                error: function(error, status) {
                    //alert("Failed!");
                }
            })
        }

        function getDynamicData(type) {
            $.ajax({
                url: BASE_URL + 'ajax/getDynamicData',
                type: 'post',
                data: {
                    type : type
                },
                success: function(data, status, xhr) {
                    console.log(data);
                },
                error: function(error, status) {
                    console.log(data);
                }
            });
        }

        ///////////////////////////////////////////////////////////////////
        /// SHIP TYPE LIST DYNAMIC LIST
        ///////////////////////////////////////////////////////////////////
        function openShipTypeList(type) {
            $.ajax({
                url: BASE_URL + 'ajax/getDynamicData',
                type: 'post',
                data: {
                    type: type
                },
                success: function(data, status, xhr) {
                    console.log(data);
                    $('#shiptype-table').html('');
                    for (var i = 0; i < data.length; i ++) {
                        var row = '<tr class="rank-tr"><td class="no-padding center">';
                        row += '<input type="hidden" name="ShipType_Id[]" value="';
                        row += data[i].id;
                        row += '">';
                        row += '<input type="text" onfocus="addShipType(this)" class="form-control" name="ShipType_OrderNo[]" value="';
                        row += data[i].OrderNo;
                        row += '" style="width: 100%;text-align: center">';
                        row += '</td><td class="no-padding"><input type="text" onfocus="addShipType(this)" class="form-control" name="ShipType_Name[]" value="';
                        row += (data[i].ShipType != null) ? data[i].ShipType : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deleteShipType(this)"><i class="icon-trash"></i></a></div></td></tr>';
                        $('#shiptype-table').append(row);
                    }
                    addShipType(null);
                    $('#modal-shiptype-list').modal('show');
                },
                error: function(error, status) {
                    //alert(error);
                }
            });
        }

        function dynamicShipTypeSubmit(type) {
            var list = [];
            if (type == 'shiptype') {
                list['ids'] = $("input[name='ShipType_Id[]']").map(function(){return $(this).val();}).get();
                list['orderno'] = $("input[name='ShipType_OrderNo[]']").map(function(){return $(this).val();}).get();
                list['name'] = $("input[name='ShipType_Name[]']").map(function(){return $(this).val();}).get();
            }

            $("#modal-shiptype-list").modal("hide");
            $.ajax({
                url: BASE_URL + 'ajax/setDynamicData', 
                type: 'post',
                data: {
                    ids: list['ids'],
                    orderno: list['orderno'],
                    name: list['name'],
                    type: type,
                },
                success: function(data, status, xhr) {
                    if (data != '-1') {
                        console.log(data);
                        var def = 0;
                        var id='';
                        if (type == 'shiptype') {
                            id = 'ShipType';
                        }
                        var def = 0;
                        var id='';
                        if (type == 'shiptype') {
                            id = 'ShipType';
                        }
                        var dest = $('input[name="' + id + '"]').closest('.dynamic-select');
                        dest.find('.dynamic-select__trigger input').val("");
                        dest.children(":first").val(def);
                        dest = dest.find('.dynamic-options-scroll');
                        dest.html('');
                        dest.html(dest.html() + '<span class="dynamic-option selected" data-value="" data-text="">&nbsp;</span>');
                        for (var i=0;i<data.length;i++) {
                            dest.html(dest.html() + '<span class="dynamic-option" data-value="' + data[i].id + '" data-text="' + data[i].ShipType + '">' + data[i].ShipType + '</span>');
                        }
                        addCustomEvent();
                        /*
                        var def = 0;
                        var id='';
                        if (type == 'shiptype') {
                            id = 'ShipType';
                        }
                        var dest = $('input[name="' + id + '"]').closest('.dynamic-select');
                        dest.find('.dynamic-select__trigger input').val("");
                        dest.children(":first").val(def);
                        dest = dest.find('.dynamic-options-scroll');
                        dest.html('');
                        dest.html(dest.html() + '<span class="dynamic-option selected" data-value="" data-text="">&nbsp;</span>');
                        for (var i=0;i<list['name'].length;i++)
                            dest.html(dest.html() + '<span class="dynamic-option" data-value="' + (i+1) + '" data-text="' + list['name'][i] + '">' + list['name'][i] + '</span>');
                        
                        addCustomEvent();
                        //alert("Success!");
                        */
                    }
                },
                error: function(error, status) {
                    //alert("Failed!");
                }
            })
        }

        function deleteShipType(e)
        {
            if ($('#shiptype-table tr').length > 2 && !$(e).closest("tr").is(":last-child")) { // && !$(e).closest("tr").is(":last-child")) {
                bootbox.confirm("Are you sure you want to delete?", function (result) {
                    var typeid = parseInt($(e).closest("tr").children().eq(0).children().eq(0).val());
                    if (result) {
                        $.ajax({
                            url: BASE_URL + 'ajax/check/shipType', 
                            type: 'post',
                            data: {
                                type: typeid,
                            },
                            success: function(data, status, xhr) {
                                if (data == true) {
                                    $(e).closest("tr").remove();
                                    resortShipType();
                                } else {
                                    alert("It cannot be deleted because the related data remains!")
                                }
                            },
                            error: function(error, status) {
                                //alert("Failed!");
                            }
                        })
                    }
                });
            }
        }

        function addShipType(e)
        {
            if ($('#shiptype-table tr').length > 0)
            {
                if (e == null || $(e).closest("tr").is(":last-child")) {
                    $("#shiptype-table").append('<tr class="rank-tr"><td class="no-padding center"><input type="hidden" name="ShipType_Id[]" value=""><input type="text" onfocus="addShipType(this)" class="form-control" name="ShipType_OrderNo[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addShipType(this)" class="form-control" name="ShipType_Name[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deleteShipType(this)"><i class="icon-trash"></i></a></div></td></tr>');
                    resortShipType();
                }
            }
        }

        function resortShipType()
        {
            for (var i=0;i<$('#shiptype-table').children().length;i++) {
                //$($('#shiptype-table').children()[i].firstChild.firstChild).val(i+1);
                $($($('#shiptype-table').children()[i].firstChild).children()[1]).val(i+1)
            }
        }

        ///////////////////////////////////////////////////////////////////
        /// RANK LIST DYNAMIC LIST
        ///////////////////////////////////////////////////////////////////
        function openRankList(type) {
            $.ajax({
                url: BASE_URL + 'ajax/getDynamicData',
                type: 'post',
                data: {
                    type: type
                },
                success: function(data, status, xhr) {
                    $('#rank-table').html('');
                    for (var i = 0; i < data.length; i ++) {
                        var row = '<tr class="rank-tr"><input type="hidden" name="Rank_Id[]" value="' + data[i].id + '"/><td class="no-padding center"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_OrderNo[]"value="';
                        row += (data[i].OrderNo != null) ? data[i].OrderNo : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_Name[]"value="';
                        row += (data[i].Duty_En != null) ? data[i].Duty_En : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding center"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_Abb[]"value="';
                        row += (data[i].Abb != null) ? data[i].Abb : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_Description[]"value="';
                        row += (data[i].Description != null) ? data[i].Description : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deleteRank(this)"><i class="icon-trash"></i></a></div></td></tr>';
                        $('#rank-table').append(row);
                    }
                    addRank(null);
                    $('#modal-rank-list').modal('show');
                },
                error: function(error, status) {
                    //alert(error);
                }
            });
        }
        
        function dynamicRankSubmit(type) {
            var list = [];
            if (type == 'rank') {
                list['id'] = $("input[name='Rank_Id[]']").map(function(){return $(this).val();}).get();
                list['orderno'] = $("input[name='Rank_OrderNo[]']").map(function(){return $(this).val();}).get();
                list['name'] = $("input[name='Rank_Name[]']").map(function(){return $(this).val();}).get();
                list['abb'] = $("input[name='Rank_Abb[]']").map(function(){return $(this).val();}).get();
                list['description'] = $("input[name='Rank_Description[]']").map(function(){return $(this).val();}).get();
            }

            $("#modal-rank-list").modal("hide");
            $.ajax({
                url: BASE_URL + 'ajax/setDynamicData', 
                type: 'post',
                data: {
                    id: list['id'],
                    orderno: list['orderno'],
                    name: list['name'],
                    abb: list['abb'],
                    description: list['description'],
                    type: type,
                },
                success: function(data, status, xhr) {
                    if (data != '-1') {
                        var def = 0;
                        var id='';
                        if (type == 'rank') {
                            id = 'DutyID_Book';
                        }
                        var dest = $('input[name="' + id + '"]').closest('.dynamic-select');
                        dest.find('.dynamic-select__trigger input').val("");
                        dest.children(":first").val(def);
                        dest = dest.find('.dynamic-options-scroll');
                        dest.html('');
                        dest.html(dest.html() + '<span class="dynamic-option selected" data-value="" data-text="">&nbsp;</span>');
                        for (var i=0;i<data.length;i++) {
                            if (list['abb'][i] != '' || list['name'][i] != '')
                                dest.html(dest.html() + '<span class="dynamic-option" data-value="' + data[i].id + '" data-text="' + data[i].Abb + '">' + data[i].Duty_En + '(' + data[i].Abb + ')' + '</span>');
                        }
                        
                        addCustomEvent();
                    }
                },
                error: function(error, status) {
                    //alert("Failed!");
                }
            })
        }

        function deleteRank(e)
        {
            if ($('#rank-table tr').length > 2 && !$(e).closest("tr").is(":last-child")) { // && !$(e).closest("tr").is(":last-child")) {
                bootbox.confirm("Are you sure you want to delete?", function (result) {
                    var rankid = parseInt($(e).closest("tr").children().eq(0).val());
                    if (result) {
                        $.ajax({
                            url: BASE_URL + 'ajax/check/rankType', 
                            type: 'post',
                            data: {
                                rank: rankid,
                            },
                            success: function(data, status, xhr) {
                                if (data == true) {
                                    resortRank(e);
                                    $(e).closest("tr").remove();
                                } else {
                                    alert("It cannot be deleted because the related data remains!")
                                }
                            },
                            error: function(error, status) {
                                //alert("Failed!");
                            }
                        })
                    }
                });
            }
        }

        function addRank(e)
        {
            if ($('#rank-table tr').length > 0)
            {
                if (e == null || $(e).closest("tr").is(":last-child")) {
                    $("#rank-table").append('<tr class="rank-tr"><input type="hidden" name="Rank_Id[]" value=""><td class="no-padding center"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_OrderNo[]"value="' + '' + '" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_Name[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_Abb[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_Description[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deleteRank(this)"><i class="icon-trash"></i></a></div></td></tr>');
                    var last_child = $('#rank-table tr:last-child');
                    last_child.children().eq(0).children().eq(0).val($("#rank-table tr").length);
                }
            }
        }

        function resortRank(e)
        {
            var index = parseInt($(e).closest("tr").children().eq(0).children().eq(0).val());
            for (var i=$('#rank-table tr').index($(e).closest("tr"))+1;i<$('#rank-table').children().length;i++) {
                $($('#rank-table').children()[i].firstChild.firstChild).val(index);
                index ++;
            }
        }

        ///////////////////////////////////////////////////////////////////
        /// PORT LIST DYNAMIC LIST
        ///////////////////////////////////////////////////////////////////
        function openPortList(type) {
            $.ajax({
                url: BASE_URL + 'ajax/getDynamicData',
                type: 'post',
                data: {
                    type: type
                },
                success: function(data, status, xhr) {
                    $('#port-table').html('');
                    for (var i = 0; i < data.length; i ++) {
                        var row = '<tr class="rank-tr"><td class="no-padding center"><input type="text" onfocus="addPort(this)" class="form-control" name="Port_En[]"value="';
                        row += (data[i].Port_En != null) ? data[i].Port_En : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addPort(this)" class="form-control" name="Port_Cn[]"value="';
                        row += (data[i].Port_Cn != null) ? data[i].Port_Cn : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deletePort(this)"><i class="icon-trash"></i></a></div></td></tr>';
                        $('#port-table').append(row);
                    }
                    addPort(null);
                    $('#modal-port-list').modal('show');
                },
                error: function(error, status) {
                    //alert(error);
                }
            });
        }
        
        function dynamicPortSubmit(type) {
            var list = [];
            if (type == 'port') {
                list['Port_En'] = $("input[name='Port_En[]']").map(function(){return $(this).val();}).get();
                list['Port_Cn'] = $("input[name='Port_Cn[]']").map(function(){return $(this).val();}).get();
            }

            $("#modal-port-list").modal("hide");
            $.ajax({
                url: BASE_URL + 'ajax/setDynamicData', 
                type: 'post',
                data: {
                    port_en: list['Port_En'],
                    port_cn: list['Port_Cn'],
                    type: type,
                },
                success: function(data, status, xhr) {
                    if (data != '-1') {
                        var def = 0;
                        var id='';
                        if (type == 'port') {
                            id = 'PortID_Book';
                        }
                        var dest = $('input[name="' + id + '"]').closest('.dynamic-select');
                        dest.find('.dynamic-select__trigger input').val("");
                        dest.children(":first").val(def);
                        dest = dest.find('.dynamic-options-scroll');
                        dest.html('');
                        dest.html(dest.html() + '<span class="dynamic-option selected" data-value="" data-text="">&nbsp;</span>');
                        for (var i=0;i<list['Port_Cn'].length;i++) {
                            if (list['Port_Cn'][i] != '' || list['Port_En'][i] != '')
                                dest.html(dest.html() + '<span class="dynamic-option" data-value="' + (i+1) + '" data-text="' + list['Port_En'][i] + ' (' + list['Port_Cn'][i] + ')' + '">' + list['Port_En'][i] + ' (' + list['Port_Cn'][i] + ')' + '</span>');
                        }
                        
                        addCustomEvent();
                        //alert("Success!");
                    }
                },
                error: function(error, status) {
                    //alert("Failed!");
                }
            })
        }

        function deletePort(e)
        {
            if ($('#port-table tr').length > 2 && !$(e).closest("tr").is(":last-child")) { // && !$(e).closest("tr").is(":last-child")) {
                bootbox.confirm("Are you sure you want to delete?", function (result) {
                    if (result) {
                        $(e).closest("tr").remove();
                    }
                });
            }
        }

        function addPort(e)
        {
            if ($('#port-table tr').length > 0)
            {
                if (e == null || $(e).closest("tr").is(":last-child")) {
                    $("#port-table").append('<tr class="rank-tr"><td class="no-padding center"><input type="text" onfocus="addPort(this)" class="form-control" name="Port_En[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addPort(this)" class="form-control" name="Port_Cn[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deletePort(this)"><i class="icon-trash"></i></a></div></td></tr>');
                }
            }
        }

        ///////////////////////////////////////////////////////////////////
        /// CAPACITY LIST DYNAMIC LIST
        ///////////////////////////////////////////////////////////////////
        function openCapacityList(type) {
            $.ajax({
                url: BASE_URL + 'ajax/getDynamicData',
                type: 'post',
                data: {
                    type: type
                },
                success: function(data, status, xhr) {
                    $('#capacity-table').html('');
                    for (var i = 0; i < data.length; i ++) {
                        var row = '<tr class="rank-tr"><td class="no-padding center">';
                        row += '<input type="hidden" name="Capacity_Id[]" value="';
                        row += data[i].id;
                        row += '">';
                        row += '<input type="text" onfocus="addShipType(this)" class="form-control" name="Capacity_OrderNo[]" value="';
                        row += data[i].OrderNo;
                        row += '" style="width: 100%;text-align: center">';
                        row += '</td><td class="no-padding"><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_Name[]"value="';
                        row += (data[i].Capacity_En != null) ? data[i].Capacity_En : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding center"><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_STCW[]"value="';
                        row += (data[i].STCWRegCode != null) ? data[i].STCWRegCode : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_Description[]"value="';
                        row += (data[i].Description != null) ? data[i].Description : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deleteCapacity(this)"><i class="icon-trash"></i></a></div></td></tr>';
                        $('#capacity-table').append(row);
                    }
                    addCapacity(null);
                    $('#modal-capacity-list').modal('show');
                },
                error: function(error, status) {
                    //alert(error);
                }
            });
        }
        
        function dynamicCapacitySubmit(type) {
            var list = [];
            if (type == 'capacity') {
                list['ids'] = $("input[name='Capacity_Id[]']").map(function(){return $(this).val();}).get();
                list['orderno'] = $("input[name='Capacity_OrderNo[]']").map(function(){return $(this).val();}).get();
                list['name'] = $("input[name='Capacity_Name[]']").map(function(){return $(this).val();}).get();
                list['STCW'] = $("input[name='Capacity_STCW[]']").map(function(){return $(this).val();}).get();
                list['description'] = $("input[name='Capacity_Description[]']").map(function(){return $(this).val();}).get();
            }

            $("#modal-capacity-list").modal("hide");
            $.ajax({
                url: BASE_URL + 'ajax/setDynamicData', 
                type: 'post',
                data: {
                    ids: list['ids'],
                    orderno: list['orderno'],
                    name: list['name'],
                    STCW: list['STCW'],
                    description: list['description'],
                    type: type,
                },
                success: function(data, status, xhr) {
                    if (data != '-1') {
                        var def = 0;
                        var id='';
                        var id2='';
                        if (type == 'rank') {
                            id = 'CapacityID';
                            id2 = 'COEId';
                        }
                        var dest = $('input[name="' + id + '"]').closest('.dynamic-select');
                        dest.find('.dynamic-select__trigger input').val("");
                        dest.children(":first").val(def);
                        dest = dest.find('.dynamic-options-scroll');
                        dest.html('');
                        dest.html(dest.html() + '<span class="dynamic-option selected" data-value="" data-text="">&nbsp;</span>');
                        for (var i=0;i<data.length;i++)
                            dest.html(dest.html() + '<span class="dynamic-option" data-value="' + data[i].id + '" data-text="' + data[i].Capacity_EN + '">' + data[i].Capacity_EN + '</span>');

                        var dest2 = $('input[name="' + id2 + '"]').closest('.dynamic-select');
                        dest2.find('.dynamic-select__trigger input').val("");
                        dest2.children(":first").val(def);
                        dest2 = dest2.find('.dynamic-options-scroll');
                        dest2.html('');
                        dest2.html(dest.html() + '<span class="dynamic-option selected" data-value="" data-text="">&nbsp;</span>');
                        for (var i=0;i<data.length;i++)
                            dest2.html(dest2.html() + '<span class="dynamic-option" data-value="' + data[i].id + '" data-text="' + data[i].Capacity_EN + '">' + data[i].Capacity_EN + '</span>');
                        
                        
                        //alert("Success!");
                    }
                },
                error: function(error, status) {
                    //alert("Failed!");
                }
            })
        }

        function resortCapacity()
        {
            for (var i=0;i<$('#capacity-table').children().length;i++) {
                //$($('#capacity-table').children()[i].firstChild.firstChild).val(i+1);
                $($($('#capacity-table').children()[i].firstChild).children()[1]).val(i+1)
            }
        }

        function deleteCapacity(e)
        {
            if ($('#capacity-table tr').length > 2 && !$(e).closest("tr").is(":last-child")) { // && !$(e).closest("tr").is(":last-child")) {
                bootbox.confirm("Are you sure you want to delete?", function (result) {
                    var capacity = parseInt($(e).closest("tr").children().eq(0).children().eq(0).val());
                    if (result) {
                        $.ajax({
                            url: BASE_URL + 'ajax/check/capacityType', 
                            type: 'post',
                            data: {
                                capacity: capacity,
                            },
                            success: function(data, status, xhr) {
                                if (data == true) {
                                    $(e).closest("tr").remove();
                                    resortCapacity();
                                } else {
                                    alert("It cannot be deleted because the related data remains!")
                                }
                            },
                            error: function(error, status) {
                                //alert("Failed!");
                            }
                        })
                    }

                    if (result) {
                        
                    }
                });
            }
        }
                        
        function addCapacity(e)
        {
            if ($('#capacity-table tr').length > 0)
            {
                if (e == null || $(e).closest("tr").is(":last-child")) {
                    $("#capacity-table").append('<tr class="rank-tr"><td class="no-padding center"><input type="hidden" name="Capacity_Id[]" value=""><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_OrderNo[]" value="" style="width:100%;text-align:center"></td><td class="no-padding"><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_Name[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_STCW[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_Description[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deleteCapacity(this)"><i class="icon-trash"></i></a></div></td></tr>');
                    resortCapacity();
                }
            }
        }

        ///////////////////////////////////////////////////////////////////
        /// POSITION LIST DYNAMIC LIST
        ///////////////////////////////////////////////////////////////////
        function openPosList(type) {
            $.ajax({
                url: BASE_URL + 'ajax/getDynamicData',
                type: 'post',
                data: {
                    type: type
                },
                success: function(data, status, xhr) {
                    $('#pos-table').html('');
                    console.log(data);
                    for (var i = 0; i < data.length; i ++) {
                        var row = '<tr class="pos-tr"><input type="hidden" name="Pos_Id[]" value="' + data[i].id + '"/><td class="no-padding center"><input type="text" onfocus="addPos(this)" class="form-control" name="Pos_OrderNum[]"value="';
                        row += (data[i].orderNum != null) ? data[i].orderNum : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addPos(this)" class="form-control" name="Pos_Name[]"value="';
                        row += (data[i].title != null) ? data[i].title : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deletePos(this)"><i class="icon-trash"></i></a></div></td></tr>';
                        $('#pos-table').append(row);
                    }
                    addPos(null);
                    $('#modal-pos-list').modal('show');
                },
                error: function(error, status) {
                    //alert(error);
                }
            });
        }
        
        function dynamicPosSubmit(type) {
            var list = [];
            if (type == 'pos') {
                list['id'] = $("input[name='Pos_Id[]']").map(function(){return $(this).val();}).get();
                list['orderno'] = $("input[name='Pos_OrderNum[]']").map(function(){return $(this).val();}).get();
                list['name'] = $("input[name='Pos_Name[]']").map(function(){return $(this).val();}).get();
            }

            $("#modal-pos-list").modal("hide");
            $.ajax({
                url: BASE_URL + 'ajax/setDynamicData', 
                type: 'post',
                data: {
                    id: list['id'],
                    orderno: list['orderno'],
                    name: list['name'],
                    type: type,
                },
                success: function(data, status, xhr) {
                    if (data != '-1') {
                        var def = 0;
                        var id='';
                        if (type == 'pos') {
                            id = 'Position';
                        }
                        var dest = $('input[name="' + id + '"]').closest('.dynamic-select');
                        dest.find('.dynamic-select__trigger input').val("");
                        dest.children(":first").val(def);
                        dest = dest.find('.dynamic-options-scroll');
                        dest.html('');
                        dest.html(dest.html() + '<span class="dynamic-option selected" data-value="" data-text="">&nbsp;</span>');
                        for (var i=0;i<list['name'].length;i++) {
                            if (list['orderno'][i] != '' || list['name'][i] != '')
                                dest.html(dest.html() + '<span class="dynamic-option" data-value="' + (i+1) + '" data-text="' + list['name'][i] + '">' + list['name'][i] + '</span>');
                        }
                        
                        addCustomEvent();
                        //alert("Success!");
                    }
                },
                error: function(error, status) {
                    //alert("Failed!");
                }
            })
        }

        function deletePos(e)
        {
            if ($('#pos-table tr').length > 2 && !$(e).closest("tr").is(":last-child")) { // && !$(e).closest("tr").is(":last-child")) {
                bootbox.confirm("Are you sure you want to delete?", function (result) {
                    if (result) {
                        resortPos(e);
                        $(e).closest("tr").remove();
                    }
                });
            }
        }

        function addPos(e)
        {
            if ($('#pos-table tr').length > 0)
            {
                if (e == null || $(e).closest("tr").is(":last-child")) {
                    $("#pos-table").append('<tr class="pos-tr"><input type="hidden" name="Pos_Id[]" value=""><td class="no-padding center"><input type="text" onfocus="addPos(this)" class="form-control" name="Pos_OrderNum[]"value="' + '' + '" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addPos(this)" class="form-control" name="Pos_Name[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deletePos(this)"><i class="icon-trash"></i></a></div></td></tr>');
                    var last_child = $('#pos-table tr:last-child');
                    last_child.children().eq(0).children().eq(0).val($("#pos-table tr").length);
                }
            }
        }

        function resortPos(e)
        {
            var index = parseInt($(e).closest("tr").children().eq(0).children().eq(0).val());
            for (var i=$('#pos-table tr').index($(e).closest("tr"))+1;i<$('#pos-table').children().length;i++) {
                $($('#pos-table').children()[i].firstChild.firstChild).val(index);
                index ++;
            }
        }

        function addCustomEvent()
        {
            for (const option of document.querySelectorAll(".dynamic-option")) {
                option.addEventListener('click', function() {
                    if (!this.classList.contains('selected')) {
                        if (this.parentNode.querySelector('.dynamic-option.selected') != null) {
                            this.parentNode.querySelector('.dynamic-option.selected').classList.remove('selected');
                        }

                        this.classList.add('selected');
                        //this.closest('.dynamic-select').querySelector('.dynamic-select__trigger span').textContent = this.getAttribute('data-text');
                        this.closest('.dynamic-select').querySelector('.dynamic-select__trigger input').value = this.getAttribute('data-text');
                        this.closest('.dynamic-select').firstElementChild.value = this.getAttribute('data-value');
                    }
                })
            }
        }