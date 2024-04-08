<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\PendingCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    public function getCatalogDetail()
    {
        $response = [
            'success' => false,
            'status' => 400,
            'message' => 'Error fetching catalog details.'
        ];
    
        if (request()->has('q')) {
            $q = request()->input('q');
    
            // Get Catalogs By Searched params
            $results = Catalog::where('title', 'LIKE', '%' . $q . '%')->get();
    
            // Update image path for search results
            foreach ($results as $result) {
                if ($result->image != null) {
                    $result->image = '/storage/' . $result->image;
                }
            }
        } else {
            // Get All Available Catalogs
            $results = Catalog::all();
    
            // Update image path for all available catalogs
            foreach ($results as $result) {
                if ($result->image != null) {
                    $result->image = '/storage/' . $result->image;
                }
            }
        }
    
        if ($results->isNotEmpty()) {
            $response['success'] = true;
            $response['status'] = 200;
            $response['message'] = 'Catalog details retrieved successfully.';
            $response['data'] = $results;
        }
    
        return response()->json($response); 
    }

    public function  fetchCatalogsDetailWithStatusByIds()
    {
        $response = [
            'success' => false,
            'status' => 400,
            'message' => 'Error fetching catalog details.'
        ];

        $catalogIds = request()->input('catalog_ids');
        $pendingCatalogIds = request()->input('pending_catalog_ids');

        if($catalogIds){
            $catalogDetail =  Catalog::whereIn('id',$catalogIds)->get();
            if(!empty($catalogDetail)){
                foreach ($catalogDetail as $result) {
                    if ($result->image != null) {
                        $result->image = '/storage/'. $result->image;
                    }
                }
            }
        }
        

        if($pendingCatalogIds){
            $pendingCatalogDetail =  PendingCatalog::whereIn('id',$pendingCatalogIds)->get();
            if (!empty($pendingCatalogDetail)) {
                foreach ($pendingCatalogDetail as $result) {
                    if ($result->image !== null) {
                        // Check if the URL is external
                        $isExternal = Str::startsWith($result->image, ['http://', 'https://']);
                        if (!$isExternal) {
                            $result->image = '/storage/' . $result->image;
                        }
                    }
                }
            }
        }
        $data['catlogDetail'] =  $catalogDetail ?? [] ;
        $data['pendingCatalogDetail'] =  $pendingCatalogDetail ?? [];


        // dd($catalogIds,$pendingCatalogIds);

        $response = [
            'success' => true,
            'status' => 200,
            'message' => 'Data Retrived Successfully',
            'data' => $data,
        ];
        return response()->json($response); 
    }
    
}
