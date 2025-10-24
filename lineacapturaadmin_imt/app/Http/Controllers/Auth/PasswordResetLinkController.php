<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Muestra el formulario para solicitar enlace de restablecimiento de contraseña.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Envía el enlace de restablecimiento y maneja posibles errores.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status == Password::RESET_LINK_SENT
                        ? back()->with('status', __($status))
                        : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);

        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            // Error de conexión con el servidor de correo del IMT
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'No se pudo enviar el correo de recuperación. Este servicio solo está disponible dentro de la red del Instituto Mexicano del Transporte (IMT). Por favor, conéctese a la red interna del IMT e intente nuevamente.'
                ]);

        } catch (\Exception $e) { 
            // Cualquier otro error inesperado
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Ocurrió un error al intentar enviar el correo de recuperación. Por favor, contacte a la División de Telemática del IMT para obtener asistencia.'
                ]);
        }
    }
}