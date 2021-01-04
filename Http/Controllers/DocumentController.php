<?php

namespace Sanjok\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Sanjok\Blog\Models\Document;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class DocumentController extends BaseController
{
    public function __construct()
    {
        $this->backend = config('blog.BACKEND_VIEW');
    }


    public function index(Request $request)
    {
        $documents = Document::paginate(10);
        return view($this->getView('documents.index'), compact('documents', 'request'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(), [
            'file' => 'required|mimes:pdf,doc',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $fileNameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            $ext = $file->getClientOriginalExtension();

            $fileNameToStore = $filename.'_'. uniqid()  . '.' . $ext;

            $path = $file->storeAs('documents', $fileNameToStore, 'public_upload');

            $data = [
                'name'=>$fileNameToStore,
                'file'=>'uploads/'.$path,
                'file_type'=>$ext,
                'document_type'=>'official',
                'status'=>'publish'
            ];

            Document::create($data);
        }
        return back();
    }

    public function show($id)
    {
        // You just need to send the contents of the file to the browser and tell it the content type rather than tell the browser to download it.
        $doc = Document::find($id);
        $file = public_path().'/'.$doc->file;
        // TODO show file according to file extension

        // switch ($file->file_type) {
        //     case 'pdf':
        //         $contentType = 'application/pdf';
        //     break;
        // }
        if (is_file($file)) {
            return response()->make(file_get_contents($file), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$doc->name.'"'
            ]);
        } else {
            return back()->with('success', 'File Not Found');
        }
    }

    public function destroy($id)
    {
        $doc = Document::find($id);
        if (!$doc) {
            return back()->with('success', 'Data Not Found');
        }
        if ($doc->delete()) {
            $file = public_path().'/'.$doc->file;
            if (is_file($file)) {
                unlink($file);
                return back()->with('success', 'File Not Found');
            } else {
                return back()->with('success', 'File Not Found');
            }
        } else {
            return back()->with('Process Failed', 'Couldnot delete File');
        }
    }
}
