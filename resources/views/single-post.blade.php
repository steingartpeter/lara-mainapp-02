<x-layout>

    <div class="container py-md-5 container--narrow">
      <div class="d-flex justify-content-between">
        <h2>{{$post->post_title}}</h2>
        @can('update', $post)
        <span class="pt-2">
          <a href="/post/{{$post->id}}/edit" class="text-primary mr-2" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
          <form class="delete-post-form d-inline" action="/post/{{$post->id}}" method="POST">
            @method('DELETE')
            <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>
          </form>
        </span>
        @endcan
      </div>

      <p class="text-muted small mb-4">
        <a href="#"><img class="avatar-tiny" src="{{$post->getUser->avatar}}" /></a>
        Posted by <a href="#">{{$post->getUser->username}}</a> on {{$post->created_at->format('n/j/Y')}} 
      </p>

      <div class="body-content">
        <!-- With this syntax, we can directly output HTML code without sanítizing it... -->
        {!! $post->post_body !!}
      </div>
    </div>

</x-layout>
