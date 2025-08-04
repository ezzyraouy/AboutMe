<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlideRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $availableLanguages = config('languages.available');
        $defaultLanguage = config('languages.default');

        $rules = [
            'title' => 'required|array',
            'description' => 'nullable|array',
            'file' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ];

        foreach ($availableLanguages as $lang) {
            $rules["title.$lang"] = ($lang === $defaultLanguage) ? 'required|string' : 'nullable|string';
            $rules["description.$lang"] = 'nullable|string';
            $rules["file.$lang"] = 'nullable|file|mimes:pdf,jpeg,png,jpg,gif,mp4|max:5120';
        }

        return $rules;
    }
}