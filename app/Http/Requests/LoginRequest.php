<?php

namespace App\Http\Requests;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\ValidationException;
class LoginRequest extends FormRequest
{
/**
     * Determine if the user is authorized to make this request.
     */
 public function authorize(): bool
 {
 return true;
 }
 /**
* Get the validation rules that apply to the request.
 *
  * @return array&lt;string, ValidationRule|array&lt;mixed>|string>
  */
  public function rules(): array
  {
    return [
      'email' => ['required', 'string', 'email'],
      'password' => ['required', 'string'],
    ];
  }
  // Pesan error kustom (opsional)
  public function messages(): array
  {
    return [
      'email.required' => 'Email wajib diisi.',
      'email.email' => 'Format email tidak valid.',
      'password.required' => 'Password wajib diisi.',
    ];
  }
  // Logika autentikasi dipindahkan ke sini agar rapi
  public function authenticate(): void
  {
    if (! Auth::attempt($this->only('email', 'password'))) {
      throw ValidationException::withMessages([
        'email' => 'Email atau password yang Anda masukkan salah.',
      ]);
    }
    // Regenerasi session agar aman dari session fixation
    $this->session()->regenerate();
  }
}