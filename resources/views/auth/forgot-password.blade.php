<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña — El Cielo</title>
    <link rel="stylesheet" href="{{ asset('css/auth-forms.css') }}">
</head>
<body>

<div class="contenedor">
    <div class="card">

        <h1>El Cielo</h1>
        <p class="descripcion">Recuperar contraseña</p>

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label for="correo">Correo electrónico</label>
            <input
                type="email"
                id="correo"
                name="correo"
                value="{{ old('correo') }}"
                placeholder="ejemplo@correo.com"
                required
                autofocus
            >

            <button type="submit" class="btn-submit">
                Generar código
            </button>
        </form>

        <div class="enlaces">
            <a href="{{ route('login') }}">← Volver a iniciar sesión</a>
        </div>

    </div>
</div>

</body>
</html>
