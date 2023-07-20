<x-profile :sharedData="$sharedData" doctitle="Who {{$sharedData['usrNm']}} follows">
@include('profile-following-only')
</x-profile>