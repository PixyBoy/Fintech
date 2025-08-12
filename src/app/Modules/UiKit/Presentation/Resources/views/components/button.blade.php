@props(['variant'=>'primary'])
<button {{ $attributes->merge([
  'class' => 'inline-flex items-center rounded-lg px-4 py-2 font-medium shadow '.
             ($variant === 'primary' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-100 hover:bg-gray-200')
]) }}>
  {{ $slot }}
</button>
