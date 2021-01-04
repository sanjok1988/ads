<?php

namespace Sanjok\Blog\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Sanjok\Blog\Models\Ad;
use Sanjok\Blog\Contracts\IAds;
use Sanjok\Blog\Models\Category;
use Illuminate\Support\Facades\DB;
use Sanjok\Blog\Repositories\BaseRepository;
use Sanjok\Blog\Traits\UploaderTrait\UploadTrait;

class AdsRepository extends BaseRepository implements IAds
{
    use UploadTrait;

    public function __construct(Ad $model)
    {
        parent::__construct($model);
    }

    public function storeAd(array $data)
    {
        if (isset($data['container_id']))
            $containers = $data['container_id'];

        // @todo make many to many for ad and container
        if (isset($data['is_public']) && $data['is_public'] == "on") {
            $data['is_public'] = true;
        } else {
            $data['is_public'] = false;
        }
        $data['start_at'] = $data['end_at'] = Carbon::now();
        if (isset($data['image']) && is_file($data['image'])) {

            $data['image'] = $this->upload($data['image'], 'ads');
        }
        $data['user_id'] = Auth::user()->id;

        DB::transaction(function () use ($data, $containers) {
            $ad = $this->model->create($data);

            $ad->containers()->sync($containers);
        });
    }
}
