<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kategoriler</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 text-green-700">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 text-red-700">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('categories.store') }}" class="flex gap-2 mb-2">
                    @csrf
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Yeni kategori adı"
                           class="border-gray-300 rounded-md shadow-sm text-sm">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">Ekle</button>
                </form>
                <x-input-error :messages="$errors->get('name')" class="mb-4" />

                <table class="min-w-full text-sm text-left mt-4">
                    <thead class="border-b font-semibold text-gray-700">
                        <tr>
                            <th class="py-2">Ad</th>
                            <th class="py-2">Ürün sayısı</th>
                            <th class="py-2">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr class="border-b">
                                <td class="py-2">{{ $category->name }}</td>
                                <td class="py-2">{{ $category->products_count }}</td>
                                <td class="py-2">
                                    <a href="{{ route('categories.edit', $category) }}" class="text-indigo-600 mr-3">Düzenle</a>
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline"
                                          onsubmit="return confirm('Bu kategoriyi silmek istediğine emin misin?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>