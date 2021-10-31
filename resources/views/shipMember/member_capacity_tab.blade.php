<div class="space-4"></div>
<div class="row">
    <div class="col-md-12">
        <input class="hidden" name="_token" value="{{csrf_token()}}">
        <input class="hidden" name="memberId" value="{{$memberId}}">
        <div class="col-md-12">
            <div class="space-4"></div>
            <table class="table table-bordered" style="table-layout:fixed;">
                <tbody>
                    <tr class="">
                        <td class="center sub-header style-bold-italic" style="width:3%">No</td>
                        <td class="center sub-header style-bold-italic" style="width:28%">Type of certificates</td>
                        <td class="center sub-header style-bold-italic" style="width:30%">Capacity</td>
                        <td class="center sub-header style-bold-italic" style="width:13%">Certificates No</td>
                        <td class="center sub-header style-bold-italic" style="width:9%">Issue Date</td>
                        <td class="center sub-header style-bold-italic" style="width:9%">Expire Date</td>
                        <td class="center sub-header style-bold-italic" style="">Issued by</td>
                    </tr>
                    <tr>
                        <td class="center sub-small-header" style="">
                            1
                        </td>
                        <td class="no-padding sub-small-header style-bold-italic" style="">
                            COC: Certificate of Competency (for Officerts only)
                        </td>
                        <td class="no-padding">
                            <?php $cap = "";
                            $capacity_id = 0; ?>
                            @foreach ($capacityList as $type)
                                @if (isset($capacity['CapacityID']) && $type->id == $capacity['CapacityID'])
                                <?php $cap = $type->Capacity_En; 
                                $capacity_id = $type->id;
                                ?>
                                @endif
                            @endforeach
                            <div class="dynamic-select-wrapper">
                                <div class="dynamic-select" style="color:#12539b">
                                    <input type="hidden"  name="CapacityID" value="{{$capacity_id}}"/>
                                    <div class="dynamic-select__trigger"><input type="text" class="form-control dynamic-select-span" value="{{$cap}}" readonly>
                                        <div class="arrow"></div>
                                    </div>
                                    <div class="dynamic-options" style="width:456px;">
                                        <div class="dynamic-options-scroll">
                                            @if ($cap == "")
                                            <span class="dynamic-option selected" data-value="" data-text="" style="width:437px">&nbsp;</span>
                                            @else
                                            <span class="dynamic-option" data-value="" data-text="" style="width:437px">&nbsp;</span>
                                            @endif
                                            @foreach ($capacityList as $type)
                                                @if (isset($capacity['CapacityID']) && $type->id == $capacity['CapacityID'])
                                                <span class="dynamic-option selected" data-value="{{$type->id}}" data-text="{{$type->Capacity_En}}" style="width:437px">{{$type->Capacity_En}}</span>
                                                @else
                                                <span class="dynamic-option" data-value="{{$type->id}}" data-text="{{$type->Capacity_En}}" style="width:437px">{{$type->Capacity_En}}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div>
                                            <span class="edit-list-btn" id="edit-list-btn" onclick="javascript:openCapacityList('capacity')">
                                                <img src="{{ cAsset('assets/img/list-edit.png') }}" alt="Edit List Items">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="ItemNo" value="@if(isset($capacity['ItemNo'])){{$capacity['ItemNo']}}@endif" style="width: 100%;text-align: center">
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy-mm-dd"
                                        name="COC_IssuedDate"
                                        value="@if(isset($capacity['COC_IssuedDate'])&&$capacity['COC_IssuedDate']!=EMPTY_DATE){{$capacity['COC_IssuedDate']}}@endif">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy-mm-dd"
                                        name="COC_ExpiryDate"
                                        value="@if(isset($capacity['COC_ExpiryDate'])&&$capacity['COC_ExpiryDate']!=EMPTY_DATE){{$capacity['COC_ExpiryDate']}}@endif">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="COC_Remarks" value="@if(isset($capacity['COC_Remarks'])){{$capacity['COC_Remarks']}}@endif" style="width: 100%;text-align: center">
                        </td>
                    </tr>
                    <tr>
                        <td class="center sub-small-header" style="">
                            2
                        </td>
                        <td class="no-padding sub-small-header style-bold-italic" style="">
                            COE: Certificate of Endorsement (by third Flag only)
                        </td>
                        <td class="no-padding">
                            <?php $cap = "";
                            $capacity_id = 0;
                             ?>
                            @foreach ($capacityList as $type)
                                @if (isset($capacity['COEId']) && $type->id == $capacity['COEId'])
                                <?php $cap = $type->Capacity_En; 
                                $capacity_id = $type->id;
                                ?>
                                @endif
                            @endforeach
                            <div class="dynamic-select-wrapper">
                                <div class="dynamic-select" style="color:#12539b">
                                    <input type="hidden"  name="COEId" value="{{$capacity_id}}"/>
                                    <div class="dynamic-select__trigger"><input type="text" class="form-control dynamic-select-span" value="{{$cap}}" readonly>
                                        <div class="arrow"></div>
                                    </div>
                                    <div class="dynamic-options" style="width:456px;">
                                        <div class="dynamic-options-scroll">
                                            @if ($cap == "")
                                            <span class="dynamic-option selected" data-value="" data-text="" style="width:437px">&nbsp;</span>
                                            @else
                                            <span class="dynamic-option" data-value="" data-text="" style="width:437px">&nbsp;</span>
                                            @endif
                                            @foreach ($capacityList as $type)
                                                @if (isset($capacity['COEId']) && $type->id == $capacity['COEId'])
                                                <span class="dynamic-option selected" data-value="{{$type->id}}" data-text="{{$type->Capacity_En}}" style="width:437px">{{$type->Capacity_En}}</span>
                                                @else
                                                <span class="dynamic-option" data-value="{{$type->id}}" data-text="{{$type->Capacity_En}}" style="width:437px">{{$type->Capacity_En}}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div>
                                            <span class="edit-list-btn" id="edit-list-btn" onclick="javascript:openCapacityList('capacity')">
                                                <img src="{{ cAsset('assets/img/list-edit.png') }}" alt="Edit List Items">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="COENo" value="@if(isset($capacity['COENo'])){{$capacity['COENo']}}@endif" style="width: 100%;text-align: center">
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy-mm-dd"
                                        name="COE_IssuedDate"
                                        value="@if(isset($capacity['COE_IssuedDate'])&&$capacity['COE_IssuedDate']!=EMPTY_DATE){{$capacity['COE_IssuedDate']}}@endif">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy-mm-dd"
                                        name="COE_ExpiryDate"
                                        value="@if(isset($capacity['COE_ExpiryDate'])&&$capacity['COE_ExpiryDate']!=EMPTY_DATE){{$capacity['COE_ExpiryDate']}}@endif">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="COE_Remarks" value="@if(isset($capacity['COE_Remarks'])){{$capacity['COE_Remarks']}}@endif" style="width: 100%;text-align: center">
                        </td>
                    </tr>
                    <tr>
                        <td class="center sub-small-header" style="">
                            3
                        </td>
                        <td class="no-padding sub-small-header style-bold-italic" style="" colspan="2">
                            GOC: GMDSS general operator (for Officerts only)
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="GMDSS_NO" value="@if(isset($capacity['GMDSS_NO'])){{$capacity['GMDSS_NO']}}@endif" style="width: 100%;text-align: center">
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy-mm-dd"
                                        name="GMD_IssuedDate"
                                        value="@if(isset($capacity['GMD_IssuedDate'])&&$capacity['GMD_IssuedDate']!=EMPTY_DATE){{$capacity['GMD_IssuedDate']}}@endif">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy-mm-dd"
                                        name="GMD_ExpiryDate"
                                        value="@if(isset($capacity['GMD_ExpiryDate'])&&$capacity['GMD_ExpiryDate']!=EMPTY_DATE){{$capacity['GMD_ExpiryDate']}}@endif">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="GMD_Remarks" value="@if(isset($capacity['GMD_Remarks'])){{$capacity['GMD_Remarks']}}@endif" style="width: 100%;text-align: center">
                        </td>
                    </tr>
                    <tr>
                        <td class="center sub-small-header" style="">
                            4
                        </td>
                        <td class="no-padding sub-small-header style-bold-italic" style="" colspan="2">
                            GOC Endorsement (by third Flag only)
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="COE_GOCNo" value="@if(isset($capacity['COE_GOCNo'])){{$capacity['COE_GOCNo']}}@endif" style="width: 100%;text-align: center">
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy-mm-dd"
                                        name="COE_GOC_IssuedDate"
                                        value="@if(isset($capacity['COE_GOC_IssuedDate'])&&$capacity['COE_GOC_IssuedDate']!=EMPTY_DATE){{$capacity['COE_GOC_IssuedDate']}}@endif">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy-mm-dd"
                                        name="COE_GOC_ExpiryDate"
                                        value="@if(isset($capacity['COE_GOC_ExpiryDate'])&&$capacity['COE_GOC_ExpiryDate']!=EMPTY_DATE){{$capacity['COE_GOC_ExpiryDate']}}@endif">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="COE_GOC_Remarks" value="@if(isset($capacity['COE_GOC_Remarks'])){{$capacity['COE_GOC_Remarks']}}@endif" style="width: 100%;text-align: center">
                        </td>
                    </tr>
                    <tr>
                        <td class="center sub-small-header" style="">
                            5
                        </td>
                        <td class="no-padding" style="" colspan="2">
                            <select class="form-control style-bold-italic sub-small-header" name="WatchID" style="height:18px;padding:0px!important;-webkit-appearance: none;">
                                <option value="0" @if(isset($capacity['WatchID']) && $capacity['WatchID'] == 0)) selected @endif>Navigation watch rating</option>
                                <option value="1" @if(isset($capacity['WatchID']) && $capacity['WatchID'] == 1)) selected @endif>Engineroom watch rating</option>
                            </select>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="WatchNo" value="@if(isset($capacity['WatchNo'])){{$capacity['WatchNo']}}@endif" style="width: 100%;text-align: center">
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy-mm-dd"
                                        name="Watch_IssuedDate"
                                        value="@if(isset($capacity['Watch_IssuedDate'])&&$capacity['Watch_IssuedDate']!=EMPTY_DATE){{$capacity['Watch_IssuedDate']}}@endif">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy-mm-dd"
                                        name="Watch_ExpiryDate"
                                        value="@if(isset($capacity['Watch_ExpiryDate'])&&$capacity['Watch_ExpiryDate']!=EMPTY_DATE){{$capacity['Watch_ExpiryDate']}}@endif">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="Watch_Remarks" value="@if(isset($capacity['Watch_Remarks'])){{$capacity['Watch_Remarks']}}@endif" style="width: 100%;text-align: center">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="space-2"></div>
        </div>
    </div>
</div>