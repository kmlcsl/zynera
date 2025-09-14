<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class FileUploadController extends Controller
{
    public function uploadProductImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Resize and optimize image menggunakan Intervention Image v3 (Laravel 12)
            $img = Image::read($image)
                ->scaleDown(800, 600);

            $path = 'products/' . $filename;

            // Simpan gambar yang sudah diproses
            Storage::disk('public')->put($path, $img->encode());

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload gambar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadPaymentProof(Request $request)
    {
        $request->validate([
            'proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $image = $request->file('proof');
            $filename = 'payment_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Opsi 1: Simpan langsung tanpa resize
            $path = $image->storeAs('payments', $filename, 'public');

            // Opsi 2: Dengan resize (uncomment jika diperlukan)
            /*
            $img = Image::read($image)
                ->scaleDown(1200, 800);

            $path = 'payments/' . $filename;
            Storage::disk('public')->put($path, $img->encode());
            */

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload bukti pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
