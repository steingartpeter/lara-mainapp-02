<div class="list-group">
  @foreach ($posts as $post)
    <x-post :post="$post" hideAuthor="1"/>
  @endforeach
</div>