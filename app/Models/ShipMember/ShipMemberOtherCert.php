<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/5/25
 * Time: 9:47
 */

namespace App\Models\ShipMember;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShipMemberOtherCert extends Model
{
    protected $table = 'tb_ship_member_othercert';

    public static function insertMemberOtherCert($memberId, $CertName, $CertNo, $CertIssue, $CertExpire, $IssuedBy) {
        $result = 0;

        ShipMemberOtherCert::where('memberId', $memberId)->delete();
        foreach($CertName as $index => $data) {
            if ($CertIssue[$index] == '') $CertIssue[$index] = null;
            if ($CertExpire[$index] == '') $CertExpire[$index] = null;
            ShipMemberOtherCert::insert(['memberId' => $memberId, 'CertName' => $CertName[$index], 'CertNo' => $CertNo[$index], 'IssueDate' => $CertIssue[$index], 'ExpireDate' => $CertExpire[$index], 'IssuedBy' => $IssuedBy[$index]]);
        }
        return $result;
    }

}
