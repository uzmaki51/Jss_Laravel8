<?php
/**
 * AIOBOT Admin Page : Master data
 * 2020/02/28 Created by H(S
 *
 * @author H(S
 */

define('MIN_TRANSFER_AMOUNT', 0.01);
define('EMPTY_STRING', '');
define('INT_ZERO', 0);
define('STR_ZERO', '0');
define('IS_ERROR', 1);
define('IS_NOT_ERROR', 0);

define('DEFAULT_PASS', '123456');
define('HTTP_METHOD_POST', 'POST');

#UserRole
define('SUPER_ADMIN', 1);
define('EMPTY_DATE', '0000-00-00');

# Status
define('STATUS_BANNED', 0);
define('STATUS_ACTIVE', 1);
$StatusData = array(
    STATUS_BANNED       =>  ['Banned', 'danger'],
    STATUS_ACTIVE       =>  ['Active', 'success'],
);

# ReportTypeData
define('REPORT_TYPE_EVIDENCE_IN',   'Credit');
define('REPORT_TYPE_EVIDENCE_OUT',  'Debit');
define('REPORT_TYPE_CONTRACT',      'Contract');
$ReportTypeData = array(
	REPORT_TYPE_EVIDENCE_OUT        => '支出',
	REPORT_TYPE_CONTRACT            => '合同',
	REPORT_TYPE_EVIDENCE_IN        	=> '收入',
);

# Issuer
define('ISSUER_TYPE_MA', 0);
define('ISSUER_TYPE_RO', 1);
define('ISSUER_TYPE_IC', 2);
define('ISSUER_TYPE_SS', 3);
define('ISSUER_TYPE_EL', 4);
$IssuerTypeData = array(
	ISSUER_TYPE_MA      => 'MA',
	ISSUER_TYPE_RO      => 'RO',
	ISSUER_TYPE_IC      => '保险社',
	ISSUER_TYPE_SS      => '服务站',
	ISSUER_TYPE_EL      => '其他',
);

# FileUpload Status
define('IS_FILE_KEEP',      0);
define('IS_FILE_DELETE',    1);
define('IS_FILE_UPDATE',    2);

# IncomeData
define('INCOME_UNIM',       '1');
define('INCOME_BODY_FEE',   '2');
define('INCOME_ELSE',       '3');
$InComeData = array(
	INCOME_UNIM         => '运费/租费',
	INCOME_BODY_FEE     => '其他收入',
	INCOME_ELSE         => '滞期费',
);

# OutcomeData
define('OUTCOME_FEE1',       '1');
define('OUTCOME_FEE2',       '2');
define('OUTCOME_FEE3',       '3');
define('OUTCOME_FEE4',       '4');
define('OUTCOME_FEE5',       '5');
define('OUTCOME_FEE6',       '6');
define('OUTCOME_FEE7',       '7');
define('OUTCOME_FEE8',       '8');
define('OUTCOME_FEE9',       '9');
define('OUTCOME_FEE10',       '10');
define('OUTCOME_FEE11',       '11');
define('OUTCOME_FEE12',       '12');
define('OUTCOME_FEE13',       '13');
define('OUTCOME_FEE14',       '14');
define('OUTCOME_FEE15',       '15');
define('OUTCOME_FEE16',       '16');
define('OUTCOME_FEE17',       '17');

$OutComeData = array(
	OUTCOME_FEE1    	=> '港费',
	OUTCOME_FEE2    	=> '油款',
	OUTCOME_FEE3    	=> '工资',
	OUTCOME_FEE4    	=> 'CTM',
	OUTCOME_FEE5    	=> '伙食费',
	OUTCOME_FEE6    	=> '劳务费',
	OUTCOME_FEE7    	=> '物料费',
	OUTCOME_FEE8    	=> '修理费',
	OUTCOME_FEE9    	=> '管理费',
	OUTCOME_FEE10    	=> '保险费',
	OUTCOME_FEE11    	=> '检验费',
	OUTCOME_FEE12    	=> '证书费',
	OUTCOME_FEE13		=> '备件',
	OUTCOME_FEE14		=> '滑油',
	OUTCOME_FEE15    	=> '办公费',
	OUTCOME_FEE16    	=> '兑换',
	OUTCOME_FEE17    	=> '其他费',
);

