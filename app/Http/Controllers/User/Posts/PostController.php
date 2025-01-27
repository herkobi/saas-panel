<?php

namespace App\Http\Controllers\User\Posts;

use App\Actions\User\Post\Create;
use App\Actions\User\Post\Delete;
use App\Actions\User\Post\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Post\PostCreateRequest;
use App\Http\Requests\User\Post\PostUpdateRequest;
use App\Services\User\FeatureService;
use App\Services\User\Post\PostService;
use App\Traits\HasFeatureConsumption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PostController extends Controller
{
    use HasFeatureConsumption;
    protected $postService;
    protected $createPost;
    protected $updatePost;
    protected $deletePost;
    protected $featureName = 'icerik-yonetimi';

    public function __construct(
        PostService $postService,
        Create $createPost,
        Update $updatePost,
        Delete $deletePost
    ) {
        $this->postService = $postService;
        $this->createPost = $createPost;
        $this->updatePost = $updatePost;
        $this->deletePost = $deletePost;
    }

    public function index(): View
    {
        $posts = $this->postService->getAllPosts();
        $remainingPosts = $this->getFeatureUsage('icerik-yonetimi');

        return view('user.posts.index', [
            'posts' => $posts,
            'remainingPosts' => $remainingPosts,
            'hasFeature' => $this->canUseFeature('icerik-yonetimi')
        ]);
    }

    public function create(): View|RedirectResponse
    {
        // Limit kontrolü
        if ($this->getFeatureUsage($this->featureName) <= 0) {
            return redirect()->route('app.posts')
                ->with('error', 'İçerik oluşturma limitinize ulaştınız.');
        }

        return view('user.posts.create');
    }

    public function store(PostCreateRequest $request): RedirectResponse
    {
        $created = $this->createPost->execute($request->validated());
        return $created
                ? Redirect::route('app.posts')->with('success', 'Sayfanız başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Sayfa oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit($id): View
    {
        $post = $this->postService->getPostById($id);
        return view('user.posts.edit', [
            'post' => $post
        ]);
    }

    public function update(PostUpdateRequest $request, $id): RedirectResponse
    {
        $updated = $this->updatePost->execute($id, $request->validated());
        return $updated
                ? Redirect::route('app.posts')->with('success', 'Sayfanız başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Sayfa güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy($id): RedirectResponse
    {
        $deleted = $this->deletePost->execute($id);
        return $deleted
                ? Redirect::route('app.posts')->with('success', 'Sayfanız başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'Sayfa silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
