<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;

use App\Http\Requests\Api\CommentRequest;

use Illuminate\Http\Request;

class CommentController extends BaseController
{
    public function __construct()
    {
        $this->middleware('admin.auth')
            ->except(['index', 'store', 'show']);

        parent::__construct();
    }

    public function index(Request $request)
    {
        $post_id = $request->input('post_id');

        $rows = Comment::with('user', 'post')->approved();

        if (!empty($post_id)) {
            $rows->where('post_id', $post_id);
        }

        $rows = $rows->orderBy('id', 'DESC')->paginate();

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

    public function store(CommentRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->user()->id;
        $data['approved'] = false;

        Comment::create($data);

        return $this->response->setCode(201)->respond();
    }

    public function show($id)
    {
        $row = Comment::with('user', 'post')->findOrfail($id);

        return $this->response->setCode(200)->setData($row)
            ->setMeta(['id' => $id])->respond();
    }

    public function update(Request $request, $id)
    {
        $row = Comment::findOrfail($id);

        $is_approved = $request->input('approved', false);

        $row->update(['approved' => $is_approved]);

        return $this->response->setCode(204)->respond();
    }

    public function destroy($id)
    {
        $row = Comment::findOrFail($id);

        $row->delete();

        return $this->response->setCode(204)->respond();
    }
}
