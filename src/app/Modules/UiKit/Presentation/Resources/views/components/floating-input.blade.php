@props(['label'=>'','name'=>'','type'=>'text'])
<label class="relative block">
  <input {{ $attributes->merge([
    'name'=>$name, 'type'=>$type,
    'class'=>'peer w-full rounded-lg border px-3 py-2 outline-none focus:ring'
  ]) }}/>
  <span class="pointer-events-none absolute -top-2 left-2 bg-white px-1 text-xs text-gray-500">
    {{ $label }}
  </span>
</label>
