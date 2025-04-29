<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\UnitEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreTopicRequest extends FormRequest
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
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('topics', 'name')->where(
                    'user_id', $this->user()->id
                ),
            ],
            'description' => 'required|string|max:255|min:3',
            'unit' => [
                'required',
                Rule::in(UnitEnum::cases()),
            ],
        ];
    }
}
