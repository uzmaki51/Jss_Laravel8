<?php

namespace App\Http\Controllers\Orgmanage;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Util;
use App\Models\Member\Career;
use App\Models\Menu;
use App\Models\ShipManage\ShipRegister;
use App\Models\UserInfo;
use App\Models\Member\Unit;
use App\Models\Member\Post;
use App\Models\Home\Settings;
use App\Models\Home\SettingsSites;
use App\Models\Decision\DecisionReport;
use App\Models\Operations\VoyLog;
use App\Models\User;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Illuminate\Support\Str;

class OrgmanageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }


    public function unitManage(Request $request) {
        Util::getMenuInfo($request);

        $units = Unit::unitList();

        return view('orgmanage.quartermanage', array('units' => $units));
    }

    public function unitDelete(Request $request) {
        $unitId = $request->get('unitId');

        $unit = Unit::find($unitId);
        if(is_null($unit))
            return;

        $unitKey = $unit['orderkey'];

        $unitIds = Unit::select(DB::raw('GROUP_CONCAT(id) as unitIds'))->where('orderkey', 'like', $unitKey.'%')->first();
        if(isset($unitIds)) {
            $idList = explode(',', $unitIds['unitIds']);
            UserInfo::whereIn('unit', $idList)->update(['unit'=> 0]);
        }
        Unit::where('orderkey', 'like', $unitKey.'%')->delete();
        return 'success';
    }

    public function unitUpdate(Request $request) {

        $unitId = $request->get('unitId');
        $unitName = $request->get('unitName');

        $isExist = Unit::where('title', $unitName)->first();
        if(isset($isExist) && ($unitId != $isExist['id']))
            return 'overlay';

        $unit = Unit::find($unitId);
        if(is_null($unit))
            return;

        $unit['title'] = $unitName;
        $unit->save();

        return 'success';
    }

    public function unitRegister(Request $request) {
        $parentId = $request->get('parentId');
        $name = $request->get('unitName');

        $isExist = Unit::where('title', $name)->first();
        
        if($isExist)
            return;
        
        $last = Unit::where('parentId', $parentId)->orderBy('orderkey', 'desc')->first();
        
        if(is_null($last)) {
            $parent = Unit::find($parentId);
            $newKey = $parent['orderkey'].$this->int2keystr(1);
        } else {
            $lastKey = $last['orderkey'];
            $len = strlen($lastKey);
            $subkey = substr($lastKey, $len-3) * 1;
            $parentKey = substr($lastKey, 0, $len-3);
            $newKey = $parentKey.$this->int2keystr($subkey + 1);
        }

        $unit = new Unit();
        $unit['title'] = $name;
        $unit['parentId'] = $parentId;
        $unit['orderkey'] = $newKey;
        $unit->save();

        return 'success';
    }

    public function int2keystr($num) {
        return sprintf("%03d", $num);
    }

    public function keystr2int($str) {
        return number_format($str);
    }

    ///////////////////////////   직위관리  ////////////////////////////
    public function savepost(Request $request) {
        $orderNum = $request->get('orderNum');
        $postname = $request->get('postname');
        $post = new Post;
        $post->orderNum = $orderNum;
        $post->title = $postname;
        $post->save();
        $result = array('result' => "success");
        return json_encode($result);
    }

    public function showpostmanage(Request $request) {
        $GLOBALS['selMenu'] = $request->get('menuId');
        $GLOBALS['submenu'] = 0;

        $posts = Post::orderBy('orderNum')->get();
        $maxLevel = Post::all()->max('orderNum')+1;

        return view('orgmanage.postmanage', array('posts' => $posts, 'maxLevel'=>$maxLevel));
    }

    public function updatepost(Request $request) {
        $post = Post::find($request->id);
        $post->orderNum = $request->orderNum;
        $post->title = $request->title;
        $this->validate($request, [
            'orderNum' => 'required|max:255',
            'title' => 'required',
        ]);
        $post->save();
        $result = array('result' => "success");
        return json_encode($result);
    }

    public function  delpost(Request $request) {
        $post = Post::find($request->id);
        $post->delete();
        $result = array('result' => "success");
        return json_encode($result);
    }

    public function  addpost(Request $request) {
        $this->validate($request, [
            'orderNum' => 'required|max:255',
            'title' => 'required',
        ]);
        $post = new Post;
        $post->orderNum = $request->orderNum;
        $post->title = $request->title;
        $post->save();
        $result = array('result' => "success");
        return json_encode($result);
    }

    public function updateSettings(Request $request) {
        $graph_year = $request->get('select-graph-year');
        $graph_ship = json_encode($request->get('select-graph-ship'));
        $cert_expire_date = $request->get('cert-expire_date');
        $report_year = $request->get('select-report-year');
        $dyn_year = $request->get('select-dyn-year');
        $settings = new Settings();
        //$settings::first()->update(['graph_year'=> $graph_year,'graph_ship'=>$graph_ship,'cert_expire_date'=>$cert_expire_date,'report_year'=>$report_year,'dyn_year'=>$dyn_year]);
        Settings::where('id', 1)->update(['graph_year'=> $graph_year,'graph_ship'=>$graph_ship,'cert_expire_date'=>$cert_expire_date,'report_year'=>$report_year,'dyn_year'=>$dyn_year]);

        $report_ids = $request->get('visible_id');
        $report_values = $request->get('visible_value');
        if (isset($report_ids) && count($report_ids) > 0) {
            foreach($report_ids as $index => $id) {
                DecisionReport::where('report_id', $id)->update(['ishide' => $report_values[$index]]);
            }
        }

        $dyn_ids = $request->get('dyn_id');
        $dyn_values = $request->get('dyn_value');
        if (isset($dyn_ids) && count($dyn_ids) > 0) {
            foreach($dyn_ids as $index => $id) {
                VoyLog::where('id', $id)->update(['ishide' => $dyn_values[$index]]);
            }
        }

        $site_orders = $request->get('site_orders');
        $site_links = $request->get('site_links');
        $site_updates = $request->get('is_update');
        $site_attachments = $request->file('attachment');

        $file_index = 0;
        for ($index=0;$index<10;$index++) {
            $siteTbl = SettingsSites::find($index+1);
            $siteTbl['link'] = $site_links[$index];
            $siteTbl['orderNo'] = $site_orders[$index];

            if($site_updates[$index] == '1') {
                if ($site_attachments[$index] != null) {
                    $file = $site_attachments[$index];
                    $file_index++;
                    $fileName = $file->getClientOriginalName();
                    $name = date('Ymd_His') . '_' . Str::random(10). '.' . $file->getClientOriginalExtension();
                    $file->move(public_path() . '/shipCertList/', $name);
                    $siteTbl['image'] = url('/') . '/shipCertList/' . $name;
                    $siteTbl['image_path'] = public_path('/shipCertList/') . $name;
                }
                else {
                    if (file_exists($siteTbl['image_path'])) {
                        @unlink($siteTbl['image_path']);
                    }
                    $siteTbl['image'] = null;
                    $siteTbl['image_path'] = null;
                }
            }
            $siteTbl->save();
        }

        /*
        foreach($site_orders as $index => $orders) {
            if ($sites_updates[$index] == '1') {
                $file = $site_attachments[$index];
                $fileName = $file->getClientOriginalName();
                $name = date('Ymd_His') . '_' . Str::random(10). '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/shipCertList/', $name);
                if($shipCertTbl['attachment'] != '' && $shipCertTbl['attachment'] != null) {
                    if(file_exists($shipCertTbl['attachment']))
                        @unlink($shipCertTbl['attachment']);
                }

                $shipCertTbl['attachment'] = public_path('/shipCertList/') . $name;
                $shipCertTbl['attachment_link'] = url('/') . '/shipCertList/' . $name;
                $shipCertTbl['file_name'] = $fileName;
            }
        }
        */
        return redirect('org/system/settings');
    }

    public function userInfoListView(Request $request) {
        Util::getMenuInfo($request);

        $unitId = $request->get('unit');
        $pos = $request->get('pos');
        $realname = $request->get('realname');
        $status = $request->get('status');

        $unitList = Unit::all(['id', 'title']);
        $posList = Post::all(['id', 'title']);

        $userlist = User::getSimpleUserList($unitId, $pos, $realname, $status);

        if(isset($unitId))
            $userlist->appends(['unit'=>$unitId]);
        if(isset($pos))
            $userlist->appends(['pos'=>$pos]);
        if(isset($realname))
            $userlist->appends(['realname'=>$realname]);
        
        return view('orgmanage.memberinfo',
                ['list'         =>$userlist,
                'unitList'      =>$unitList,
                'posList'       =>$posList,
                'realname'      =>$realname,
                'unitId'        =>$unitId,
                'posId'         =>$pos,
                'status'        =>$status,
                'type'          => 'edit',
                'realname'      =>$realname
            ]);
    }

    // Go to Personal Info Edit screen
    public function updateMemberinfo(Request $request) {
        $userid = $request->get('userId');

        $userinfo = UserInfo::find($userid);
        $user = User::find($userid);

        return view('org/memberadd',   ['profile'=>$userinfo, 'user'=>$user]);
    }


    public function addMemberinfo(Request $request) {
        $units = Unit::unitFullNameList();
        $posts = Post::orderBy('orderNum')->get();
        $pmenus = Menu::where('parentId', '=', 0)->get();
        $cmenus = array();
        $index = 0;

        $state = Session::get('state');

        foreach ($pmenus as $pmenu) {
			$cmenus[$index] = array();
			$cmenus[$index] = Menu::where('parentId', $pmenu['id'])->orderBy('id')->get();
			$index++;
		}

        $userid = $request->get('uid');
        if(empty($userid)) {
            if(isset($state) && ($state == 'success')) {
                $userid = Session::get('userId');
            }
        }

        $userinfo = User::find($userid);
        $shipList = ShipRegister::getShipListByOrigin();

        return view('orgmanage.addmember',
                [   'userid'    =>  $userid,
                    'userinfo'  =>  $userinfo,
                    'units'     =>  $units,
                    'pos'       =>  $posts,
                    'pmenus'    =>  $pmenus,
                    'cmenus'    =>  $cmenus,
                    'state'     =>  $state,
                    'shipList'  =>  $shipList,
                ]);
    }


    public function updateMember(Request $request) {
        $file = $request->file('photopath');
        if(isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $filename = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/logo'), $filename );
        } else
            $filename = null;

        $userid = $request->get('userid');

        $param = $request->all();

        $account = $param['account'];
        $isUser = User::where('account', $account)->where('id', '<>', $userid)->first();
        if(!is_null($isUser)) {
            $error = "错误!  ID重复了!";
            return back()->with(['state'=> $error]);
        }

        $user = User::find($userid);
	    $user->account = $param['account'];
	    $user->realname = $param['name'];
	    $user->phone = $param['phone'];
        $user->remark = $param['remark'];
	    $user->pos = $param['pos'];
        
	    $user->entryDate = $param['enterdate'] == '' ? null : $param['enterdate'];
	    $releaseDate = $param['releaseDate'];
	    if(!empty($param['enterdate']))
		    $user->entryDate = $param['enterdate'];

	    if(!empty($param['releaseDate'])) {
		    $user->releaseDate = $param['releaseDate'];
		    $user->status = STATUS_BANNED;
	    } else
		    $user->status = STATUS_ACTIVE;

	    $user->isAdmin = (isset($param['isAdmin']) && $param['isAdmin'] == 1) ? 1 : ($param['pos'] == IS_SHAREHOLDER ? IS_SHAREHOLDER : 0);

        if ($param['pos'] == 1) $user->isAdmin = 1;
        else $user->isAdmin = 0;

        if(isset($param['password_reset']) && $param['password_reset'] == true)
	        $user->password = bcrypt(DEFAULT_PASS);

        $this->storePrivilege($request);
        $user->save();

        //return redirect('org/userInfoListView');
        return redirect('org/memberadd?uid='.$user->id);
    }

    public function addMember(Request $request) {
        $file = $request->file('photopath');

        if(isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $filename = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/logo'),$filename );
        } else
            $filename = null;

        $param = $request->all();

        $account = $param['account'];
        $isUser = User::where('account', $account)->first();
        if(!is_null($isUser)) {
            $error = "错误!  用户ID重复!";
            return back()->with(['state'=>$error]);
        }

        $user = new User();
        $user->account = $param['account'];
	    $user->realname = $param['name'];
	    $user->password = bcrypt(DEFAULT_PASS);
	    $user->pos = $param['pos'];
	    $user->phone = $param['phone'];
        $user->remark = $param['remark'];
	    if(!empty($param['enterdate']))
	        $user->entryDate = $param['enterdate'];

	    if(!empty($param['releaseDate'])) {
		    $user->releaseDate = $param['releaseDate'];
		    $user->status = STATUS_BANNED;
	    } else
		    $user->status = STATUS_ACTIVE;

        $user->isAdmin = (isset($param['isAdmin']) && $param['isAdmin'] == 1) ? 1 : ($param['pos'] == IS_SHAREHOLDER ? IS_SHAREHOLDER : 0);
        $user->save();
        $request->merge([
            'userid' => $user->id,
        ]);
        $this->storePrivilege($request);

        return redirect('org/memberadd?uid='.$user->id);
    }


    public function upload(Request $request) {
        $file = $request->files('photo');
        $desdir = '/upload';
        $desfilename = 'tmp';
        $file->move($desdir, $file->getClientOriginalName());
        $photo = $request->get('photo');
        $data = array('result' => "success");
        return json_encode($data);
    }

    public function deleteMember(Request $request) {
    	$params = $request->all();
    	$userid = $params['userid'];
	    $ret = User::where('id', $userid)->delete();
	    //$ret = Career::where('userId', $userid)->delete();
	    //$ret = UserInfo::where('id', $userid)->delete();

    	return response()->json($ret);
    }

	public function userPrivilege(Request $request) {
		Util::getMenuInfo($request);

		$unitId = $request->get('unit');
		$pos = $request->get('pos');
		$realname = $request->get('realname');
		$status = $request->get('status');

		$unitList = Unit::all(['id', 'title']);
		$posList = Post::all(['id', 'title']);

		$userlist = User::getSimpleUserList($unitId, $pos, $realname, $status);

		if(isset($unitId))
			$userlist->appends(['unit'=>$unitId]);
		if(isset($pos))
			$userlist->appends(['pos'=>$pos]);
		if(isset($realname))
			$userlist->appends(['realname'=>$realname]);

		return view('orgmanage.memberinfo_privilege',
			[   'list'          =>$userlist,
				'unitList'      =>$unitList,
				'posList'       =>$posList,
				'realname'      =>$realname,
				'unitId'        =>$unitId,
				'posId'         =>$pos,
				'status'        =>$status,
				'realname'      =>$realname
			]);
	}

	public function addPrivilege(Request $request) {
		$units = Unit::unitFullNameList();
		$posts = Post::orderBy('orderNum')->orderByRaw('CAST(OrderNum AS SIGNED) ASC')->get();
		$pmenus = Menu::where('parentId', '=', 0)->get();
		$cmenus = array();
		$index = 0;

		foreach ($pmenus as $pmenu) {
			$cmenus[$index] = array();
			$cmenus[$index] = Menu::where('parentId', $pmenu['id'])->orderBy('id')->get();
			$index++;
		}

		$state = Session::get('state');

		$userid = $request->get('uid');
		if(empty($userid)) {
			if(isset($state) && ($state == 'success')) {
				$userid = Session::get('userId');
			}
		}

		$userinfo = User::find($userid);
		$shipList = ShipRegister::getShipListByOrigin();

		return view('orgmanage.privilege_manage',
			[   'userid'    =>  $userid,
				'userinfo'  =>  $userinfo,
				'shipList'  =>  $shipList,
				'units'     =>  $units,
				'pos'       =>  $posts,
				'pmenus'    =>  $pmenus,
				'cmenus'    =>  $cmenus,
				'state'     =>  $state
			]);
	}

	// Store privilege status
	public function storePrivilege(Request $request) {
		$param = $request->all();
		$userid = $param['userid'];
		if(User::find($userid) == null)
			return back()->with([
				'state' => '不存在的用户。',
				'userId' => $userid
			]);

		$menus = Menu::all();
		// Privilege Check List
		$allowmenus = '';
		foreach ($menus as $menu) {
			if (isset($param[$menu['id']])) {
				$allowmenus = empty($allowmenus) ? $menu['id'] : $allowmenus .','.$menu['id'];
			}
		}

		$insertData = array();
		$insertData = ['menu'     => $allowmenus];

		if(isset($param['shipList'])) {
			$shipList = $param['shipList'];
			$shipListInfo = '';
			foreach($shipList as $item)
				$shipListInfo .= $item . ',';

			$shipListInfo = substr($shipListInfo, 0, strlen($shipListInfo) - 1);
			$insertData['shipList']  = $shipListInfo;
		}

		$user = new User();
		User::where('id', $userid)->update($insertData);

		return redirect()->back()->with(['state'=>'success', 'userId'=>$userid]);
	}
}