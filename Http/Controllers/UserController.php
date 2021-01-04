<?php

namespace Sanjok\Blog\Http\Controllers;

use Auth;
use Sanjok\Blog\Models\Role;
use Sanjok\Blog\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sanjok\Blog\Http\Controllers\BaseController;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = new User;
        $search = $user->query();
        if ($name = $request->title) {
            $search->where('name', 'like', '%' . $name . '%');
        }
        $users = $search->orderBy('id', 'desc')->paginate(10);
        // $users = $search->with('roles')->orderBy('id', 'desc')->paginate(10);
        return view($this->getView('backend.user.index'), compact('users', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = 'create';
        $usersforall = User::all();
        $role = Role::select('name', 'id')->get();
        return view($this->getView('backend.user.form'), compact('form', 'usersforall', 'role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'Biography' => 'required',
        ]);
        // dd($request);
        $user = new User();
        $slug = str_slug($request->slug, '-');

        $user->name = $request->input('name');
        $user->slug = $slug;

        // $user->Biography = $request->Biography;
        $user->email = $request->input('email');
        $user->password = bcrypt($request->password);

        DB::transaction(function () use ($user, $request) {
            if ($role = Role::find($request->single_selection)) {
                $user->assignRole($role->name);
            }

            $user->save();
        }, 5);
        return redirect()->route('user.index')->with('success', 'User Created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $form = 'edit';
        $usersforall = User::all();
        $role = Role::select('name', 'id')->get();
        return view($this->getView('backend.user.form'), compact('user', 'role', 'form', 'usersforall'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        $slug = str_slug($request->slug, '-');
        $user->name = $request->input('name');
        $user->slug = $slug;
        // $user->role_id = $request->single_selection;
        // $user->Biography = $request->Biography;
        $user->email = $request->input('email');
        $user->password = bcrypt($request->password);

        //If the Closure executes successfully, the transaction will automatically be committed.
        //You don't need to worry about manually rolling back or committing while using the transaction method:
        DB::transaction(function () use ($user, $request) {
            if ($role = Role::find($request->single_selection)) {
                $user->assignRole($role->name);
            }

            $user->update();
        }, 5); //Once 5 attempts have been exhausted, an exception will be thrown:

        return redirect('admin/user/' . $request->id . '/edit')->with('success', 'user updated successfully');

        // return redirect()->route('user.index')->with('success', 'User Updated Successsfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User Deleted Successfully');
    }

    public function changeuser(User $user)
    {
        $form = 'edit';
        $modifieduser = Auth::User()->id;
        $user = User::where('id', $modifieduser)->first();
        // echo $user->id;
        return view($this->getView('backend.user.changeuser'), compact('user', 'form'));
    }

    public function modifyuser_Save(Request $request, User $user)
    {
        $id = $request->input('id');
        $user = User::findOrFail($id);
        if ($request->hasFile('feature_image')) {
            $fileNameWithExt = $request->file('feature_image')->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('feature_image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $upload_path = $request->file('feature_image')->storeAs('feature_images', $fileNameToStore, 'public_upload');
            $upload_path = 'uploads' . '/' . $upload_path;

            $user->feature_image = $fileNameToStore;
        }
        $user->name = $request->input('name');
        $user->Biography = $request->Biography;

        if (!$request->input('password') == '') {
            $user->password = bcrypt($request->input('password'));
        }
        $user->update();
        return redirect()->route('user.index')->with('success', 'User Updated Successsfully');
    }
}
