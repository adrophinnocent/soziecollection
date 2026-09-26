<?php

namespace App\Http\Requests\Admin;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpsertHomepageSlideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->canAccess('homepage_content') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'eyebrow' => ['nullable', 'string', 'max:80'],
            'headline' => ['required', 'string', 'max:120'],
            'highlight_text' => ['nullable', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'image' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
                'dimensions:min_width=800,min_height=500',
            ],
            'mobile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'button_text' => ['nullable', 'string', 'max:60'],
            'button_link' => ['nullable', 'string', 'max:255', $this->safeLink()],
            'secondary_button_text' => ['nullable', 'string', 'max:60'],
            'secondary_button_link' => ['nullable', 'string', 'max:255', $this->safeLink()],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Upload a desktop design for this slide.',
            'image.dimensions' => 'The desktop design must be at least 800px wide and 500px high.',
        ];
    }

    private function safeLink(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if (! Str::startsWith($value, ['/', '#', 'http://', 'https://'])) {
                $fail('The :attribute must be an internal path or a valid http(s) URL.');
            }
        };
    }
}
