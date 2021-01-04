<?php

namespace Sanjok\Blog\Http\Controllers;



use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Partners;
use App\Traits\UploadTrait;

class PartnersController extends Controller
{
    use UploadTrait;

    protected $partners;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Partners $partners)
    {
        $this->partners = $partners;
    }

    public function index(Request $request)
    {
        $partner = new Partners;

        $search = $partner->query();
        if ($title = $request->title) {
            $search->where('title', 'like', '%' . $title . '%');
        }
        $partners = $search->orderBy('id', 'desc')->paginate(10);
        return view(getView('backend.partners.index'), compact('partners', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = 'create';

        return view(getView('backend.partners.create'), compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');
        $data['image'] = $this->upload($request, $folder = "partners", $fieldName = "image");

        if ($this->partners->create($data)) {
            return redirect(route('partner.index', $request->session()->flash('success', 'Partners Added successfully')));
        } else {
            $request->session()->flash('message', 'failed');
            return redirect(route('partner.index'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->partners->find($id);


        return view(getView("backend.partners.edit"), compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $partners = Partners::find($id);

        $data = $request->except('_token');
        $data['image'] = $this->upload($request, $folder = "partners", $fieldName = "image");

        if ($partners->update($data)) {
            return redirect(route('partner.index', $request->session()->flash('success', 'Partners  Updated successfully')));
        } else {
            return view(getView('backend.partners.edit'), $request->session()->flash('message', "Failed"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $partners = Partners::find($id);
        $partners->delete();
        return redirect(route(('partner.index'), $request->session()->flash('success', 'Partners Deleted Successsfully')));
    }
}
