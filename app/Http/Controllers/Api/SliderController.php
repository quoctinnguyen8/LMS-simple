<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Lấy danh sách slider đang hoạt động
     */
    public function index()
    {
        $sliders = Slider::active()
            ->ordered()
            ->select(['id', 'title', 'description', 'image_url', 'link_url', 'position'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sliders,
            'message' => 'Lấy danh sách slider thành công'
        ]);
    }

    /**
     * Lấy thông tin chi tiết một slider
     */
    public function show($id)
    {
        $slider = Slider::active()
            ->select(['id', 'title', 'description', 'image_url', 'link_url', 'position'])
            ->find($id);

        if (!$slider) {
            return response()->json([
                'success' => false,
                'message' => 'Slider không tồn tại hoặc đã hết hạn'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $slider,
            'message' => 'Lấy thông tin slider thành công'
        ]);
    }
}
