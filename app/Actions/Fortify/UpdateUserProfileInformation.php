<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        Validator::make($input, [
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]); // ->validateWithBag('updateProfileInformation');

        if (
            $input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                // 'name' => $input['name'],
                'email' => $input['email'],
            ])->save();
        }

        $user->empleados()->update([
            'identificacion' => $input['identificacion'],
            'nombres' => $input['nombres'],
            'apellidos' => $input['apellidos'],
            'telefono' => $input['telefono'],
            'fecha_nacimiento' => $input['fecha_nacimiento'],
            'jefe_id' => $input['jefe_id'],
            'localidad_id' => $input['localidad_id'],
        ]);

        // Log::channel('testing')->info('Log', ['listado', $input]);

        // return response()->json(['mensaje' => 'Perfil actualizado exitosamente!']);
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->empleados()->update($input);

        $user->sendEmailVerificationNotification();
    }
}
