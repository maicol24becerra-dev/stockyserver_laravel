<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determina si la solicitud está autorizada.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'correo' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Intentar autenticar al usuario.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = [
            'correo' => $this->string('correo')->toString(),
            'password' => $this->string('password')->toString(),
        ];

        if (! Auth::attempt($credentials)) {
            // 1 intento fallido
            RateLimiter::hit($this->throttleKey(), 15 * 60);

            $intentos = RateLimiter::attempts($this->throttleKey());
            $restantes = max(0, 3 - $intentos);

            if ($intentos >= 3) {
                throw ValidationException::withMessages([
                    'correo' => 'Superaste el límite de intentos. Acceso bloqueado por 15 minutos.',
                ]);
            }

            throw ValidationException::withMessages([
                'correo' => "Usuario o contraseña incorrectos. Te quedan {$restantes} intento(s).",
            ]);
        }

        // Login correcto: limpiar contador
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Verificar si se superó el límite de intentos.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'correo' => 'Demasiados intentos fallidos. Intenta de nuevo en '
                . ceil($seconds / 60)
                . ' minuto(s).',
        ]);
    }

    /**
     * Clave utilizada para controlar los intentos.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('correo')) . '|' . $this->ip()
        );
    }
}