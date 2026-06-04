<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <title>Products</title>
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 font-sans" x-data="{ openAdd: false, openEdit: false, openDelete: false, currentProduct: { name: 'Coca Cola', code: 'PRO-001', qty: 24, category: 'Drinks' } }">

    <div class="flex min-h-screen">
        <!-- Sidebar (Same as Dashboard) -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold text-center border-b border-slate-800">
                <span class="text-blue-400">POS</span> System
            </div>
            <nav class="flex-1 mt-6 px-4 space-y-2">
                <a href="/dashboard"
                    class="flex items-center px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg transition">
                    <i class="fa-solid fa-chart-line mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/products" class="flex items-center px-4 py-3 bg-blue-600 text-white rounded-lg transition">
                    <i class="fa-solid fa-box mr-3"></i>
                    <span>Products</span>
                </a>
                <a href="/categories"
                    class="flex items-center px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg transition">
                    <i class="fa-solid fa-layer-group mr-3"></i>
                    <span>Categories</span>
                </a>
            </nav>
            <div class="p-4 border-t border-slate-800">
                <a href="/logout"
                    class="flex items-center w-full px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-lg transition">
                    <i class="fa-solid fa-right-from-bracket mr-3"></i>
                    <span class="font-semibold">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Header (Same as Dashboard) -->
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8">
                <h1 class="text-xl font-semibold text-gray-800">Manage Products</h1>
                <div class="flex items-center space-x-4">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff" alt="Profile"
                        class="w-10 h-10 rounded-full border">
                </div>
            </header>

            <!-- Content Area -->
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-gray-700">All Products</h2>
                    <button @click="openAdd = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center">
                        <i class="fa-solid fa-plus mr-2"></i> Add New Product
                    </button>
                </div>
                <form action="/products/export" method="GET" class="flex gap-3 items-center mb-4">

                    <input type="date" name="from_date" class="border px-3 py-2 rounded-lg">

                    <input type="date" name="to_date" class="border px-3 py-2 rounded-lg">

                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">
                        Export Excel
                    </button>
                </form>

                <form action="/products/import" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="file" name="file" class="border px-3 py-2 rounded-lg">

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Import Excel
                    </button>
                </form>

                <!-- Product Table Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-left" id="productTable">
                        <thead class="bg-gray-50 border-b border-gray-100 text-gray-600 text-sm">
                            <tr>
                                <th class="p-4 font-semibold">#</th>
                                <th class="p-4 font-semibold">Code</th>
                                <th class="p-4 font-semibold">Product Name</th>
                                <th class="p-4 font-semibold">Category</th>
                                <th class="p-4 font-semibold">Qty</th>
                                <th class="p-4 font-semibold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y divide-gray-100">
                            <!-- Sample Row -->
                            @forelse ($products as $pro)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4">{{ $pro->id }}</td>
                                    <td class="p-4">{{ $pro->code }}</td>
                                    <td class="p-4 font-medium">{{ $pro->name }}</td>

                                    <td class="p-4">
                                        <span
                                            class="bg-blue-100 text-blue-600 px-2 py-1 rounded-md text-xs font-bold uppercase">
                                            {{ $pro->category?->name ?? 'No Category' }}
                                        </span>
                                    </td>

                                    <td class="p-4 font-bold">{{ $pro->qty }}</td>

                                    <td class="p-4 text-center">
                                        <button @click="openEdit = true"
                                            class="text-blue-500 hover:text-blue-700 mx-2 btn-edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button @click="openDelete = true"
                                            class="text-red-500 hover:text-red-700 mx-2 btn-delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center p-4 text-gray-400">
                                        No products found
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL: ADD PRODUCT -->
    <div x-show="openAdd"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4" x-cloak>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden" @click.away="openAdd = false">
            <div class="p-6 border-b flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800 uppercase">Add New Product</h3>
                <button @click="openAdd = false" class="text-gray-400 hover:text-gray-600"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="/products" method="POST">
                @csrf

                <div class="p-6 space-y-4">

                    <!-- Product Name -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Product Name</label>
                        <input type="text" name="name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- Product Code -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Product Code</label>
                        <input type="text" name="code"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Category</label>

                            <select name="category_id"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

                                <option value="">-- Select Category --</option>

                                @foreach ($allcate as $allc)
                                    <option value="{{ $allc->id }}">
                                        {{ $allc->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <!-- Quantity -->
                        <!-- Quantity Input Section -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="qty" min="0""
                                class=" w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500
                                outline-none @error('qty') border-red-500 @enderror"
                                required>

                            <!-- បង្ហាញសារ Error បើបញ្ចូលលេខខុស -->
                            @error('qty')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="p-6 border-t bg-gray-50 flex justify-end space-x-3">
                    <button type="button" @click="openAdd = false" class="px-4 py-2 text-gray-600 font-semibold">
                        Cancel
                    </button>

                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- MODAL: UPDATE PRODUCT -->
    <div x-show="openEdit"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4" x-cloak>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden" @click.away="openEdit = false">
            <div class="p-6 border-b flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800 uppercase">Update Product</h3>
                <button @click="openEdit = false" class="text-gray-400 hover:text-gray-600"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4">
                    <div>
                        <input type="hidden" name="id" id="id">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Product Name</label>
                        <input type="text" name="name" id="name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Product Code</label>
                        <input type="text" name="code" id="code"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Category</label>

                            <select name="category_id" id="category_id"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

                                <option value="">-- Select Category --</option>

                                @foreach ($allcate as $allc)
                                    <option value="{{ $allc->id }}">
                                        {{ $allc->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="qty" min="0" id="qty"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500
                                outline-none @error('qty') border-red-500 @enderror"
                                required>

                            @error('qty')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="p-6 border-t bg-gray-50 flex justify-end space-x-3">
                    <button type="button" @click="openEdit = false" class="px-4 py-2 text-gray-600 font-semibold">
                        Cancel
                    </button>

                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: DELETE POPUP -->
    <div x-show="openDelete"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4" x-cloak>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6 text-center" @click.away="openDelete = false">
            <div
                class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="" id="id" name="id">
                <h3 class="text-xl font-bold text-gray-800">Confirm Delete</h3>
                <p class="text-gray-500 mt-2">Are you sure you want to delete this product? This action cannot be
                    undone.
                </p>
                <div class="mt-6 flex justify-center space-x-3">
                    <button type="submit"
                        class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-700 transition">Yes,
                        Delete</button>
                </div>
            </form>
            <button @click="openDelete = false" class="px-4 py-2 text-gray-500 font-semibold">Cancel</button>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#productTable').on('click', '.btn-edit', function() {
            let row = $(this).closest('tr');
            let id = row.find('td').eq(0).text().trim();
            let code = row.find('td').eq(1).text().trim();
            let name = row.find('td').eq(2).text().trim();
            let category = row.find('td').eq(3).text().trim();
            let qty = row.find('td').eq(4).text().trim();

            $('#id').val(id);
            $('#code').val(code);
            $('#name').val(name);
            $('#category').val(category);
            $('#qty').val(qty);

            $('#editForm').attr('action', '/products/' + id);
        });



        $('#productTable').on('click', '.btn-delete', function() {
            let row = $(this).closest('tr');
            let id = row.find('td').eq(0).text().trim();

            $('#delete_id').val(id);
            $('#deleteForm').attr('action', '/products/' + id);

            console.log($('#deleteForm').attr('action'));
        });
    </script>
</body>

</html>
