<?php

namespace Sanjok\Blog\Http\Controllers;

use File;
use Sanjok\Blog\Models\Slider;
use Sanjok\Blog\Traits\UploaderTrait\UploadTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SliderController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $slider = new Slider;
        $search = $slider->query();
        if ($title = $request->title) {
            $search->where('title', 'like', '%' . $title . '%');
        }
        $sliders = $search->orderBy('id', 'desc')->paginate(10);
        return view(getView('backend.slider.index'), compact('sliders', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = 'create';

        return view(getView('backend.slider.form'), compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sliders = new Slider;
        $sliders->image = $this->upload($request, $folder = 'slider');

        $data = $request->except('_token');
        $data['image'] = $sliders->image;
        $data['user_id'] = Auth::user()->id;

        if ($sliders->create($data)) {
            return redirect(route('slider.index', $request->session()->flash('success', 'Slider Added successfully')));
        } else {
            $request->session()->flash('message', 'failed');
            return redirect(route('slider.index'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        $form = 'edit';
        return view(getView('backend.slider.form'), compact('slider', 'form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        // dd($request);
        $upload_path = '';
        $validatedData = $request->validate([
            'title' => 'required'
        ]);

        if ($request->hasFile('image')) {
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            // get file name
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // get extension
            $extension = $request->file('image')->getClientOriginalExtension();

            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // upload
            $upload_path = $request->file('image')->storeAs('images', $fileNameToStore, 'public_upload');
            $upload_path = 'uploads' . '/' . $upload_path;
            $slider->image = $upload_path;
        }
        $slider->title = $request->input('title');
        $slider->subtitle = $request->input('subtitle');
        $slider->link = $request->input('link');
        $slider->content = $request->input('content');
        $slider->position = $request->input('position');
        $slider->user_id = $request->user()->id;

        $slider->update();
        return redirect()->route('slider.index')->with('success', 'Slider Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        $slider->delete();
        return redirect()->route('slider.index')->with('success', 'Slider Deleted Successfully');
    }
}