$OutComeData1 = array(
	OUTCOME_FEE1    	=> '港费',
	OUTCOME_FEE2    	=> '油款',
	OUTCOME_FEE3    	=> '工资',
	OUTCOME_FEE4    	=> 'CTM',
	OUTCOME_FEE5    	=> '伙食费',
	OUTCOME_FEE6    	=> '劳务费',
	OUTCOME_FEE7    	=> '物料费',
	OUTCOME_FEE8    	=> '修理费',
	OUTCOME_FEE9    	=> '管理费',
	OUTCOME_FEE10    	=> '保险费',
	OUTCOME_FEE11    	=> '检验费',
	OUTCOME_FEE12    	=> '证书费',
	OUTCOME_FEE13		=> '备件',
	OUTCOME_FEE14		=> '滑油',
	OUTCOME_FEE17    	=> '其他费',
);

$OutComeData2 = array(
	OUTCOME_FEE15    	=> '办公费',
	OUTCOME_FEE16    	=> '兑换',
	OUTCOME_FEE17    	=> '其他费',
);

$FeeTypeData = array(
	REPORT_TYPE_CONTRACT           => [],
	REPORT_TYPE_EVIDENCE_OUT       => $OutComeData,
	REPORT_TYPE_EVIDENCE_IN        => $InComeData,
);
# StaffLevelData
define('STAFF_LEVEL_MANAGER',       	1);
define('STAFF_LEVEL_OLDDER',            2);
define('STAFF_LEVEL_OPERATOR',        	3);
define('STAFF_LEVEL_ENGINEER',        	4);
define('STAFF_LEVEL_SEAMAN',        	5);
define('STAFF_LEVEL_FINANCIAL',        	6);
define('STAFF_LEVEL_OTHER',	        	7);
define('STAFF_LEVEL_CAPTAIN',	        8);
define('STAFF_LEVEL_SHAREHOLDER',      	100);

$StaffLevelData = array(
	STAFF_LEVEL_MANAGER          	=> ['总经理', 'danger'],
	STAFF_LEVEL_OLDDER            	=> ['老轨',   'info'],
	STAFF_LEVEL_OPERATOR      		=> ['业务经理',   'danger'],
	STAFF_LEVEL_ENGINEER        	=> ['机务经理',   'warning'],
	STAFF_LEVEL_SEAMAN        		=> ['海员经理',   'info'],
	STAFF_LEVEL_FINANCIAL        	=> ['财务经理',   'success'],
	STAFF_LEVEL_OTHER	        	=> ['其他',   'primary'],
	STAFF_LEVEL_CAPTAIN	        	=> ['船长',   'danger'],
	STAFF_LEVEL_SHAREHOLDER        	=> ['股东',   'secondary'],
);


# ReportTypeData
define('REPORT_EVIDENCE_OUT',   2);
define('REPORT_EVIDENCE_IN',    1);
define('REPORT_CONTACT',        3);
define('REPORT_OTHER',          4);
$ReportTypeLabelData = array(
	REPORT_EVIDENCE_IN      => ['支出',      'danger'],
	REPORT_EVIDENCE_OUT     => ['收入',       'info'],
	REPORT_CONTACT          => ['合同',    'primary'],
	REPORT_OTHER            => ['其他',    'secondary'],
);

define('CNY_LABEL', 'CNY');
define('USD_LABEL', 'USD');
define('EUR_LABEL', 'OTHER');
$CurrencyLabel = array(
	CNY_LABEL   =>  '¥',
	USD_LABEL   =>  '$',
	EUR_LABEL   =>  'C',
);

#Inventory Status Data
define('INVENTORY_STATUS_UNKNOWN',  0);
define('INVENTORY_STATUS_NEW',      1);
define('INVENTORY_STATUS_RECYCLE',  2);
define('INVENTORY_STATUS_OLD',      3);
$InventoryStatusData = array(
	INVENTORY_STATUS_UNKNOWN        => ['未定',       'secondary'],
	INVENTORY_STATUS_NEW            => ['新品',       'primary'],
	INVENTORY_STATUS_RECYCLE        => ['再生',       'info'],
	INVENTORY_STATUS_OLD            => ['二手',       'danger'],
);

