<?php

define("REST_TYPE_1","星期日");
define("REST_TYPE_2","节日");
define("REST_TYPE_3","节日");

global $attendData;
$attendData = array('未确定' => 'noResult',
                    '讲习' => 'lecture',
                    '国内出差' => 'interior',
                    '外出' => 'work',
                    '上学' => 'school',
                    '隐私' => 'professional',
                    '出院' => 'hospital',
                    '早退' => 'off',
                    '迟到' => 'late',
                    '海外出差' => 'aboard',
                    '患者' => 'patient',
                    '训练' => 'train',
                    '放假' => 'vocation',
);