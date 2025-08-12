<x-admin::layouts.admin>
  <div class="max-w-sm mx-auto mt-10 space-y-3">
    <h1 class="text-xl font-semibold">Admin Login</h1>
    <x-ui::floating-input label="Email" name="email" type="email" wire:model.defer="email" />
    <x-ui::floating-input label="Password" name="password" type="password" wire:model.defer="password" />
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" wire:model="remember" class="rounded">
      <span>Remember me</span>
    </label>
    <x-ui::button wire:click="submit">Login</x-ui::button>
  </div>
</x-admin::layouts.admin>
