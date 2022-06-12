<div class="space-4"></div>
<div class="row">
    <div class="col-md-12">
        <input class="hidden" name="_token" value="{{csrf_token()}}">
        <div class="col-md-12">
            <div class="center"><h6><b>Crew Evaluation Report</b></div>
            <div class="center"><h5><b>船员评价</b></div>
            <div class="space-4"></div>
                <table class="table table-bordered" style="margin-bottom: -1px!important;">
                    <tbody>
                        <tr class="">
                            <td class="center" colspan="2" rowspan="2">Key Items</td>
                            <td class="center" rowspan="2">Description</td>
                            <td class="center" colspan="6">GRADE</td>
                        </tr>
                        <tr class="">
                            <td class="center">10</td>
                            <td class="center">9</td>
                            <td class="center">8</td>
                            <td class="center">7</td>
                            <td class="center">6</td>
                            <td class="center">5</td>
                        </tr>
                        <tr class="">
                            <td rowspan="12" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Performance Ability<br/>60%</span></td>
                            <td rowspan="2">Quantity of Work</td>
                            <td rowspan="2">Capable to complete assigned work within assigned period</td>
                            <td class="center">A</td>
                            <td class="center">B</td>
                            <td class="center">C</td>
                            <td class="center">D</td>
                            <td class="center">E</td>
                            <td class="center">F</td>
                        </tr>
                        <tr class="">
                            <td class="center"><input name="value1" data-ref="1" type="checkbox" class="grade" value="10" @if(isset($evaluation['value1']) && $evaluation['value1']==10) checked @endif/></td>
                            <td class="center"><input name="value1" data-ref="1" type="checkbox" class="grade" value="9" @if(isset($evaluation['value1']) && $evaluation['value1']==9) checked @endif/></td>
                            <td class="center"><input name="value1" data-ref="1" type="checkbox" class="grade" value="8" @if(isset($evaluation['value1']) && $evaluation['value1']==8) checked @endif/></td>
                            <td class="center"><input name="value1" data-ref="1" type="checkbox" class="grade" value="7" @if(isset($evaluation['value1']) && $evaluation['value1']==7) checked @endif/></td>
                            <td class="center"><input name="value1" data-ref="1" type="checkbox" class="grade" value="6" @if(isset($evaluation['value1']) && $evaluation['value1']==6) checked @endif/></td>
                            <td class="center"><input name="value1" data-ref="1" type="checkbox" class="grade" value="5" @if(isset($evaluation['value1']) && $evaluation['value1']==5) checked @endif/></td>
                        </tr>
                        <tr class="">
                            <td style="text-align:left!important" rowspan="2">Quality of Work</td>
                            <td rowspan="2">Maintains precision and quality of complete work</td>
                            <td class="center">A</td>
                            <td class="center">B</td>
                            <td class="center">C</td>
                            <td class="center">D</td>
                            <td class="center">E</td>
                            <td class="center">F</td>
                        </tr>
                        <tr class="">
                            <td class="center"><input name="value2" data-ref="2" type="checkbox" class="grade" value="10" @if(isset($evaluation['value2']) && $evaluation['value2']==10) checked @endif/></td>          
                            <td class="center"><input name="value2" data-ref="2" type="checkbox" class="grade" value="9" @if(isset($evaluation['value2']) && $evaluation['value2']==9) checked @endif/></td>            
                            <td class="center"><input name="value2" data-ref="2" type="checkbox" class="grade" value="8" @if(isset($evaluation['value2']) && $evaluation['value2']==8) checked @endif/></td>            
                            <td class="center"><input name="value2" data-ref="2" type="checkbox" class="grade" value="7" @if(isset($evaluation['value2']) && $evaluation['value2']==7) checked @endif/></td>            
                            <td class="center"><input name="value2" data-ref="2" type="checkbox" class="grade" value="6" @if(isset($evaluation['value2']) && $evaluation['value2']==6) checked @endif/></td>            
                            <td class="center"><input name="value2" data-ref="2" type="checkbox" class="grade" value="5" @if(isset($evaluation['value2']) && $evaluation['value2']==5) checked @endif/></td>            
                        </tr>
                        <tr class="">
                            <td style="text-align:left!important" rowspan="2">Sense of Responsibility</td>
                            <td rowspan="2">Can take the unforced problem voluntarily and work aggressively?</td>
                            <td class="center">A</td>
                            <td class="center">B</td>
                            <td class="center">C</td>
                            <td class="center">D</td>
                            <td class="center">E</td>
                            <td class="center">F</td>
                        </tr>
                        <tr class="">
                            <td class="center"><input name="value3" data-ref="3" type="checkbox" class="grade" value="10" @if(isset($evaluation['value3']) && $evaluation['value3']==10) checked @endif/></td>          
                            <td class="center"><input name="value3" data-ref="3" type="checkbox" class="grade" value="9" @if(isset($evaluation['value3']) && $evaluation['value3']==9) checked @endif/></td>            
                            <td class="center"><input name="value3" data-ref="3" type="checkbox" class="grade" value="8" @if(isset($evaluation['value3']) && $evaluation['value3']==8) checked @endif/></td>            
                            <td class="center"><input name="value3" data-ref="3" type="checkbox" class="grade" value="7" @if(isset($evaluation['value3']) && $evaluation['value3']==7) checked @endif/></td>            
                            <td class="center"><input name="value3" data-ref="3" type="checkbox" class="grade" value="6" @if(isset($evaluation['value3']) && $evaluation['value3']==6) checked @endif/></td>            
                            <td class="center"><input name="value3" data-ref="3" type="checkbox" class="grade" value="5" @if(isset($evaluation['value3']) && $evaluation['value3']==5) checked @endif/></td>            
                        </tr>
                        <tr class="">
                            <td style="text-align:left!important" rowspan="2">Diligence and Positive Attitude</td>
                            <td rowspan="2">Works diligently and solves problems with vigor</td>
                            <td class="center">A</td>
                            <td class="center">B</td>
                            <td class="center">C</td>
                            <td class="center">D</td>
                            <td class="center">E</td>
                            <td class="center">F</td>
                        </tr>
                        <tr class="">
                            <td class="center"><input name="value4" data-ref="4" type="checkbox" class="grade" value="10" @if(isset($evaluation['value4']) && $evaluation['value4']==10) checked @endif/></td>          
                            <td class="center"><input name="value4" data-ref="4" type="checkbox" class="grade" value="9" @if(isset($evaluation['value4']) && $evaluation['value4']==9) checked @endif/></td>            
                            <td class="center"><input name="value4" data-ref="4" type="checkbox" class="grade" value="8" @if(isset($evaluation['value4']) && $evaluation['value4']==8) checked @endif/></td>            
                            <td class="center"><input name="value4" data-ref="4" type="checkbox" class="grade" value="7" @if(isset($evaluation['value4']) && $evaluation['value4']==7) checked @endif/></td>            
                            <td class="center"><input name="value4" data-ref="4" type="checkbox" class="grade" value="6" @if(isset($evaluation['value4']) && $evaluation['value4']==6) checked @endif/></td>            
                            <td class="center"><input name="value4" data-ref="4" type="checkbox" class="grade" value="5" @if(isset($evaluation['value4']) && $evaluation['value4']==5) checked @endif/></td>            
                        </tr>
                        <tr class="">
                            <td style="text-align:left!important" rowspan="2">Obedience</td>
                            <td rowspan="2">Obeys instruction and be centripetal toward superiors</td>
                            <td class="center">A</td>
                            <td class="center">B</td>
                            <td class="center">C</td>
                            <td class="center">D</td>
                            <td class="center">E</td>
                            <td class="center">F</td>
                        </tr>
                        <tr class="">
                            <td class="center"><input name="value5" data-ref="5" type="checkbox" class="grade" value="10" @if(isset($evaluation['value5']) && $evaluation['value5']==10) checked @endif/></td>          
                            <td class="center"><input name="value5" data-ref="5" type="checkbox" class="grade" value="9" @if(isset($evaluation['value5']) && $evaluation['value5']==9) checked @endif/></td>            
                            <td class="center"><input name="value5" data-ref="5" type="checkbox" class="grade" value="8" @if(isset($evaluation['value5']) && $evaluation['value5']==8) checked @endif/></td>            
                            <td class="center"><input name="value5" data-ref="5" type="checkbox" class="grade" value="7" @if(isset($evaluation['value5']) && $evaluation['value5']==7) checked @endif/></td>            
                            <td class="center"><input name="value5" data-ref="5" type="checkbox" class="grade" value="6" @if(isset($evaluation['value5']) && $evaluation['value5']==6) checked @endif/></td>            
                            <td class="center"><input name="value5" data-ref="5" type="checkbox" class="grade" value="5" @if(isset($evaluation['value5']) && $evaluation['value5']==5) checked @endif/></td>            
                        </tr>
                        <tr class="">
                            <td style="text-align:left!important" rowspan="2">Skills and Experience</td>
                            <td rowspan="2">Be versed in work and be ample-experienced</td>
                            <td class="center">A</td>
                            <td class="center">B</td>
                            <td class="center">C</td>
                            <td class="center">D</td>
                            <td class="center">E</td>
                            <td class="center">F</td>
                        </tr>
                        <tr class="">
                            <td class="center"><input name="value6" data-ref="6" type="checkbox" class="grade" value="10" @if(isset($evaluation['value6']) && $evaluation['value6']==10) checked @endif/></td>          
                            <td class="center"><input name="value6" data-ref="6" type="checkbox" class="grade" value="9" @if(isset($evaluation['value6']) && $evaluation['value6']==9) checked @endif/></td>            
                            <td class="center"><input name="value6" data-ref="6" type="checkbox" class="grade" value="8" @if(isset($evaluation['value6']) && $evaluation['value6']==8) checked @endif/></td>            
                            <td class="center"><input name="value6" data-ref="6" type="checkbox" class="grade" value="7" @if(isset($evaluation['value6']) && $evaluation['value6']==7) checked @endif/></td>            
                            <td class="center"><input name="value6" data-ref="6" type="checkbox" class="grade" value="6" @if(isset($evaluation['value6']) && $evaluation['value6']==6) checked @endif/></td>            
                            <td class="center"><input name="value6" data-ref="6" type="checkbox" class="grade" value="5" @if(isset($evaluation['value6']) && $evaluation['value6']==5) checked @endif/></td>            
                        </tr>
                        <tr class="">
                            <td rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Judgment<br/>20%</span></td>
                            <td rowspan="2">Comprehension Of Instruction</td>
                            <td rowspan="2">Can understand the emphasis of problem swiftly and definitely?</td>
                            <td class="center">A</td>
                            <td class="center">B</td>
                            <td class="center">C</td>
                            <td class="center">D</td>
                            <td class="center">E</td>
                            <td class="center">F</td>
                        </tr>
                        <tr class="">
                            <td class="center"><input name="value7" data-ref="7" type="checkbox" class="grade" value="10" @if(isset($evaluation['value7']) && $evaluation['value7']==10) checked @endif/></td>          
                            <td class="center"><input name="value7" data-ref="7" type="checkbox" class="grade" value="9" @if(isset($evaluation['value7']) && $evaluation['value7']==9) checked @endif/></td>            
                            <td class="center"><input name="value7" data-ref="7" type="checkbox" class="grade" value="8" @if(isset($evaluation['value7']) && $evaluation['value7']==8) checked @endif/></td>            
                            <td class="center"><input name="value7" data-ref="7" type="checkbox" class="grade" value="7" @if(isset($evaluation['value7']) && $evaluation['value7']==7) checked @endif/></td>            
                            <td class="center"><input name="value7" data-ref="7" type="checkbox" class="grade" value="6" @if(isset($evaluation['value7']) && $evaluation['value7']==6) checked @endif/></td>            
                            <td class="center"><input name="value7" data-ref="7" type="checkbox" class="grade" value="5" @if(isset($evaluation['value7']) && $evaluation['value7']==5) checked @endif/></td>            
                        </tr>
                        <tr class="">
                            <td style="text-align:left!important" rowspan="2">Judgement and Ability to React</td>
                            <td rowspan="2">Can stay calm in emergency and react sensitively?</td>
                            <td class="center">A</td>
                            <td class="center">B</td>
                            <td class="center">C</td>
                            <td class="center">D</td>
                            <td class="center">E</td>
                            <td class="center">F</td>
                        </tr>
                        <tr class="">
                            <td class="center"><input name="value8" data-ref="8" type="checkbox" class="grade" value="10" @if(isset($evaluation['value8']) && $evaluation['value8']==10) checked @endif/></td>          
                            <td class="center"><input name="value8" data-ref="8" type="checkbox" class="grade" value="9" @if(isset($evaluation['value8']) && $evaluation['value8']==9) checked @endif/></td>            
                            <td class="center"><input name="value8" data-ref="8" type="checkbox" class="grade" value="8" @if(isset($evaluation['value8']) && $evaluation['value8']==8) checked @endif/></td>            
                            <td class="center"><input name="value8" data-ref="8" type="checkbox" class="grade" value="7" @if(isset($evaluation['value8']) && $evaluation['value8']==7) checked @endif/></td>            
                            <td class="center"><input name="value8" data-ref="8" type="checkbox" class="grade" value="6" @if(isset($evaluation['value8']) && $evaluation['value8']==6) checked @endif/></td>            
                            <td class="center"><input name="value8" data-ref="8" type="checkbox" class="grade" value="5" @if(isset($evaluation['value8']) && $evaluation['value8']==5) checked @endif/></td>            
                        </tr>
                        <tr class="">
                            <td rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Morality<br/>Discipline<br/>20%</span></td>
                            <td rowspan="2">Character</td>
                            <td rowspan="2">Be polite to superiors and treats others with honesty.</td>
                            <td class="center">A</td>
                            <td class="center">B</td>
                            <td class="center">C</td>
                            <td class="center">D</td>
                            <td class="center">E</td>
                            <td class="center">F</td>
                        </tr>
                        <tr class="">
                            <td class="center"><input name="value9" data-ref="9" type="checkbox" class="grade" value="10" @if(isset($evaluation['value9']) && $evaluation['value9']==10) checked @endif/></td>          
                            <td class="center"><input name="value9" data-ref="9" type="checkbox" class="grade" value="9" @if(isset($evaluation['value9']) && $evaluation['value9']==9) checked @endif/></td>            
                            <td class="center"><input name="value9" data-ref="9" type="checkbox" class="grade" value="8" @if(isset($evaluation['value9']) && $evaluation['value9']==8) checked @endif/></td>            
                            <td class="center"><input name="value9" data-ref="9" type="checkbox" class="grade" value="7" @if(isset($evaluation['value9']) && $evaluation['value9']==7) checked @endif/></td>            
                            <td class="center"><input name="value9" data-ref="9" type="checkbox" class="grade" value="6" @if(isset($evaluation['value9']) && $evaluation['value9']==6) checked @endif/></td>            
                            <td class="center"><input name="value9" data-ref="9" type="checkbox" class="grade" value="5" @if(isset($evaluation['value9']) && $evaluation['value9']==5) checked @endif/></td>            
                        </tr>
                        <tr class="">
                            <td style="text-align:left!important" rowspan="2">Socialization</td>
                            <td rowspan="2">Communicates effectively and develops the spirit of mutual cooperation.</td>
                            <td class="center">A</td>
                            <td class="center">B</td>
                            <td class="center">C</td>
                            <td class="center">D</td>
                            <td class="center">E</td>
                            <td class="center">F</td>
                        </tr>
                        <tr class="">
                            <td class="center"><input name="value10" data-ref="10" type="checkbox" class="grade" value="10" @if(isset($evaluation['value10']) && $evaluation['value10']==10) checked @endif/></td>          
                            <td class="center"><input name="value10" data-ref="10" type="checkbox" class="grade" value="9" @if(isset($evaluation['value10']) && $evaluation['value10']==9) checked @endif/></td>            
                            <td class="center"><input name="value10" data-ref="10" type="checkbox" class="grade" value="8" @if(isset($evaluation['value10']) && $evaluation['value10']==8) checked @endif/></td>            
                            <td class="center"><input name="value10" data-ref="10" type="checkbox" class="grade" value="7" @if(isset($evaluation['value10']) && $evaluation['value10']==7) checked @endif/></td>            
                            <td class="center"><input name="value10" data-ref="10" type="checkbox" class="grade" value="6" @if(isset($evaluation['value10']) && $evaluation['value10']==6) checked @endif/></td>            
                            <td class="center"><input name="value10" data-ref="10" type="checkbox" class="grade" value="5" @if(isset($evaluation['value10']) && $evaluation['value10']==5) checked @endif/></td>            
                        </tr>
                        <tr class="">
                            <td style="text-align:right!important;" class="center" colspan="3">Total&nbsp;</td>
                            <td class="center" colspan="6"><input id="total" class="form-control" type="text"/></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered" style="margin-bottom: -1px!important;">
                    <tbody>
                        <tr class="">
                            <td rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Superior’s comments</span></td>
                            <td class="center">Master</td>
                            <td class="center"><textarea type="text" name="master" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['master']) ? $evaluation['master'] : ''}}</textarea></td>
                            <td rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Signature of Superior</span></td>
                            <td class="center"><textarea type="text" name="sign1" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['sign1']) ? $evaluation['sign1'] : ''}}</textarea></td>
                        </tr>
                        <tr class="">
                            <td class="center">C/E</td>
                            <td class="center"><textarea type="text" name="ce" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['ce']) ? $evaluation['ce'] : ''}}</textarea></td>
                            <td class="center"><textarea type="text" name="sign2" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['sign2']) ? $evaluation['sign2'] : ''}}</textarea></td>
                        </tr>
                        <tr class="">
                            <td class="center">C/O</td>
                            <td class="center"><textarea type="text" name="co" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['co']) ? $evaluation['co'] : ''}}</textarea></td>
                            <td class="center"><textarea type="text" name="sign3" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['sign3']) ? $evaluation['sign3'] : ''}}</textarea></td>
                        </tr>
                        <tr class="">
                            <td class="center">1/E</td>
                            <td class="center"><textarea type="text" name="1e" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['1e']) ? $evaluation['1e'] : ''}}</textarea></td>
                            <td class="center"><textarea type="text" name="sign4" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['sign4']) ? $evaluation['sign4'] : ''}}</textarea></td>
                        </tr>
                        <tr>
                            <td style="text-align:left!important;" colspan="5">Company’s evaluate：<input class="grade" data-ref="11" name="qualified" type="checkbox" value="1" @if(isset($evaluation['qualified']) && $evaluation['qualified']==1) checked @endif><label style="margin-top:-5px;">qualified</label></input>&nbsp;&nbsp;&nbsp;&nbsp;<input class="grade" data-ref="11" name="qualified" type="checkbox" value="2" @if(isset($evaluation['qualified']) && $evaluation['qualified']==2) checked @endif><label style="margin-top:-5px;">disqualified</label></input></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <tbody>
                        <tr class="">
                        <td rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Operational</span></td>
                            <td style="vertical-align:top;"><textarea type="text" name="operational" class="form-control text-left auto-area" style="height:100px;" autocomplete="off">{{isset($evaluation['operational']) ? $evaluation['operational'] : ''}}</textarea></td>
                            <td rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">General Affair Dept.</span></td>
                            <td style="vertical-align:top;"><textarea type="text" name="general_dept" class="form-control text-left auto-area" style="height:100px;" autocomplete="off">{{isset($evaluation['general_dept']) ? $evaluation['general_dept'] : ''}}</textarea></td>
                            <td rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Technical Dep</span></td>
                            <td style="vertical-align:top;"><textarea type="text" name="technical_dept" class="form-control text-left auto-area" style="height:100px;" autocomplete="off">{{isset($evaluation['technical_dept']) ? $evaluation['technical_dept'] : ''}}</textarea></td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>

