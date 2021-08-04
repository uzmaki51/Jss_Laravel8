<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\ShipManage\ShipRegister;
use App\Models\UserInfo;
use App\Models\Member\Unit;
use App\Models\Member\Post;
use App\Models\User;
use Auth;
use Session;

class ProfileController extends Controller
{
    //

    public function index() {
        $userInfo = Auth::user();
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

        $userid = $userInfo->id;
        $userinfo = User::find($userid);
        $shipList = ShipRegister::orderBy('id')->get();

        return view('profile.index', [   
                    'userid'    =>  $userid,
                    'userinfo'  =>  $userinfo,
                    'units'     =>  $units,
                    'pos'       =>  $posts,
                    'pmenus'    =>  $pmenus,
                    'cmenus'    =>  $cmenus,
                    'state'     =>  $state,
                    'shipList'  =>  $shipList,
                ]);
    }

    public function update(Request $request) {
        $request->validate([
            'name'      => 'required',
        ]);

        $params = $request->all();
        $password = '';
        if(isset($params['password']) && $params['password'] != '') {
            $request->validate([
                'password'          => 'min:6|confirmed'
            ]);

            $password = bcrypt($params['password']);
        }



        $avatar_url = '';
        if($request->file('avatar')) {
            $file = $request->file('avatar');
            $fileName = $file->getClientOriginalName();
            $name = $fileName . '.' . $file->getClientOriginalExtension();
            $file->move(public_path() . '/avatar/', $name);
            $avatar_url = '/avatar/' . $name;
        }

        $userTbl = User::find($params['id']);

        $userTbl['realname'] = $params['name'];
        $userTbl['phone'] = $params['phone'];
        if($avatar_url != '')
            $userTbl['avatar'] = $avatar_url;

        if($password != '')
            $userTbl['password'] = $password;

        $userTbl['remark'] = isset($params['remark']) ? $params['remark'] : '';

        $userTbl->save();

        return redirect('/profile')->with(['message'    => trans('common.message.update.success')]);

    }
}
