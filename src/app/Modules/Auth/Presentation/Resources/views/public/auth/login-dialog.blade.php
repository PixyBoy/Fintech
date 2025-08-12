<x-ui::dialog :open="$open" name="auth-login" :title="__('Login / Sign up')">
  @if($state === 'phone')
    <div class="space-y-3">
      <x-ui::floating-input label="{{ __('Phone') }}" name="phone" wire:model.defer="phone" />
      <x-ui::button wire:click="requestOtp">{{ __('Send Code') }}</x-ui::button>
    </div>
  @elseif($state === 'otp')
    <div class="space-y-3">
      <x-ui::floating-input label="{{ __('Phone') }}" name="phone" wire:model.defer="phone" />
      <x-ui::floating-input label="{{ __('Code') }}" name="code" wire:model.defer="code" />
      <div class="flex gap-2">
        <x-ui::button wire:click="verify">{{ __('Verify & Login') }}</x-ui::button>
        <x-ui::button variant="secondary" wire:click="requestOtp">{{ __('Resend') }}</x-ui::button>
      </div>
    </div>
  @elseif($state === 'success')
    <p class="text-green-700">{{ __('Logged in successfully.') }}</p>
  @endif
</x-ui::dialog>
