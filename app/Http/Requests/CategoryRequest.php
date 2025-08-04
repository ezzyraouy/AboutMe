<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
        ];

        foreach ($availableLanguages as $lang) {
            $rules["title.$lang"] = ($lang === $defaultLanguage) ? 'required|string' : 'nullable|string';
        }

        return $rules;
    }
}