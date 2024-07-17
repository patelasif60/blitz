<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use Auth;
class GradesController extends Controller
{
    //
    function list()
    {
        $grades = grade::all()->where('is_deleted', 0);

        return view('admin/gradesList', ['grades' => $grades]);
    }


    function create(Request $request)
    {

        $grade = new Grade;
        $grade->name = $request->name;
        $grade->description = $request->description;
        $grade->status = $request->status;
        $grade->added_by = Auth::id();

        $gradeData = $grade->save();
        return redirect('/admin/grades');
    }

    function edit($id)
    {
        $grade = Grade::find($id);
        if ($grade) {
            return view('/admin/gradeEdit', ['grade' => $grade]);
        } else {
            return redirect('/admin/grades');
        }
    }

    function update(Request $request)
    {
        $grade = Grade::find($request->id);
        $grade->name = $request->name;
        $grade->description = $request->description;
        $grade->status = $request->status;
        $grade->updated_by =Auth::id();

        $gradeData = $grade->save();
        return redirect('/admin/grades');
    }

    function delete(Request $request)
    {
        $grade = Grade::find($request->id);
        $grade->is_deleted = 1;
        $grade->deleted_by = Auth::id();

        $grade->save();
        return $request->id;
    }
}