#TermListData
define('TERM_MONTH_1_IN',   0);
define('TERM_MONTH_1',      1);
define('TERM_MONTH_3',      3);
define('TERM_MONTH_6',      6);
define('TERM_YEAR_1',       12);
define('TERM_YEAR_PLUS',    '+');
$TermData = array(
	TERM_MONTH_1_IN =>  ['1月以内',       'primary'],
	TERM_MONTH_1    =>  ['1月以上',       'primary'],
	TERM_MONTH_3    =>  ['3月以上',       'primary'],
	TERM_MONTH_6    =>  ['6月以上',       'info'],
	TERM_YEAR_1     =>  ['1年以上',       'danger'],
);


# Employee Status
define('EMPLOYEE_STATUS_RETIREMENT',    0);
define('EMPLOYEE_STATUS_WORK',          1);
$EmployeeStatusData = array(
	EMPLOYEE_STATUS_RETIREMENT      => ['卸任', 'secondary'],
	EMPLOYEE_STATUS_WORK            => ['登录', 'primary']
);

# Accident Status
define('ACCIDENT_TYPE_RUNGROUND', 	1);
define('ACCIDENT_TYPE_CLASH', 		2);
define('ACCIDENT_TYPE_BREAKDOWN', 	3);
define('ACCIDENT_TYPE_LOSE', 		4);
define('ACCIDENT_TYPE_SHORTAGE', 	5);
$AccidentTypeData = array(
	ACCIDENT_TYPE_RUNGROUND		=> ['搁浅', 'primary'],
	ACCIDENT_TYPE_CLASH			=> ['冲突', 'info'],
	ACCIDENT_TYPE_BREAKDOWN		=> ['故障破损', 'warning'],
	ACCIDENT_TYPE_LOSE			=> ['丢失', 'secondary'],
	ACCIDENT_TYPE_SHORTAGE		=> ['货物不足', 'danger'],
);

# ShipTypeData
define('SHIP_TYPE_A_1', 1);
define('SHIP_TYPE_B_1', 2);
define('SHIP_TYPE_B_2', 3);
define('SHIP_TYPE_B_3', 4);
$ShipTypeData = array(
	SHIP_TYPE_A_1		=> 'Type "A"',
	SHIP_TYPE_B_1		=> 'Type "B"',
	SHIP_TYPE_B_2		=> 'Type "B" with reduced freeboard',
	SHIP_TYPE_B_3		=> 'Type "B" with increased freeboard',
);

# ShipRegStatusType
define('SHIP_REG_STATUS_PRO', 1);
define('SHIP_REG_STATUS_PER', 2);
define('SHIP_REG_STATUS_DEL', 3);
$ShipRegStatus = array(
	SHIP_REG_STATUS_PRO     => 'PRO',
	SHIP_REG_STATUS_PER     => 'PER',
	SHIP_REG_STATUS_DEL     => 'DEL',
);

# ReportStatusData
define('REPORT_STATUS_REQUEST',     0);
define('REPORT_STATUS_ACCEPT',      1);
define('REPORT_STATUS_REJECT',      2);
define('REPORT_STATUS_DRAFT',       3);

$ReportStatusData = array(
	REPORT_STATUS_REQUEST   => ['等待', 'primary'],
	REPORT_STATUS_ACCEPT    => ['通过', 'info'],
	REPORT_STATUS_REJECT    => ['否决', 'secondary'],
);

$NationalityData = array(
	0      => 'BANGLADESH',
	1      => 'CHINESE',
	2      => 'MYANMAR',
);


# CPTypeData
define('CP_TYPE_VOY',   'VOY');
define('CP_TYPE_TC',    'TC');
$CPTypeData = array(
	CP_TYPE_VOY     => 'VOY',
	CP_TYPE_TC      => 'TC'
);

define('QTY_TYPE_MOLOO', 1);
define('QTY_TYPE_MOLCO', 2);
$QtyTypeData = array(
	QTY_TYPE_MOLOO		=> 'MOLOO',
	QTY_TYPE_MOLCO		=> 'MOLCO'
);
define('BANK_TYPE_0', 0);
define('BANK_TYPE_1', 1);
define('BANK_TYPE_2', 2);
$BankData = array(
	BANK_TYPE_0		=> '农行',
	BANK_TYPE_1		=> '华夏',
	BANK_TYPE_2		=> '大连'
);

