<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
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
            'company' => 'required|string',
            'location' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'required|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
        ];

        foreach ($availableLanguages as $lang) {
            $rules["title.$lang"] = ($lang === $defaultLanguage) ? 'required|string' : 'nullable|string';
            $rules["description.$lang"] = ($lang === $defaultLanguage) ? 'required|string' : 'nullable|string';
        }

        return $rules;
    }
}