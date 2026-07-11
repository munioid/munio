<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\UpdateProfile;
use App\Http\Resources\Authentication\ProfileResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();

        return $this->respondWithItem(new ProfileResponse($user));
    }

    public function updateProfile(UpdateProfile $request)
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            $input = $request->validated();

            $user->update([
                'name' => data_get($input, 'name'),
                'email' => data_get($input, 'email'),
            ]);

            // Jika password lama benar, update password
            if (filled(data_get($input, 'new_password'))) {
                if (! Hash::check(data_get($input, 'last_password'), $user->password)) {
                    throw new Exception('Password lama tidak sesuai.', 422);
                }

                $user->update([
                    'password' => bcrypt(data_get($input, 'new_password')),
                ]);
            }
            DB::commit();
            return $this->respondSuccess('Profile berhasil dirubah.');
        } catch (Throwable $th) {
            return $this->respondWithError($th->getMessage(), $th->getCode());
        }
    }
}
