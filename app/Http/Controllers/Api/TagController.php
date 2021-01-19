<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;

use App\Http\Requests\Api\TagRequest;

class TagController extends BaseController
{
    public function __construct()
    {
        $this->middleware('admin.auth')
            ->except(['index', 'show']);

        parent::__construct();
    }

    public function index()
    {
        $rows = Tag::paginate();

        $meta = [
            'total' => $rows->total(),
            'current_page' => $rows->currentPage(),
            'last_page' => $rows->lastPage(),
            'per_page' => $rows->perPage(),
            'path' => $rows->path(),
        ];

        return $this->response->setCode(200)
            ->setData($rows->items())
            ->setMeta($meta)->respond();
    }

    public function store(TagRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = str_slug_tr($data['title']);

        Tag::create($data);

        return $this->response->setCode(201)->respond();
    }

    public function show($id)
    {
        $row = Tag::findOrfail($id);

        return $this->response->setCode(200)->setData($row)
            ->setMeta(['id' => $id])->respond();
    }

    public function update(TagRequest $request, $id)
    {
        $row = Tag::findOrfail($id);

        $data = $request->validated();

        $data['slug'] = str_slug_tr($data['title']);

        $row->update($data);

        return $this->response->setCode(204)->respond();
    }

    public function destroy($id)
    {
        $row = Tag::findOrFail($id);

        $row->delete();

        return $this->response->setCode(204)->respond();
    }
}
