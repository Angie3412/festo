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

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    <style>
        /* Estilos básicos para la tabla */
        table {
            table-layout: fixed;
            width: 100%;
        }

        th, td {
            padding: 12px;
            word-wrap: break-word;
            vertical-align: top;
        }

        /* Aseguramos que las columnas no se sobrepongan */
        th, td {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Estilo para la columna de acciones */
        .acciones-col {
            width: 100px; /* Ancho fijo para la columna de acciones */
            min-width: 100px;
            text-align: center;
            white-space: nowrap;
        }

        /* Aseguramos que las acciones estén en una sola línea */
        .acciones-col form {
            display: inline-block;
        }

        /* Estilo para botones de acciones */
        .acciones-col button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .acciones-col i {
            transition: color 0.2s;
        }

        .acciones-col i:hover {
            color: #1e40af; /* Cambiar color al hacer hover */
        }

        /* Fondo y diseño del cuerpo */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f3f4f6;
        }

        .container {
            padding: 20px;
        }

        /* Mejoras en el diseño de la tabla */
        .table-container {
            overflow-x: auto;
        }

        .table {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .table thead {
            background-color: #3b82f6;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f1f5f9;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navegación -->
        @include('layouts.navigation')

        <!-- Contenido de la página -->
        <main class="container mx-auto">
            <div class="table-container">
                <div class="table w-full max-w-4xl mx-auto">
                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr>
                                <th class="px-4 py-4">Código</th>
                                <th class="px-4 py-4">Nombre</th>
                                <th class="px-4 py-4">Descripción</th>
                                <th class="px-4 py-4">Stock</th>
                                <th class="px-4 py-4">Categoria</th>
                                <th class="px-4 py-4">Imagen</th>
                                <th class="px-4 py-4">Códigos de Barras</th>
                                <th class="px-4 py-4">Estado</th>
                                <th class="px-4 py-4 acciones-col">Acciones</th>
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
                                <td class="px-4 py-4">
                                    <img src="{{ asset('storage/images/' . $post->imagen) }}" alt="Imagen del post" class="w-12 h-12 rounded-md">
                                </td>
                                <td class="px-4 py-4">{{ $post->imagencode }}</td>
                                <td class="px-4 py-4">{{ $post->estado }}</td>
                                <td class="px-4 py-4 acciones-col">
                                    <!-- Formulario para disminuir stock -->
                                    <form action="{{ route('posts.adjustarStock', ['post' => $post->id, 'action' => 'decrease']) }}" method="POST" title="Sustraer Stock">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-800">
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                    </form>

                                    <!-- Formulario para aumentar stock -->
                                    <form action="{{ route('posts.adjustarStock', ['post' => $post->id, 'action' => 'increase']) }}" method="POST" title="Sumar Stock">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-plus-circle"></i>
                                        </button>
                                    </form>

                                    <!-- Formulario para eliminar -->
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

    <!-- Script para ajustar el stock mediante AJAX (si se desea) -->
    <script>
        function adjustStock(postId, action) {
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
