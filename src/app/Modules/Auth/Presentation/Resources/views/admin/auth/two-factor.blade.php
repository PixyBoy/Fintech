<x-admin::layouts.admin>
  <div class="max-w-sm mx-auto mt-10 space-y-3">
    <h1 class="text-xl font-semibold">Two-Factor</h1>
    <x-ui::floating-input label="Code" name="code" wire:model.defer="code" />
    <x-ui::button wire:click="verify">Verify</x-ui::button>
    <p class="text-sm text-gray-500">* کد ۶ رقمی در لاگ‌ها نوشته می‌شود (PoC)</p>
  </div>
</x-admin::layouts.admin>
