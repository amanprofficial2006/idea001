<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming authenticated users can create tasks
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'budget_type' => 'required|in:fixed,hourly',
            'amount' => 'required|numeric',
            'urgency_level' => 'required|in:urgent,today,tomorrow,week,custom',
            'help_needed_within' => 'nullable|integer',
            'deadline' => 'nullable|date',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'additional_info' => 'nullable|string',
            'contact_preference' => 'required|in:message,call,both',
            'privacy' => 'required|in:public,verified,invite',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:4096',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The task title is required.',
            'category.required' => 'The category is required.',
            'description.required' => 'The description is required.',
            'budget_type.required' => 'The budget type is required.',
            'budget_type.in' => 'The budget type must be either fixed or hourly.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.',
            'urgency_level.required' => 'The urgency level is required.',
            'urgency_level.in' => 'The urgency level must be one of: urgent, today, tomorrow, week, custom.',
            'deadline.after' => 'The deadline must be a date after today.',
            'location.required' => 'The location is required.',
            'lat.between' => 'The latitude must be between -90 and 90.',
            'lng.between' => 'The longitude must be between -180 and 180.',
            'contact_preference.in' => 'The contact preference must be one of: message, call, both.',
            'privacy.in' => 'The privacy must be one of: public, verified, invite.',
        ];
    }
}
