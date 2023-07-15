<x-layout>
  
  <div class="container py-md-5 container--narrow">
    <h2>
      <img class="avatar-small" src="{{$sharedData['avatar']}}" /> {{$sharedData['usrNm']}}
      @auth
      @if (!$sharedData['currentlyFollowing'] AND auth()->user()->username != $sharedData['usrNm'])
        <form class="ml-2 d-inline" action="/create-follow/{{$sharedData['usrNm']}}" method="POST">
          @csrf
          <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
        </form>  
      @endif

      @if ($sharedData['currentlyFollowing'])
        <form class="ml-2 d-inline" action="/remove-follow/{{$sharedData['usrNm']}}" method="POST">
          @csrf
          <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>

        </form>  
      @endif
      @if(auth()->user()->username == $sharedData['usrNm'])
      <a href="/manage-avatar" class="btn btn-secondary btn-sm">Manage Avatar</a>
      @endif
          
      @endauth
      
    </h2>

    <div class="profile-nav nav nav-tabs pt-2 mb-4">
      <a href="/profile/{{$sharedData['usrNm']}}" class="profile-nav-link nav-item nav-link {{Request::segment(3) == '' ? 'active':''}}">Posts: {{$sharedData['postCount']}}</a>
      <a href="/profile/{{$sharedData['usrNm']}}/followers" class="profile-nav-link nav-item nav-link {{Request::segment(3) === 'followers'?'active':''}}">Followers: {{$sharedData['followerCount']}}</a>
      <a href="/profile/{{$sharedData['usrNm']}}/following" class="profile-nav-link nav-item nav-link {{Request::segment(3) === 'following' ? 'active':''}}">Following: {{$sharedData['followingCount']}}</a>
    </div>

    <div class="profile-slot-content">
      {{$slot}}
    </div>


  </div>
</x-layout>