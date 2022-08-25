<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Repositories\PostRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Termwind\Components\Raw;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return ResourceCollection
     */
    // * @return \Illuminate\Http\JsonResponse
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 20;
        $posts = Post::query()->paginate($pageSize);

        return PostResource::collection($posts);

        // return new JsonResponse([
        //     'data' => $posts
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return PostResource
     */
    // * @return \Illuminate\Http\JsonResponse
    // * @param  \App\Http\Requests\StorePostRequest  $request
    public function store(Request $request, PostRepository $repository)
    {
        $created = $repository->create($request->only([
            'title', 'body', 'user_ids'
        ]));
        return new PostResource($created);

        // $created = DB::transaction(function () use ($request) {
        //     $created = Post::query()->create([
        //         'title' => $request->title,
        //         'body' => $request->body
        //     ]);

        //     if ($userIds = $request->user_ids) {
        //         $created->users()->sync($userIds);
        //     }
        //     return $created;
        // });
        // return new PostResource($created);

        // $created = Post::query()->create([
        //     'title' => $request->title,
        //     'body' => $request->body
        // ]);
        // return new JsonResponse([
        //     'data' => $created
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return PostResource
     */
    // * @return \Illuminate\Http\JsonResponse
    public function show(Post $post)
    {
        // return new JsonResponse([
        //     'data' => $post
        // ]);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Post  $post
     * @return PostResource | JsonResponse
     */
    // * @return \Illuminate\Http\JsonResponse
    // * @param  \App\Http\Requests\UpdatePostRequest  $request
    public function update(Request $request, Post $post)
    {
        $updated = $post->update([
            'title' => $request->title ?? $post->title,
            'body' => $request->body ?? $post->body
        ]);

        if (!$updated) {
            return new JsonResponse([
                'errors' => [
                    'Failed to update model post.'
                ]
            ], 400);
        }

        return new PostResource($post);

        // return new JsonResponse([
        //     'data' => $post
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post)
    {
        $deleted = $post->forceDelete();

        if (!$deleted) {
            return  new JsonResponse([
                'errors' => [
                    'Failed to delete model post.'
                ]
            ], 400);
        }

        return new JsonResponse([
            'data' => 'post deleted successfully!!'
        ]);
    }
}
