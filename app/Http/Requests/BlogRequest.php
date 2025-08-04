<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'content' => 'required|array',
            'date' => 'required|date',
            'files.*' => 'file|mimes:jpg,jpeg,png,gif,mp4,pdf|max:10240',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
        ];

        foreach ($availableLanguages as $lang) {
            $titleRule = ($lang === $defaultLanguage) ? 'required|string' : 'nullable|string';
            $contentRule = ($lang === $defaultLanguage) ? 'required|string' : 'nullable|string';

            $rules["title.$lang"] = $titleRule;
            $rules["content.$lang"] = $contentRule;
        }

        return $rules;
    }
}