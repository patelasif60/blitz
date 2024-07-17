<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SubscribedUser;

class AdminUserController extends Controller
{
    public function __construct()
    {
        // admin user
        $this->middleware('permission:create users|edit users|delete users|publish users|unpublish users', ['only' => ['list']]);
        $this->middleware('permission:create users', ['only' => ['create']]);
        $this->middleware('permission:edit users', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete users', ['only' => ['destroy']]);
        // subscribe user
        $this->middleware('permission:create subscribed users|edit subscribed users|delete subscribed users|publish subscribed users|unpublish subscribed users', ['only' => ['subscriberUsersList']]);
        $this->middleware('permission:create subscribed users', ['only' => ['create']]);
        $this->middleware('permission:edit subscribed users', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete subscribed users', ['only' => ['destroy']]);

    }

    function list()
    {
        $user = User::all()->where('is_deleted', 0);
        /**begin: system log**/
        User::bootSystemView(new User());
        /**end:  system log**/
        return view('admin/user/UserList', ['users' => $user]);
    }

    function subscriberUsersList()
    {
        $subscribeUsers = SubscribedUser::all()->where('is_deleted', 0);

        /**begin: system log**/
        SubscribedUser::bootSystemView(new SubscribedUser());
        /**end:  system log**/
        return view('admin/user/SubscribeUserList', ['subscribeUsers' => $subscribeUsers]);
    }

    /**
     * Is user role is JNE
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkRole()
    {
        $authRole = (\Auth::user()->hasRole('jne')) ?  true : false;
        return response()->json(array('success' => $authRole));
    }
}
