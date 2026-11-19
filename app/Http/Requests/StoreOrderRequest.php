<?php

namespace App\Http\Requests;

use App\Models\ProductVariant;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreOrderRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|ulid|exists:products,id',
            'items.*.variant_id' => 'required|ulid|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',

        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $items = $this->input('items', []);

                if (! is_array($items)) {
                    return;
                }

                foreach ($items as $index => $item) {
                    if (! is_array($item)) {
                        continue;
                    }

                    $productErrorKey = "items.{$index}.product_id";
                    $variantErrorKey = "items.{$index}.variant_id";

                    if (
                        $validator->errors()->has($productErrorKey) ||
                        $validator->errors()->has($variantErrorKey)
                    ) {
                        continue;
                    }

                    $belongsToProduct = ProductVariant::query()
                        ->whereKey($item['variant_id'])
                        ->where('product_id', $item['product_id'])
                        ->exists();

                    if (! $belongsToProduct) {
                        $validator->errors()->add(
                            $variantErrorKey,
                            __('validation.variant_product_mismatch'),
                        );
                    }
                }
            },
        ];
    }
}
