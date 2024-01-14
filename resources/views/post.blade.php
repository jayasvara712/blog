@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-9">
                <h1 class="mb-3">{{ $post->title }}</h1>
                <p>By. <a href="/posts?author={{ $post->author->username }}"
                        class="text-decoration-none">{{ $post->author->name }}</a> in
                    <a href="/posts?category={{ $post->category->slug }}"
                        class="text-decoration-none">{{ $post->category->name }}</a>
                </p>
                <p>
                    <i class="far fa-heart"></i> <span id="likesCount">{{ count($post->likes) }}</span> Likes
                </p>
                @if ($post->image)
                    <div style="max-height: 50rem; overflow:hidden;">
                        <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top"
                            alt="{{ $post->category->name }}" class="image-fluid">
                    </div>
                @else
                    <div style="max-height: 30rem; overflow:hidden;">
                        <img src="https://source.unsplash.com/500x400?{{ $post->category->name }}" class="card-img-top"
                            alt="{{ $post->category->name }}" class="image-fluid">
                    </div>
                @endif
                <article class="my-3 fs-5">
                    {!! $post->body !!}
                </article>
                {{-- <a href="/posts" class="text-decoration-none d-block mt-3">Back to Posts</a> --}}
            </div>
        </div>

        {{-- relate post --}}
        <div class="row justify-content-center mb-3">
            <div class="col-lg-9 ">
                <h4 class="border-bottom">Related Post</h4>
            </div>
        </div>
        <div class="row justify-content-center mb-4">
            @foreach ($relates as $relate)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="position-absolute half-transparent p-2 text-white ">
                            <a href="/posts?category={{ $relate->category->slug }}"
                                class="text-decoration-none text-white">{{ $relate->category->name }}</a>
                        </div>
                        @if ($relate->image)
                            <div style="max-height: 50rem; overflow:hidden;">
                                <img src="{{ asset('storage/' . $relate->image) }}" class="card-img-top"
                                    alt="{{ $relate->category->name }}" class="image-fluid">
                            </div>
                        @else
                            <div style="max-height: 30rem; overflow:hidden;">
                                <img src="https://source.unsplash.com/500x400?{{ $relate->category->name }}"
                                    class="card-img-top" alt="{{ $relate->category->name }}" class="image-fluid">
                            </div>
                        @endif
                        <div class="card-body">
                            <h2><a href="/posts/{{ $relate->slug }}" class="text-decoration-none">{{ $relate->title }}</a>
                            </h2>
                            <p>
                                <small class="text-muted">
                                    By.<a href="/posts?author={{ $relate->author->username }}"
                                        class="text-decoration-none">{{ $relate->author->name }}</a>
                                    {{ $relate->created_at->diffForHumans() }}
                                </small>
                            </p>
                            <p class="card-text">{{ $relate->excerpt }}</p>
                            <a href="/posts/{{ $relate->slug }}" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- comments --}}
        <div class="row justify-content-center mb-3">
            <div class="col-lg-1 border-bottom">
                @auth
                    <button id="likeBtn" data-post-id="{{ $post->id }}" class="bg-transparent border-0">
                        <h5>
                            @if ($like_status)
                                <i class="fas fa-heart" id="stat_true"></i> Like
                            @else
                                <i class="far fa-heart" id="stat_false"></i> Like
                            @endif
                        </h5>
                    </button>
                @else
                    <button class="bg-transparent border-0">
                        <i class="far fa-heart" id="stat_false"></i> Like
                    </button>
                @endauth

            </div>
            <div class="col-lg-5 border-bottom">
                <h4 class=""> Comments</h4>
            </div>
            <div class="col-lg-3 text-end border-bottom">
                @auth
                    <h5>
                        <i class="fas fa-comment"></i> {{ auth()->user()->name }}
                    </h5>
                @else
                    <a class="text-decoration-none" href="/login">
                        <h5>Login</h5>
                    </a>
                @endauth
            </div>
        </div>

        {{-- form comment --}}
        @auth
            <div class="row justify-content-center mb-3">
                <div class="col-lg-9">
                    <form method="POST" action="/comments" class="border border-success border-1 rounded-3 p-3">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $post->slug }}">
                        <div class="">
                            <textarea class="form-control" id="body" name="body" rows="3"></textarea>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary rounded" type="submit">Comments</button>
                        </div>
                    </form>
                </div>
            </div>
        @endauth

        {{-- list comment --}}
        <div class="row justify-content-center mb-3">
            @foreach ($comments as $comment)
                <div class="border border-success border-1 rounded-3 p-3 col-lg-9 mb-3">
                    <div class="col-lg-9">
                        <h5>{{ $comment->user->name }}</h5>
                        <small>{{ $comment->created_at->diffForHumans() }}</small>
                        <p>{{ $comment->body }}</p>
                    </div>
                    @foreach ($comment->replies as $reply)
                        <div class="col-lg-9">
                            <h5>{{ $reply->user->name }}</h5>
                            <small>{{ $reply->created_at->diffForHumans() }}</small>
                            <p>{{ $reply->body }}</p>
                        </div>
                    @endforeach
                    @auth
                        <div class="col-lg-8 mb-3">
                            <button class="show-reply-form text-decoreation-none border-0 bg-transparent"
                                data-comment-id="{{ $comment->id }}"><strong>Reply</strong></button>
                            <!-- Reply form -->
                            <form class="reply-form d-none border border-success border-1 rounded-3 p-3"
                                id="reply-form-{{ $comment->id }}" action="/reply" method="POST">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $comment->id }}">
                                <div class="">
                                    <textarea class="form-control" id="body" name="body" rows="3"></textarea>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-primary rounded" type="submit">Reply</button>
                                </div>
                            </form>
                        </div>
                    @endauth
                </div>
            @endforeach
        </div>
        <div class="row justify-content-center mb-3">
            <div class="col-lg-9">
                {{ $comments->links() }}
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.show-reply-form').on('click', function() {
                // Get the comment ID from the data attribute
                var commentId = $(this).data('comment-id');

                if ($('#reply-form-' + commentId).hasClass('d-none')) {
                    $('#reply-form-' + commentId).removeClass('d-none');
                } else {
                    $('#reply-form-' + commentId).addClass('d-none');
                }
            });

            $('#likeBtn').on('click', function() {
                var postId = $(this).data('post-id');
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: '/posts/' + postId + '/like',
                    data: {
                        _token: CSRF_TOKEN,
                    },
                    success: function(data) {
                        // console.log(data);
                        if ($('#stat_true')) {
                            $('#stat_true').removeClass('fas').addClass(
                                'far');
                        }
                        if ($('#stat_false')) {
                            $('#stat_false').removeClass('far').addClass(
                                'fas');
                        }
                        $('#likesCount').text(data.likes);
                    },
                    error: function(err, e) {
                        for (var x in err) {
                            // console.log(x + " <=> error index of <=> " + err[x])
                        }
                    }
                });
            });
        });
    </script>
@endsection
