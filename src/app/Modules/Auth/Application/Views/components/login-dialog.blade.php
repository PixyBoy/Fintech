<div class="space-y-4">
    @if ($step === 1)
        <form wire:submit.prevent="requestOtp" class="space-y-3">
            <label class="block text-sm font-medium text-gray-700">شماره موبایل</label>
            <input type="text" wire:model.defer="phone"
                class="w-full rounded-xl border border-gray-300 px-3 py-2 focus:border-gray-500 focus:outline-none"
                placeholder="0912xxxxxxx" autocomplete="tel" inputmode="numeric" autofocus>
            @error('phone')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button type="submit"
                class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-white hover:bg-blue-700 disabled:opacity-60"
                @disabled($busy)>
                ارسال کد تأیید
            </button>
        </form>
    @endif

    @if ($step === 2)
        <div class="rounded-xl border border-gray-200 p-3 text-sm text-gray-700">
            کد به شماره <span class="font-semibold">{{ $phone }}</span> ارسال شد.
        </div>

        <form wire:submit.prevent="verifyOtp" class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">کد تأیید</label>
                <input type="text" wire:model.defer="code"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2 focus:border-gray-500 focus:outline-none"
                    inputmode="numeric" placeholder="کد ۴ تا ۶ رقمی" autofocus>
                @error('code')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full rounded-xl bg-green-600 px-4 py-2.5 text-white hover:bg-green-700 disabled:opacity-60"
                @disabled($busy)>
                تأیید و ورود
            </button>
        </form>

        <div class="mt-2 flex items-center justify-between text-xs text-gray-600">
            <button wire:click="resend" class="underline underline-offset-2 hover:text-gray-800">
                ارسال مجدد کد
            </button>
            <button wire:click="changePhone" class="underline underline-offset-2 hover:text-gray-800">
                تغییر شماره
            </button>
        </div>
    @endif
</div>
