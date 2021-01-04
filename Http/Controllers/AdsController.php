<?php

namespace Sanjok\Blog\Http\Controllers;

use Carbon\Carbon;
use Sanjok\Blog\Models\Ad;
use Illuminate\Http\Request;
use Sanjok\Blog\Contracts\IAds;
use Sanjok\Blog\Models\Category;
use Illuminate\Support\Facades\DB;
use Sanjok\Blog\Models\AdContainer;
use Sanjok\Blog\Contracts\ICategory;
use Sanjok\Blog\Http\Requests\AdStoreRequest;
use Sanjok\Blog\Traits\UploaderTrait\UploadTrait;

class AdsController extends BaseController
{
    use UploadTrait;

    public function __construct(IAds $ad)
    {
        $this->ad = $ad;
    }

    public function index()
    {
        $ads = $this->ad->all();
        return view($this->getView('backend.ads.index'), compact('ads'));
    }

    public function create(Request $request)
    {
        $form = "create";
        $containers = AdContainer::all();
        return view($this->getView('backend.ads.form'), compact('form', 'containers'));
    }

    public function edit(Request $request, Ad $ad)
    {
        $form = "edit";
        $containers = AdContainer::all();
        return view($this->getView('backend.ads.form'), compact('form', 'containers', 'ad'));
    }

    public function store(AdStoreRequest $request)
    {
        $data = $request->only(
            'name',
            'image',
            'container_id',
            'url',
            'is_public',
            'publisher',
            'description',
            'start_at',
            'end_at'
        );
        $this->ad->storeAd($data);

        return redirect(route('ads.create'));
    }

    public function update(Request $request, Ad $ad)
    {
        $data = $request->only(
            'name',
            'image',
            'container_id',
            'url',
            'is_public',
            'publisher',
            'description',
            'start_at',
            'end_at'
        );

        $ad->update($data);
        return redirect(route('ads.create'));
    }
}
