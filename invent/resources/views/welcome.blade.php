@include('template.head')

{{ auth()->user()->name }}'s Dashboard

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-primary">Logout</button>
</form>

@include('template.footer')