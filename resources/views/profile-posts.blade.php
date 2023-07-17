<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['usrNm']}}'s profile">
<div class="list-group">
  @foreach ($posts as $post)
    <x-post :post="$post" hideAuthor="1"/>
  @endforeach
  </div>
</x-profile>