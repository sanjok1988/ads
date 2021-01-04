<?php

namespace Sanjok\Blog\Http\Controllers;

use stdClass;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Sanjok\Blog\Traits\ImportExportTrait;
use Sanjok\Blog\Traits\UploaderTrait\UploadTrait;

use Sanjok\Blog\Models\Team;
use Sanjok\Blog\Models\Role;

class TeamController extends BaseController
{
    use UploadTrait;
    use ImportExportTrait;

    public function index(Request $request)
    {
        $team = new Team;
        $search = $team->query();

        if ($title = $request->title) {
            $search->where('title', 'like', '%' . $title . '%');
        }

        $teams = $search->orderBy('order', 'ASC')->paginate(10);
        return view($this->getView('backend.team.index'), compact('teams', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = 'create';


        return view(getView('backend.team.form'), compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $team = new Team;

        $team->photo = $this->upload($request, $folder = 'teams', $fieldName = 'photo');

        $team->title = $request->title;
        $team->designation = $request->slug;
        $team->slug = $request->slug;
        $team->fb = $request->fblink;
        $team->linkedin = $request->LinkedIn;
        $team->insta = $request->Instalink;
        $team->gplus = $request->gpluslink;
        $team->content = $request->content;
        $team->order = $request->order;
        $team->homepage = $request->homepage;
        $team->status = $request->status;
        $team->user_id = $request->user()->id;
        $team->save();
        return redirect()->route('team.index')->with('success', 'Team Created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        $form = 'edit';

        return view($this->getView('backend.team.form'), compact('form', 'team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $data = $request->only('fullname', 'designation', 'email', 'address', 'phone', 'content', 'status');

        if ($request->hasFile('photo'))
            $data['photo'] = $this->upload($request->file('photo'), $folder = 'teams', $fieldName = 'photo');

        if ($team->id) {
            Team::whereId($team->id)->update($data);
        }




        return redirect()->route('team.index')->with('success', 'Team Updated Successsfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('team.index')->with('success', 'Team Deleted Successfully');
    }

    public function importCsv(Request $request)
    {
        if ($request->_csv == '') {
            return back()->withErrors(trans('message.csv_must'));
        }
        //dd($request->_csv);
        //dd($request->file('_csv'));
        $cols = ['fullname', 'email', 'address', 'phone', 'photo', 'designation', 'content', 'linkedin', 'fb', 'insta'];

        $extention = ['csv', 'xlsx', 'xls'];
        $origanlExt = $request->file('_csv')->getClientOriginalExtension();
        if (in_array($origanlExt, $extention)) {
            $path = $request->file('_csv')->getRealPath();
            $array = $this->csvToArray($path);

            $result = $this->only($cols, $array);

            Team::insert($result);
            return redirect(route('team.index'))->with('message', trans('message.employee_imported'));
        } else {
            return back()->withErrors(trans('message.format_not_supported'));
            echo "";
        }
    }

    /**
     * Return only given keys from array
     *
     * @param [type] $keys
     * @param [type] $data
     * @return void
     */
    public function only($keys, $data)
    {
        # code...
        $placeholder = new stdClass;
        $results = [];

        foreach ($data as $d) {
            foreach ($keys as $key) {
                $value = data_get($d, $key, $placeholder);
                if ($value !== $placeholder) {
                    $arr[$key] = $value;
                }
            }
            array_push($results, $arr);
        }

        return $results;
    }
}
