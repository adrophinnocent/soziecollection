<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpsertHomepageSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAccess('homepage_content') ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('button_link')) {
            $link = (string) $this->input('button_link');
            if (! Str::startsWith(strtolower(trim($link)), ['javascript:', 'data:'])) {
                $this->merge(['button_link' => $this->formatLink($link)]);
            }
        }
        if ($this->filled('secondary_button_link')) {
            $link = (string) $this->input('secondary_button_link');
            if (! Str::startsWith(strtolower(trim($link)), ['javascript:', 'data:'])) {
                $this->merge(['secondary_button_link' => $this->formatLink($link)]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'eyebrow' => ['nullable', 'string', 'max:80'],
            'headline' => ['required', 'string', 'max:120'],
            'highlight_text' => ['nullable', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'image' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,gif,bmp,avif,svg',
                'max:20480',
            ],
            'image_url' => ['nullable', 'string', 'max:500'],
            'mobile_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,bmp,avif,svg', 'max:20480'],
            'mobile_image_url' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:60'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'secondary_button_text' => ['nullable', 'string', 'max:60'],
            'secondary_button_link' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'show_in_hero' => ['sometimes', 'boolean'],
            'show_in_gallery' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->isMethod('post')) {
                if (! $this->hasFile('image') && ! $this->filled('image_url')) {
                    $validator->errors()->add('image', 'Upload a desktop design image file or enter an image URL link.');
                }
            }

            foreach (['button_link', 'secondary_button_link'] as $field) {
                $link = (string) $this->input($field);
                if (str_starts_with(strtolower(trim($link)), 'javascript:') || str_starts_with(strtolower(trim($link)), 'data:')) {
                    $validator->errors()->add($field, 'Unsafe button link URL format.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Upload a desktop design image file or enter an image URL link.',
        ];
    }

    private function formatLink(string $link): string
    {
        $link = trim($link);
        if ($link === '') {
            return '';
        }
        if (Str::startsWith($link, ['/', '#', 'http://', 'https://'])) {
            return $link;
        }
        if (str_contains($link, '.')) {
            return 'https://'.$link;
        }

        return '/'.$link;
    }
}
