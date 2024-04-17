<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\PendingCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\AddCatalogAPI;


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

     public  function savecatalog(AddCatalogAPI $request)
        {
          
            $response = [
                'success' => false,
                'status' => 400,
                'message' => 'An error occurred while attempting to save pending catalog.'
            ];
    
            $validatedData = $request->validated();
         
  
    
            //-------------------------------------------------------
            if($validatedData['image'])
            {
                // Extracting filename from URL
                $filename = uniqid() . '_' . basename($validatedData['image']);
                // Initialize cURL session
                $ch = curl_init($validatedData['image']);
                // Set options
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
                
                // Fetching image content from URL
                $imageContent = curl_exec($ch);
                
                // Checking if image content was retrieved successfully
                if ($imageContent === false) {
                    throw new \Exception('Failed to retrieve image content from URL');
                }
                
                // Close cURL session
                curl_close($ch);
                
              $saveDirectory = storage_path('/app/public/Catalogs');
             
          
                if (!is_dir($saveDirectory)) {
                    mkdir($saveDirectory, 0755, true); // Creating directory if it doesn't exist
                }
                
              //  Full file path to save the image
               $filePath = $saveDirectory . '/' . $filename;
                
               // Saving image content to file
               $saved = file_put_contents($filePath, $imageContent);
               
                
               // Checking if image file was saved successfully
                if ($saved === false) {
                    throw new \Exception('Failed to save image file');
                }
              
              $Catalog = Catalog::create([
                'author_id' => $validatedData['author_id'],
                'wp_category_id' => $validatedData['wp_category_id'],
                'title' => $validatedData['title'],
                'name' => $validatedData['name'],
                'sku' => $validatedData['sku'],
                'base_price' => $validatedData['base_price'],
                'image' => "Catalogs/".$filename,
                'content' => $validatedData['content'],
                'status' => $validatedData['status'],
                'publish_date' => $validatedData['publish_date']
            ]);
   
        // return $saved ? $filePath : null; 
            //-----
            } 
            //--------------------------------------------------------
            if($Catalog){
                $response = [
                    'success' => true,
                    'status' => 201,
                    'message' => 'Catalog Added',
                    'data' => ['catalog_id' =>  $Catalog->id ],
                ];
            }                

            return response()->json($response);
           
        }

    
}