define('PAY_TYPE_0', 0);
define('PAY_TYPE_1', 1);
define('PAY_TYPE_2', 2);
define('PAY_TYPE_3', 3);
$PayTypeData = array(
	PAY_TYPE_0		=> '汇款',
	PAY_TYPE_1		=> '现钞',
	PAY_TYPE_2		=> '扣除',
	PAY_TYPE_3		=> '转账',
);


define('DYNAMIC_SUB_ELSE', 			1);
define('DYNAMIC_SUB_WEATHER', 		2);
define('DYNAMIC_SUB_SUPPLY', 		3);
define('DYNAMIC_SUB_REPAIR', 		4);
define('DYNAMIC_SUB_WAITING', 		5);
define('DYNAMIC_SUB_LOADING', 		6);
define('DYNAMIC_SUB_SALING', 		7);
define('DYNAMIC_SUB_DISCH', 		8);

$DynamicSub = array(
	DYNAMIC_SUB_ELSE		=> '其他',
	DYNAMIC_SUB_WEATHER		=> '天气',
	DYNAMIC_SUB_SUPPLY		=> '供应',
	DYNAMIC_SUB_REPAIR		=> '修理',
	DYNAMIC_SUB_WAITING		=> '待泊',
	DYNAMIC_SUB_LOADING		=> '装货',
	DYNAMIC_SUB_SALING		=> '航行',
	DYNAMIC_SUB_DISCH		=> '卸货',
);

# Dynamic Status
define('DYNAMIC_DEPARTURE', 		1);
define('DYNAMIC_SAILING', 			2);
define('DYNAMIC_ANCHORING', 		3);
define('DYNAMIC_ARRIVAL', 			4);
define('DYNAMIC_WAITING', 			5);
define('DYNAMIC_BERTH', 			6);
define('DYNAMIC_UNBERTH', 			7);
define('DYNAMIC_CMNC', 				8);
define('DYNAMIC_LOADING', 			9);
define('DYNAMIC_CMPLT_LOADING', 	10);
define('DYNAMIC_CMNC_DISCH', 		11);
define('DYNAMIC_DISCHARG', 			12);
define('DYNAMIC_CMPLT_DISCH', 		13);
define('DYNAMIC_STOP', 				14);
define('DYNAMIC_RESUME', 			15);
define('DYNAMIC_DOCKING', 			16);
define('DYNAMIC_UNDOCKING', 		17);
define('DYNAMIC_POB', 				18);
define('DYNAMIC_VOYAGE', 			19);

