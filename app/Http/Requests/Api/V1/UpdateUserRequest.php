<?php

namespace App\Http\Requests\Api\V1;

use App\Helpers\FileHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'username' => 'required|string|unique:users,username,' . $userId,
            'email' => 'required|email|unique:users,email,' . $userId,
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'is_admin' => 'boolean',
            'hidden' => 'boolean',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:' . FileHelper::getMaxUploadSizeKB()
        ];
    }
}
