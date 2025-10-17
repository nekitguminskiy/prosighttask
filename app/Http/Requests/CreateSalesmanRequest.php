<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\TitleAfter;
use App\Enums\TitleBefore;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @phpstan-type TitleBeforeCode = 'Bc.' | 'Mgr.' | 'Ing.' | 'JUDr.' | 'MVDr.' | 'MUDr.' | 'PaedDr.' | 'prof.' | 'doc.' | 'dipl.' | 'MDDr.' | 'Dr.' | 'Mgr. art.' | 'ThLic.' | 'PhDr.' | 'PhMr.' | 'RNDr.' | 'ThDr.' | 'RSDr.' | 'arch.' | 'PharmDr.'
 * @phpstan-type TitleAfterCode = 'CSc.' | 'DrSc.' | 'PhD.' | 'ArtD.' | 'DiS' | 'DiS.art' | 'FEBO' | 'MPH' | 'BSBA' | 'MBA' | 'DBA' | 'MHA' | 'FCCA' | 'MSc.' | 'FEBU' | 'LL.M'
 * @phpstan-type GenderCode = 'm' | 'f'
 * @phpstan-type MaritalStatusCode = 'single' | 'married' | 'divorced' | 'widowed'
 */
final class CreateSalesmanRequest extends FormRequest
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
     * @return array<string, array<int, string|\Illuminate\Validation\Rules\In|\Illuminate\Validation\Rules\Unique>>
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],
            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],
            'titles_before' => [
                'nullable',
                'array',
                'max:10',
            ],
            'titles_before.*' => [
                'string',
                'min:2',
                'max:10',
                Rule::in(array_column(TitleBefore::cases(), 'value')),
            ],
            'titles_after' => [
                'nullable',
                'array',
                'max:10',
            ],
            'titles_after.*' => [
                'string',
                'min:2',
                'max:10',
                Rule::in(array_column(TitleAfter::cases(), 'value')),
            ],
            'prosight_id' => [
                'required',
                'string',
                'size:5',
                'unique:salesmen,prosight_id',
            ],
            'email' => [
                'required',
                'email',
                'unique:salesmen,email',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'gender' => [
                'required',
                'string',
                Rule::in(array_column(Gender::cases(), 'value')),
            ],
            'marital_status' => [
                'nullable',
                'string',
                Rule::in(array_column(MaritalStatus::cases(), 'value')),
            ],
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
            'titles_before.*.in' => 'The selected title before is invalid.',
            'titles_after.*.in' => 'The selected title after is invalid.',
            'gender.in' => 'The selected gender is invalid.',
            'marital_status.in' => 'The selected marital status is invalid.',
            'prosight_id.unique' => 'A salesman with this prosight ID already exists.',
            'email.unique' => 'A salesman with this email already exists.',
        ];
    }
}