$DynamicStatus = array(
	DYNAMIC_DEPARTURE 			=> ['DEPARTURE', [
			DYNAMIC_SUB_ELSE, 
			DYNAMIC_SUB_WEATHER, 
			DYNAMIC_SUB_SUPPLY, 
			DYNAMIC_SUB_REPAIR
		]],
	DYNAMIC_SAILING 			=> ['SAILING', [
			DYNAMIC_SUB_SALING
	]],
	DYNAMIC_ANCHORING 			=> ['ANCHORING', [
			DYNAMIC_SUB_SALING,
			DYNAMIC_SUB_WEATHER,
			DYNAMIC_SUB_SUPPLY,
			DYNAMIC_SUB_LOADING,
			DYNAMIC_SUB_REPAIR,
			DYNAMIC_SUB_DISCH,
			DYNAMIC_SUB_WAITING,
			DYNAMIC_SUB_ELSE
	]],
	DYNAMIC_ARRIVAL 			=> ['ARRIVAL', [
		DYNAMIC_SUB_SALING
	]],
	DYNAMIC_WAITING 			=> ['WAITING', [
		DYNAMIC_SUB_WEATHER, 
		DYNAMIC_SUB_SUPPLY, 
		DYNAMIC_SUB_REPAIR,
		DYNAMIC_SUB_LOADING,
		DYNAMIC_SUB_DISCH,
		DYNAMIC_SUB_WAITING,
		DYNAMIC_SUB_ELSE
	]],
	DYNAMIC_POB 				=> ['POB', [
		DYNAMIC_SUB_WAITING,
		DYNAMIC_SUB_SALING,
	]],
	DYNAMIC_BERTH 				=> ['BERTH', [
		DYNAMIC_SUB_SALING,
		DYNAMIC_SUB_WAITING,
	]],
	DYNAMIC_UNBERTH 			=> ['UNBERTH', [
			DYNAMIC_SUB_ELSE
	]],
	DYNAMIC_CMNC 				=> ['CMNC LOADING', [
			DYNAMIC_SUB_ELSE,
			DYNAMIC_SUB_WAITING
	]],
	DYNAMIC_LOADING 			=> ['LOADING', [
		DYNAMIC_SUB_LOADING
	]],
	DYNAMIC_CMPLT_LOADING 		=> ['CMPLT LOADING', [
		DYNAMIC_SUB_LOADING
	]],
	DYNAMIC_CMNC_DISCH 			=> ['CMNC DISCH', [
		DYNAMIC_SUB_ELSE,
		DYNAMIC_SUB_WAITING,
	]],
	DYNAMIC_DISCHARG 			=> ['DISCHARG', [
		DYNAMIC_SUB_DISCH,
	]],
	DYNAMIC_CMPLT_DISCH 			=> ['CMPLT DISCH', [
		DYNAMIC_SUB_DISCH,
	]],
	DYNAMIC_STOP 				=> ['STOP', [
		DYNAMIC_SUB_LOADING,
		DYNAMIC_SUB_DISCH
	]],
	DYNAMIC_RESUME 				=> ['RESUME', [
		DYNAMIC_SUB_ELSE, 
		DYNAMIC_SUB_WEATHER, 
		DYNAMIC_SUB_SUPPLY, 
		DYNAMIC_SUB_REPAIR,
		DYNAMIC_SUB_WAITING
	]],
	DYNAMIC_DOCKING 			=> ['DOCKING', [
		DYNAMIC_SUB_REPAIR,
		DYNAMIC_SUB_ELSE
	]],
	DYNAMIC_UNDOCKING 			=> ['UNDOCKING', [
		DYNAMIC_SUB_REPAIR
	]],
	DYNAMIC_VOYAGE 			=> ['CMPLT VOYAGE', [
		DYNAMIC_SUB_ELSE
	]],
);

define('PROFIT_TYPE_1', 1);
define('PROFIT_TYPE_2', 2);
define('PROFIT_TYPE_3', 3);
define('PROFIT_TYPE_4', 4);
define('PROFIT_TYPE_5', 5);
define('PROFIT_TYPE_6', 6);
define('PROFIT_TYPE_7', 7);
define('PROFIT_TYPE_8', 8);
define('PROFIT_TYPE_9', 9);
define('PROFIT_TYPE_10', 10);
define('PROFIT_TYPE_11', 11);
define('PROFIT_TYPE_12', 12);
define('PROFIT_TYPE_13', 13);
define('PROFIT_TYPE_14', 14);

$ProfitTypeData = array(
	PROFIT_TYPE_1		=> '接收',
	PROFIT_TYPE_2		=> '伙食费',
	PROFIT_TYPE_3		=> '劳务费',
	PROFIT_TYPE_4		=> '娱乐费',
	PROFIT_TYPE_5		=> '物料费',
	PROFIT_TYPE_6		=> '招待费',
	PROFIT_TYPE_7		=> '奖励',
	PROFIT_TYPE_8		=> '小费',
	PROFIT_TYPE_9		=> '修理费',
	PROFIT_TYPE_10		=> '证书费',
	PROFIT_TYPE_11		=> '通信费',
	PROFIT_TYPE_12		=> '其他',
	PROFIT_TYPE_13		=> '备件',
	PROFIT_TYPE_14		=> '滑油',
);

$ProfitDebitData = array(
	PROFIT_TYPE_2		=> '伙食费',
	PROFIT_TYPE_3		=> '劳务费',
	PROFIT_TYPE_4		=> '娱乐费',
	PROFIT_TYPE_5		=> '物料费',
	PROFIT_TYPE_6		=> '招待费',
	PROFIT_TYPE_7		=> '奖励',
	PROFIT_TYPE_8		=> '小费',
	PROFIT_TYPE_9		=> '修理费',
	PROFIT_TYPE_10		=> '证书费',
	PROFIT_TYPE_11		=> '通信费',
	PROFIT_TYPE_12		=> '其他',
	PROFIT_TYPE_13		=> '备件',
	PROFIT_TYPE_14		=> '滑油',
);

