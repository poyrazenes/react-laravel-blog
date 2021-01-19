<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;

use App\Http\Requests\Api\CategoryRequest;

class CategoryController extends BaseController
{
    public function __construct()
    {
        $this->middleware('admin.auth')
            ->except(['index', 'show']);

        parent::__construct();
    }

    public function index()
    {
        $rows = Category::paginate();

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

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = str_slug_tr($data['title']);

        Category::create($data);

        return $this->response->setCode(201)->respond();
    }

    public function show($id)
    {
        $row = Category::findOrfail($id);

        return $this->response->setCode(200)->setData($row)
            ->setMeta(['id' => $id])->respond();
    }

    public function update(CategoryRequest $request, $id)
    {
        $row = Category::findOrfail($id);

        $data = $request->validated();

        $data['slug'] = str_slug_tr($data['title']);

        $row->update($data);

        return $this->response->setCode(204)->respond();
    }

    public function destroy($id)
    {
        $row = Category::findOrFail($id);

        $row->delete();

        return $this->response->setCode(204)->respond();
    }
}
