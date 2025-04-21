<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImageController extends Controller
{
    public function removeBackground(Request $request)
{
    $request->validate([
        'image' => 'required|image|max:5120',
    ]);

    $file = $request->file('image');
    $filename = time() . '.' . $file->getClientOriginalExtension();
    $originalPath = storage_path('app/public/originals/' . $filename);
    $removedPath = storage_path('app/public/removed/removed-' . $filename);

    $file->move(storage_path('app/public/originals'), $filename);

    $apiKey = env('REMOVE_BG_API_KEY'); // replace with your real key

    $response = Http::attach(
        'image_file', file_get_contents($originalPath), $filename
    )->withHeaders([
        'X-Api-Key' => $apiKey
    ])->post('https://api.remove.bg/v1.0/removebg');

    if ($response->successful()) {
        file_put_contents($removedPath, $response->body());
        return response()->download($removedPath);
    }

    return response()->json([
        'error' => 'Failed to remove background',
        'details' => $response->json(),
    ], 500);
}
}
