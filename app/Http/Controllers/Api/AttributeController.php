<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DropdownOption;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function getModels(Request $request)
    {
        $makeId = $request->query('make_id');
        
        if (!$makeId) {
            return response()->json([], 400);
        }

        $models = DropdownOption::where('type', DropdownOption::TYPE_MODEL)
            ->where('parent_id', $makeId)
            ->where('is_active', true)
            ->orderBy('label') // Models usually sorted alphabetically
            ->get(['id', 'label', 'value']);

        return response()->json($models);
    }
}
