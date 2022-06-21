<div class="space-4"></div>
<div class="row">
    <div class="col-md-12">
        <input class="hidden" name="_token" value="{{csrf_token()}}">
        <div class="col-md-12">
            <div class="center"><h4><b>Crew Evaluation Report</b></div>
            <div class="space-4"></div>
                <table class="table table-bordered table-report" style="margin-bottom: -1px!important;">
                    <tbody>
                        <tr class="">
                            <td class="center" colspan="2" rowspan="2"><b>Key Items<br/>重点项目</b></td>
                            <td class="center" rowspan="2"><b>Description<br/>内容</b></td>
                            <td class="center" colspan="6"><b>GRADE(等级)<br/></b></td>
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
                            <td rowspan="12" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Performance Ability<br/>工作能力<br/>60%</span></td>
                            <td rowspan="2">Quantity of Work<br/>工作量</td>
                            <td rowspan="2">Capable to complete assigned work within assigned period<br/>能够在指定的时间内完成指定的工作</td>
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
                            <td style="text-align:left!important" rowspan="2">Quality of Work<br/>工作能力</td>
                            <td rowspan="2">Maintains precision and quality of complete work<br/>保持工作的准确性和质量</td>
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
                            <td style="text-align:left!important" rowspan="2">Sense of Responsibility<br/>责任感</td>
                            <td rowspan="2">Can take the unforced problem voluntarily and work aggressively?<br/>能够主动接受非强制性的问题并积极工作吗？</td>
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
                            <td style="text-align:left!important" rowspan="2">Diligence and Positive Attitude<br/>勤奋和积极态度</td>
                            <td rowspan="2">Works diligently and solves problems with vigor<br/>工作勤奋，积极解决问题</td>
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
                            <td style="text-align:left!important" rowspan="2">Obedience<br/>服从</td>
                            <td rowspan="2">Obeys instruction and be centripetal toward superiors<br/>服从指挥并以上级为中心</td>
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
                            <td style="text-align:left!important" rowspan="2">Skills and Experience<br/>工作技能和经验</td>
                            <td rowspan="2">Be versed in work and be ample-experienced<br/>精通工作并经验丰富</td>
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
                            <td rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Judgment<br/>判断力<br/>20%</span></td>
                            <td rowspan="2">Comprehension Of Instruction<br/>指令理解</td>
                            <td rowspan="2">Can understand the emphasis of problem swiftly and definitely?<br/>能够迅速明确地把握问题的重点吗？</td>
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
                            <td style="text-align:left!important" rowspan="2">Judgement and Ability to React<br/>判断和反应能力</td>
                            <td rowspan="2">Can stay calm in emergency and react sensitively?<br/>在紧急情况下冷静思考并反应灵敏吗？</td>
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
                            <td rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Morality<br/>Discipline<br/>道德自律<br/>20%</span></td>
                            <td rowspan="2">Character<br/>品质</td>
                            <td rowspan="2">Be polite to superiors and treats others with honesty.<br/>对上级有礼貌，待人诚实</td>
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
                            <td style="text-align:left!important" rowspan="2">Socialization<br/>社交</td>
                            <td rowspan="2">Communicates effectively and develops the spirit of mutual cooperation.<br/>有效沟通，培养相互合作的精神</td>
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
                <table class="table table-bordered table-report2" style="margin-bottom: -1px!important;">
                    <tbody>
                        <tr class="">
                            <td class="gray" rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Superior’s<br/>comments</span></td>
                            <td class="center gray">Master</td>
                            <td class="center"><textarea type="text" name="master" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['master']) ? $evaluation['master'] : ''}}</textarea></td>
                            <td class="gray" rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Signature of Superior</span></td>
                            <td class="center"><textarea type="text" name="sign1" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['sign1']) ? $evaluation['sign1'] : ''}}</textarea></td>
                        </tr>
                        <tr class="">
                            <td class="center gray">C/E</td>
                            <td class="center"><textarea type="text" name="ce" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['ce']) ? $evaluation['ce'] : ''}}</textarea></td>
                            <td class="center"><textarea type="text" name="sign2" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['sign2']) ? $evaluation['sign2'] : ''}}</textarea></td>
                        </tr>
                        <tr class="">
                            <td class="center gray">C/O</td>
                            <td class="center"><textarea type="text" name="co" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['co']) ? $evaluation['co'] : ''}}</textarea></td>
                            <td class="center"><textarea type="text" name="sign3" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['sign3']) ? $evaluation['sign3'] : ''}}</textarea></td>
                        </tr>
                        <tr class="">
                            <td class="center gray">1/E</td>
                            <td class="center"><textarea type="text" name="1e" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['1e']) ? $evaluation['1e'] : ''}}</textarea></td>
                            <td class="center"><textarea type="text" name="sign4" class="form-control text-left auto-area" autocomplete="off">{{isset($evaluation['sign4']) ? $evaluation['sign4'] : ''}}</textarea></td>
                        </tr>
                        <tr>
                            <td class="gray" style="text-align:left!important;" colspan="5">Company’s evaluate：<input class="grade" data-ref="11" name="qualified" type="checkbox" value="1" @if(isset($evaluation['qualified']) && $evaluation['qualified']==1) checked @endif><label style="margin-top:-5px;">qualified</label></input>&nbsp;&nbsp;&nbsp;&nbsp;<input class="grade" data-ref="11" name="qualified" type="checkbox" value="2" @if(isset($evaluation['qualified']) && $evaluation['qualified']==2) checked @endif><label style="margin-top:-5px;">disqualified</label></input></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-report2">
                    <tbody>
                        <tr class="">
                            <td class="gray" rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Operational<br/>营业部</span></td>
                            <td style="vertical-align:top;"><textarea type="text" name="operational" class="form-control text-left auto-area" style="height:100px;" autocomplete="off">{{isset($evaluation['operational']) ? $evaluation['operational'] : ''}}</textarea></td>
                            <td class="gray" rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">General Affair Dept.<br/>事务部</span></td>
                            <td style="vertical-align:top;"><textarea type="text" name="general_dept" class="form-control text-left auto-area" style="height:100px;" autocomplete="off">{{isset($evaluation['general_dept']) ? $evaluation['general_dept'] : ''}}</textarea></td>
                            <td class="gray" rowspan="4" style="text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(0deg);white-space: nowrap;">Technical Dep<br/>机务部</span></td>
                            <td style="vertical-align:top;"><textarea type="text" name="technical_dept" class="form-control text-left auto-area" style="height:100px;" autocomplete="off">{{isset($evaluation['technical_dept']) ? $evaluation['technical_dept'] : ''}}</textarea></td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>

