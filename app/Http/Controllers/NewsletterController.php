<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;
use App\Models\SystemActivity;
use Auth;
use Illuminate\Support\Facades\Crypt;

class NewsletterController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user() && Auth::user()->role_id == 3){
                return redirect()->back()->with('error', 'Access Denied.');
            } else {
                return $next($request);
            }
        });
        $this->middleware('permission:create newsletter users|edit newsletter users|delete newsletter users|publish newsletter users|unpublish newsletter users', ['only'=> ['list']]);
        $this->middleware('permission:create newsletter users', ['only' => ['create']]);
        $this->middleware('permission:edit newsletter users', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete newsletter users', ['only' => ['delete']]);
    }

    public function add(Request $request)
    {
        //	echo "<pre>"; print_r(request()->post()); exit;
        $newsletter = new Newsletter();
        $newsletter->email = $request->email;
        $newsletter->save();
        return response()->json(['inserted' => true]);
    }

    public function list()
    {
        $newsletters = Newsletter::all()->where('is_deleted', 0);

        /**begin: system log**/
        Newsletter::bootSystemView(new Newsletter());
        /**end:  system log**/
        return view('admin/newsletter/NewsletterUserList', ['newsletters' => $newsletters]);
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $newsletters = Newsletter::find($id);

        /**begin: system log**/
        $newsletters->bootSystemView(new Newsletter(), 'Newsletter', SystemActivity::EDITVIEW, $newsletters->id);
        /**end: system log**/
        return view('admin/newsletter/NewsletterUserEdit', ['newsletters' => $newsletters]);
    }

    public function update(Request $request)
    {
        $newsletter = Newsletter::find($request->id);
        $newsletter->email = $request->email;
        $newsletter->updated_by =Auth::id();

        $newsletter->save();
        $newsletters = Newsletter::all()->where('is_deleted', 0);

        /**begin: system log**/
        $newsletter->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true));

        return view('admin/newsletter/NewsletterUserList', ['newsletters' => $newsletters]);
    }
}
