<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ürünler</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
            <div class="mb-4 text-green-700">{{ session('success') }}</div>
                 @endif

            <a href="{{ route('products.create') }}" class="inline-block mb-4 px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
              Yeni Ürün
           </a>
                <table class="min-w-full text-sm text-left">
                    <thead class="border-b font-semibold text-gray-700">
                        <tr>
                            <th class="py-2">SKU</th>
                            <th class="py-2">Ad</th>
                            <th class="py-2">Kategori</th>
                            <th class="py-2">Adet</th>
                            <th class="py-2">Fiyat</th>
                            <th class="py-2">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr class="border-b">
                                <td class="py-2">{{ $product->sku }}</td>
                                <td class="py-2">{{ $product->name }}</td>
                                <td class="py-2">{{ $product->category->name }}</td>
                                <td class="py-2">{{ $product->quantity }}</td>
                                <td class="py-2">{{ number_format($product->price, 2) }}</td>
                                <td class="py-2">
    <a href="{{ route('products.edit', $product) }}" class="text-indigo-600 mr-3">Düzenle</a>
    <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline"
          onsubmit="return confirm('Bu ürünü silmek istediğine emin misin?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600">Sil</button>
    </form>
</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">{{ $products->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>