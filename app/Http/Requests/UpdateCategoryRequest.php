<?php

namespace App\Http\Requests;

use App\Models\Category;
use Elastic\Elasticsearch\Endpoints\Cat;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id',
                function (string $attribute, $value, \Closure $fail) {
                    $id = $this->get('id');
                    $category = Category::where('id', $id)->first();
                    if (!$category) {
                        return $fail('Category not exists');
                    }
                    $children = Category::getAllChildrenByParent($category);
                    $ids = array_map(fn($c) => $c->id, $children);
                    if (in_array($value, $ids)) {
                        return $fail('This category is already child category of the selected category and can\'t be selected as parent one');
                    }
                }
            ],
            'active' => ['required', 'boolean']
        ];
    }
}
