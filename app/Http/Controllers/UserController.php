<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $users = User::get();

        if ($request->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" id="user-edit" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-secondary">Edit</a>';

                    $btn = $btn . ' <a href="javascript:void(0)" id="user-delete" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('user.index');
    }

    /**
     * Store a newly created/edited user in database.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'regex:pattern', 'unique:users,phone'],
            'gender' => ['required'],
            'image' => ['required', 'image', 'max:2048'],
            'file' => ['nullable'],
        ]);
        if ($validation->fales()) {
            return response()->json([
                'status' => 403,
                'message' => $validation->errors(),
            ]);
        }
        try {
            switch (array_key_exists('id', $request->all()) && isset($request->id)) {

                case true:
                    $user = User::findOrFail($request->id);

                    if ($files = $request->file('image')) {

                        //delete old file
                        File::delete('public/user/image' . $request->hidden_image);

                        //insert new file
                        $destinationPath = 'public/user/image'; // upload path
                        $Image = date('YmdHis') . "." . $files->getClientOriginalExtension();
                        $files->move($destinationPath, $Image);
                        $details['image'] = "$Image";
                    }
                    if ($files = $request->file('file')) {

                        //delete old file
                        File::delete('public/user/file' . $request->hidden_image);

                        //insert new file
                        $destinationPath = 'public/user/file'; // upload path
                        $file = date('YmdHis') . "." . $files->getClientOriginalExtension();
                        $files->move($destinationPath, $file);
                        $details['file'] = "$file";
                    }

                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->phone = $request->phone;
                    $user->gender = $request->gender;
                    $user->image = $details['image'];
                    $user->file = $details['file'];
                    $user->save();

                    return response()->json([
                        'status' => 200,
                        'message' => 'user updated',
                    ]);
                case false:
                    if ($files = $request->file('image')) {

                        //insert new file
                        $destinationPath = 'public/user/image'; // upload path
                        $Image = date('YmdHis') . "." . $files->getClientOriginalExtension();
                        $files->move($destinationPath, $Image);
                        $details['image'] = "$Image";
                    }
                    if ($files = $request->file('file')) {

                        //insert new file
                        $destinationPath = 'public/user/file'; // upload path
                        $file = date('YmdHis') . "." . $files->getClientOriginalExtension();
                        $files->move($destinationPath, $file);
                        $details['file'] = "$file";
                    }
                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'gender' => $request->gender,
                    ]);

                    return response()->json([
                        'status' => 201,
                        'message' => 'user Created',
                    ]);
            }
        } catch (Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error, Try again in some time',
            ]);
        }
    }

    /**
     * Show the data for editing the specified user.
     */
    public function edit(Request $request)
    {
        try {
            $user = user::find($request->id);

            return response()->json([
                'status' => 500,
                'message' => 'Internal server error, Try again in some time',
                'data' => $user
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error, Try again in some time',
            ]);
        }
    }

    /**
     * Remove the specified user from database.
     */
    public function destroy(Request $request)
    {
        try {
            $user = user::find($request->id);
            $user->delete();

            return response()->json([
                'status' => 200,
                'message' => 'user deleted successfully',
                'data' => $user
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error, Try again in some time',
            ]);
        }
    }
}
