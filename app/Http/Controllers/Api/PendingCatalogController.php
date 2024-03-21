<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddPendingCatalog;
use App\Models\PendingCatalog;
use Illuminate\Http\Request;

class PendingCatalogController extends Controller
{
    public function savePendingCatalog(AddPendingCatalog $request)
    {
        $response = [
            'success' => false,
            'status' => 400,
            'message' => 'An error occurred while attempting to save pending catalog.'
        ];

        $validatedData = $request->validated();

          //Create User
                $pendingCatalog = PendingCatalog::create([
                    // 'author_id' => auth()->user()->id,
                    // 'name' => $validatedData['title'],
                    'title' => $validatedData['title'],
                    'content' => $validatedData['content'],
                    'wp_category_id' => $validatedData['category'] ?? null,
                    'sku' => $validatedData['sku'] ?? null,
                    'base_price' => $validatedData['base_price'],
                    'status' => 'draft',
                    'publish_date' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        
                //Only if Needs to update the preview image then this will update the image
                if ($request->hasFile('image')) {
                    //Delete The Old Stored Image in path And Upload New
                        $uploadedFile = $request->file('image');
                        $filename = time() . '_' . $uploadedFile->getClientOriginalName();
                        $uploadedFile->storeAs('public/Catalogs', $filename);
                        $path = 'Catalogs/' . $filename;
                        PendingCatalog::where('id', $pendingCatalog->id)->update(['image' => $path]);
        
                }
                if($pendingCatalog){
                    $response = [
                        'success' => true,
                        'status' => 201,
                        'message' => 'Catalog Added In Pending List.'
                    ];
                }
    
        return response()->json($response);
    }
}
