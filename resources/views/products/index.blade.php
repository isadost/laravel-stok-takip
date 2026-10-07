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

                <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-2 mb-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ad veya SKU ara"
                           class="border-gray-300 rounded-md shadow-sm text-sm">

                    <select name="category_id" class="border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">Tüm kategoriler</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">Filtrele</button>
                    <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm text-gray-600">Temizle</a>
                </form>

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
                        @forelse ($products as $product)
                            <tr @class(['border-b', 'bg-red-50' => $product->quantity <= $product->min_stock])>
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
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-gray-500">Ürün bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $products->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>