define('ZERO_DATE', '0000-00-00');

define('OBJECT_TYPE_SHIP', 		'1');
define('OBJECT_TYPE_PERSON', 	'2');


define('PLACE_TYPE_DECK', 		1);
define('PLACE_TYPE_ENGINE', 	2);
define('PLACE_TYPE_ELSE', 		3);

$PlaceType = array(
	PLACE_TYPE_DECK			=> '甲板',
	PLACE_TYPE_ENGINE		=> '机舱',
	PLACE_TYPE_ELSE			=> '其他',
);

define('VARIETY_TYPE_PRE', 					1);
define('VARIETY_TYPE_SPARE', 				2);
define('VARIETY_TYPE_MAINFRAME', 			3);
define('VARIETY_TYPE_AX_MACHINE', 			4);
define('VARIETY_TYPE_BOILER', 				5);
define('VARIETY_TYPE_DEVICE', 				6);
define('VARIETY_TYPE_TOOLS', 				7);
define('VARIETY_TYPE_ELSE', 				8);

$VarietyType = array(
	VARIETY_TYPE_PRE			=> 	'备用品',
	VARIETY_TYPE_SPARE 			=> 	'备件',
	VARIETY_TYPE_MAINFRAME 		=>	'主机',
	VARIETY_TYPE_AX_MACHINE 	=>	'辅机',
	VARIETY_TYPE_BOILER 		=>	'锅炉',
	VARIETY_TYPE_DEVICE 		=>	'机械',
	VARIETY_TYPE_TOOLS 			=>	'工具',
	VARIETY_TYPE_ELSE 			=>	'其他',
);

$UnitData = array(
	'PCS', 'SET', 'BTL', 'BOX', 'M', 'MT', 'kg', 'DRM', 'SHT', 'ROL', 'LTR', '㎡', 'm³', '㎠', ' ㎤'
);

define('VOY_SETTLE_ORIGIN', 'ORIGIN');
define('VOY_SETTLE_LOAD', 	'LOAD');
define('VOY_SETTLE_DIS', 	'DIS');
define('VOY_SETTLE_FUEL', 	'FUEL');

define('USER_POS_ACCOUNTER', 8);

define('REPAIR_STATUS_ALL', 			0);
define('REPAIR_STATUS_UNCOMPLETE', 	1);
define('REPAIR_STATUS_COMPLETE', 		2);

define('REPAIR_REPORT_TYPE_DEPART', 	1);
define('REPAIR_REPORT_TYPE_CHARGE', 	2);
define('REPAIR_REPORT_TYPE_TYPE', 		3);

 $g_masterData = array(
 	    'ReportTypeData'	            => $ReportTypeData,
	    'ReportTypeLabelData'	        => $ReportTypeLabelData,
	    'StaffLevelData'	            => $StaffLevelData,
	    'InComeData'                    => $InComeData,
        'OutComeData'                   => $OutComeData,
		'OutComeData1'                  => $OutComeData1,
		'OutComeData2'                  => $OutComeData2,
	    'FeeTypeData'                   => $FeeTypeData,
	    'CurrencyLabel'                 => $CurrencyLabel,
	    'InventoryStatusData'           => $InventoryStatusData,
	    'TermData'                      => $TermData,
		'EmployeeStatusData'            => $EmployeeStatusData,
		'AccidentTypeData'            	=> $AccidentTypeData,
		'ShipTypeData'					=> $ShipTypeData,
	    'ShipRegStatus'					=> $ShipRegStatus,
	    'ReportStatusData'			    => $ReportStatusData,
		'NationalityData'				=> $NationalityData,
	    'IssuerTypeData'				=> $IssuerTypeData,
		'CPTypeData'				    => $CPTypeData,
	    'QtyTypeData'				    => $QtyTypeData,	
		'BankData'				    	=> $BankData,
		'PayTypeData'					=> $PayTypeData,
		'DynamicStatus'				    => $DynamicStatus,
		'DynamicSub'					=> $DynamicSub,
		'ProfitTypeData'				=> $ProfitTypeData,
		'ProfitDebitData'				=> $ProfitDebitData,
		'PlaceType'						=> $PlaceType,
		'VarietyType'					=> $VarietyType,
		'UnitData'						=> $UnitData,

 );
