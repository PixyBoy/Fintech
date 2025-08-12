<nav class="border-b bg-white">
  <div class="container mx-auto flex items-center justify-between p-3">
    <a href="{{ route('home') }}" class="font-bold">MyApp</a>

    <div class="flex items-center gap-2">
      @auth('web')
        <span class="text-sm">{{ auth('web')->user()->name ?? auth('web')->user()->phone }}</span>
        <form method="POST" action="{{ route('auth.web.logout') }}">
          @csrf
          <x-ui-button variant="secondary">Logout</x-ui-button>
        </form>
      @else
        <x-ui-button x-on:click="$dispatch('open-dialog',{name:'auth-login'})">Login / Sign up</x-ui-button>
      @endauth
    </div>
  </div>
</nav>
