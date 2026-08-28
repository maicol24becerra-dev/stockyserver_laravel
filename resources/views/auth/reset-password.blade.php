<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña — El Cielo</title>
    <link rel="stylesheet" href="{{ asset('css/auth-forms.css') }}">
</head>
<body>

<div class="contenedor">
    <div class="card">

        <h1>El Cielo</h1>
        <p>Nueva contraseña</p>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if(session('codigo'))
            <div class="codigo">
                <strong>Tu código de recuperación es:</strong>
                <h2>{{ session('codigo') }}</h2>
                <small>Este código es válido durante 15 minutos.</small>
            </div>
        @endif

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <label for="correo">Correo</label>
            <input
                type="email"
                id="correo"
                name="correo"
                value="{{ old('correo', $correo) }}"
                required
            >

            <label for="codigo">Código de recuperación</label>
            <input
                type="text"
                id="codigo"
                name="codigo"
                maxlength="6"
                required
            >

            <label for="password">Nueva contraseña</label>
            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                required
            >

            <label for="password_confirmation">Confirmar contraseña</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                minlength="8"
                required
            >

            <button type="submit" class="btn-submit">
                Cambiar contraseña
            </button>
        </form>

    </div>
</div>

</body>
</html>
