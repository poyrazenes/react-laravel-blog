<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;

use App\Http\Requests\Api\PostRequest;

use Illuminate\Support\Facades\Request;

class PostController extends BaseController
{
    public function __construct()
    {
        $this->middleware('admin.auth')
            ->except(['index', 'show']);

        parent::__construct();
    }

    public function index(Request $request)
    {
        $type = $request->input('type', '');

        $posts = Post::with('category', 'user', 'tags')
            ->where('published', true)
            ->orderBy('id', 'DESC');

        if ($type == 'recent') {
            $posts = $posts->take(7)->get();
        } elseif ($type == 'category') {
            $posts = $posts->whereHas('category', function ($query) use ($request) {
                $query->where('id', $request->input('category'));
            })->paginate();
        } elseif ($type == 'tag') {
            $posts = $posts->whereHas('tags', function ($query) use ($request) {
                $query->where('id', $request->input('tag'));
            })->paginate();
        } else {
            $posts = $posts->paginate();
        }

        if ($type != 'recent') {
            $meta = [
                'total' => $posts->total(),
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'path' => $posts->path(),
            ];

            return $this->response->setCode(200)
                ->setData($posts->items())
                ->setMeta($meta)->respond();
        }

        return $this->response->setCode(200)
            ->setData($posts->items())->respond();
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->user()->id;
        $data['slug'] = str_slug_tr($data['title']);
        $data['published'] = false;

        $post = Post::create($data);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $post->image = $filename;
        }

        $post->save();

        return $this->response->setCode(201)->respond();
    }

    public function show($id)
    {
        $row = Post::findOrfail($id);

        return $this->response->setCode(200)->setData($row)
            ->setMeta(['id' => $id])->respond();
    }

    public function update(PostRequest $request, $id)
    {
        $row = Post::findOrfail($id);

        $data = $request->validated();

        $data['user_id'] = auth()->user()->id;
        $data['slug'] = str_slug_tr($data['title']);
        $data['published'] = false;

        $row->update($data);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $row->image = $filename;
        }

        $row->save();

        return $this->response->setCode(204)->respond();
    }

    public function destroy($id)
    {
        $row = Post::findOrFail($id);

        $row->delete();

        return $this->response->setCode(204)->respond();
    }
}
