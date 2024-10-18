<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600" rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite('resources/css/app.css')

    <!-- Font Awesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Tu archivo CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen bg-gradient-to-r from-blue-100 to-purple-200">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main>
            <div class="container mx-auto p-6">

                <!-- Tabla -->
                <div class="mt-10 overflow-hidden rounded-lg shadow-lg mx-auto w-full max-w-4xl bg-white">
                    <table class="table-auto w-full text-left bg-gray-50">
                        <thead class="bg-gradient-to-r from-blue-500 to-purple-500 text-white">
                            <tr>
                                <th class="px-4 py-4">Código</th>
                                <th class="px-4 py-4">Nombre</th>
                                <th class="px-4 py-4">Descripción</th>
                                <th class="px-4 py-4">Stock</th>
                                <th class="px-4 py-4">Categoria</th>
                                <th class="px-4 py-4">Imagen</th>
                                <th class="px-4 py-4">Códigos de Barras</th>
                                <th class="px-4 py-4">Estado</th>
                                <th class="px-4 py-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="postsTable" class="divide-y divide-gray-200">
                            @foreach ($posts as $post)
                            <tr class="bg-white hover:bg-gray-100 transition duration-200">
                                <td class="px-4 py-4">{{ $post->cod_herramienta }}</td>
                                <td class="px-4 py-4">{{ $post->nombre }}</td>
                                <td class="px-4 py-4">{{ Str::limit($post->descripcion, 50) }}</td>
                                <td class="px-4 py-4">{{ $post->stock }}</td>
                                <td class="px-4 py-4">{{ $post->categoria }}</td>
                                <td class="px-4 py-4"><img src="{{ asset('storage/images/' . $post->imagen) }}" alt="Imagen del post" class="w-12 h-12 rounded-md"></td>
                                <td class="px-4 py-4">{{ $post->imagencode }}</td>
                                <td class="px-4 py-4">{{ $post->estado }}</td>
                                <td class="border px-4 py-4 flex space-x-2">
                                    <!-- Botón para disminuir stock -->
                                    <form action="{{ route('posts.adjustarStock', ['post' => $post->id, 'action' => 'decrease']) }}" method="POST" title="Sustraer Stock">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-800">
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                    </form>

                                    <!-- Botón para aumentar stock -->
                                    <form action="{{ route('posts.adjustarStock', ['post' => $post->id, 'action' => 'increase']) }}" method="POST" title="Sumar Stock">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-plus-circle"></i>
                                        </button>
                                    </form>

                                    <!-- Botón para eliminar -->
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" title="Eliminar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts para manejar el modal y ajustar el stock -->
    <script>
        function adjustStock(postId, action) {
            // Aquí se puede hacer una solicitud AJAX para actualizar el stock
            const url = `/posts/${postId}/stock/${action}`;
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Recargar la página para reflejar los cambios
                } else {
                    alert('Error al ajustar el stock');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
