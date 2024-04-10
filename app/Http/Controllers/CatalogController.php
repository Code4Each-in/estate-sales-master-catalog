<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\AddCatalog;
use App\Http\Requests\UpdateCatalog;
use App\Models\Catalog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {    

        if (request()->ajax()) {
            $query = Catalog::query();
    
            if (request()->has('status_filter') && request()->input('status_filter') == 'all') {
                // If 'status_filter' is 'all', select all records
                $query->select('*');
            } elseif (request()->has('status_filter') && request()->input('status_filter')!= '') {
                // If 'status_filter' is not empty, filter by status
                $query->where('status', request()->input('status_filter'));
            } elseif (request()->has('search') && request()->input('search.value') !== null) {
                // If search value is provided, perform search across multiple columns
                $searchText = request()->input('search.value');
                $columns = ['title', 'base_price', 'content', 'status', 'publish_date','sku'];
                $query->where(function($query) use ($columns, $searchText) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'like', '%'.$searchText.'%');
                    }
                });
            } else {
                // Default behavior, filter by status 'publish'
                $query->where('status', 'publish');
            }
    
            // Implement server-side pagination
            $start = request()->input('start', 0);
            $length = request()->input('length', 10);
    
            $totalRecords = $query->count();
    
            $data = $query
                ->skip($start)
                ->take($length)
                ->get();
    
            return response()->json([
                'data' => $data,
                'draw' => request()->input('draw', 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
            ]);
        }
    
        return view('catalogs.index');
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddCatalog $request)
    {
          //Request Data validation
          $validatedData = $request->validated();
          

           //Create User
         $catalog = Catalog::create([
            'author_id' => auth()->user()->id,
            // 'name' => $validatedData['title'],
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'wp_category_id' => $validatedData['category'],
            'sku' => $validatedData['sku'],
            'base_price' => $validatedData['base_price'],
            'status' => $validatedData['status'],
            'publish_date' => $validatedData['status'] == 'publish' ? now() : null,
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
                Catalog::where('id', $catalog->id)->update(['image' => $path]);

        }

        $request->session()->flash('message','Catalog Saved Successfully.');

        return Response()->json(['status'=>200, 'catalog'=>$catalog]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $catalog = Catalog::find($id);
        $data = [
            'revenue' => [11, 32, 45, 32, 34, 52, 41],
            'customers' => [15, 11, 32, 18, 9, 24, 11],
            'categories' => ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
        ];
        try {
            $results =  $this->getProducts($id);
           
            if($results['success'] == true && $results['status'] == 200 ){
               $products = $results['data'];
            }else{
                $products = [];
            }
        } catch (\Exception $e) {
            //throw $th;
            $products = [];
        }
        //    $results =  $this->getProducts($id);
           
        // if($results['success'] == true && $results['status'] == 200 ){
        //    $products = $results['data'];
        // }else{
        //     $products = [];
        // }
        
        return view('catalogs.show',compact('catalog','data','products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $catalog = Catalog::find($id);


        return Response()->json(['catalogs' =>$catalog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCatalog $request, Catalog $catalog)
    {
          //Request Data validation
          $validatedData = $request->validated();
           //Update User
           $catalogs = Catalog::where('id', $catalog->id)->update([
            // 'name' => $validatedData['title'],
            'title' => $validatedData['title'],
            'wp_category_id' => $validatedData['category'],
            'content' => $validatedData['content'],
            'sku' => $validatedData['sku'],
            'base_price' => $validatedData['base_price'],
            'status' => $validatedData['status'],
            'publish_date' => $validatedData['status'] == 'publish' ? now() : null,
            'updated_at' => now(),
        ]);

         //Only if Needs to update the preview image then this will update the image
         if ($request->hasFile('image')) {
            $oldFilePath = 'storage/'.$catalog->image;
            //Delete The Old Stored Image in path And Upload New
            if (Helper::deleteFile($oldFilePath)) {
                $uploadedFile = $request->file('image');
                $filename = time() . '_' . $uploadedFile->getClientOriginalName();
                $uploadedFile->storeAs('public/Catalogs', $filename);
                $path = 'Catalogs/' . $filename;
                Catalog::where('id', $catalog->id)->update(['image' => $path]);
            } else {
                return back()->with("File $oldFilePath not found.");
            }

        }

        $request->session()->flash('message','Catalog updated successfully.');
		return Response()->json(['status'=>200]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $catalog = Catalog::findOrFail($id);

         $oldFilePath = 'storage/'.$catalog->image;
            // Delete The Old Stored Image in path And Upload New
            if (Helper::deleteFile($oldFilePath)) {
            } else {
                return back()->with("File $oldFilePath not found.");
            }

        $catalog->delete();

        session()->flash('message','Catalog Deleted successfully.');
        return response()->json(['success' => true]);
    }

    // public function fetchCategories()
    // {
    //     $url = 'https://recollection.com/wp-json/wc/v3/products/categories';
        
    //       // Get the selected category value from the request
    //     //   $selectedCategory = request()->input('category');
    //     //   if($selectedCategory){
    //     //     dd($selectedCategory);
    //     //     $url = 'https://recollection.com/wp-json/wc/v3/products/categories?search='.$selectedCategory;
    //     //   }

    //     $consumerKey = 'ck_db66350c57384308f7ffe8045cada46ee3e7d96e';
    //     $consumerSecret = 'cs_7c01bf3a4f3fae66a8cd8c4f40890b91c36151d2';
        
    //     // Generate OAuth nonce and timestamp
    //     $oauthNonce = md5(uniqid(rand(), true));
    //     $oauthTimestamp = time();
        
    //     // Generate OAuth signature
    //     $baseString = 'GET&' . urlencode($url) . '&'
    //         . urlencode('oauth_consumer_key=' . $consumerKey
    //         . '&oauth_nonce=' . $oauthNonce
    //         . '&oauth_signature_method=HMAC-SHA1'
    //         . '&oauth_timestamp=' . $oauthTimestamp
    //         . '&oauth_version=1.0'
    //     );
        
    //     $key = urlencode($consumerSecret) . '&';
    //     $oauthSignature = base64_encode(hash_hmac('sha1', $baseString, $key, true));
        
    //     // Set CURL options
    //     $curl = curl_init();
    //     curl_setopt_array($curl, [
    //         CURLOPT_URL => $url,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_SSL_VERIFYPEER => false, // Disable SSL certificate verification
    //         CURLOPT_HTTPHEADER => [
    //             'Authorization: OAuth oauth_consumer_key="' . $consumerKey . '", '
    //             . 'oauth_nonce="' . $oauthNonce . '", '
    //             . 'oauth_signature="' . urlencode($oauthSignature) . '", '
    //             . 'oauth_signature_method="HMAC-SHA1", '
    //             . 'oauth_timestamp="' . $oauthTimestamp . '", '
    //             . 'oauth_version="1.0"'
    //         ]
    //     ]);
        
    //     // Execute the request
    //     $response = curl_exec($curl);
    //     // dd( $response );
    //     // Check for errors
    //     if ($response === false) {
    //         $error = curl_error($curl);
    //         // Handle error
    //         return "CURL Error: $error";
    //     }
    //     // dd($response);
        
    //     // Close CURL
    //     curl_close($curl);
        
    //     // Process the response as needed
    //     return $response;
        
    // }

    public function fetchCategories()
    {
        $consumerKey =  env('CONSUMER_KEY');
        $consumerSecret = env('CONSUMER_SECRET');
        $apiUrl = env('WORDPRESS_URL').'wp-json/wc/v3/products/categories';

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)
        ->withoutVerifying()->get($apiUrl, ['per_page' => 100]); // Or use a large number to get all categories
        
        if ($response->successful()) {
            $categories = $response->json();
            return response()->json($categories);
        } else {
            return response()->json(['error' => 'Error fetching categories'], 500);
        }
    }

    private function getProducts($id = null)
    {
        $apiUrl = rtrim(env('WORDPRESS_URL'), '/') . '/wp-json/custom/v3/products-by-meta';
    
        try {
            $response = Http::withoutVerifying()
                ->get($apiUrl, ['cat_id' => $id]);
    
            // Check if the request was successful
            if ($response->successful()) {
                $data = $response->json(); 
                // dd($data);
                return $data; // Return the data
                
            } else {
                // Handle unsuccessful response (e.g., log error)
                Log::error('Failed to fetch data from WordPress API');
                return null; // Return null or handle error response as needed
            }
        } catch (Exception $e) {
            
            Log::error('Exception occurred: ' . $e->getMessage());
            return null; 
        }
    }

    public function exportCSV(Request $request)
    {
    
             $statusFilter5 = $request->query('status_filter');
    
       
        $filename = 'Catalog-data.csv';
    
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
  
        return response()->stream(function () use ($statusFilter5) {
       
            $handle = fopen('php://output', 'w');
    
          //  Add CSV headers
            fputcsv($handle, [
                'id',
                'Author Name',
                'Title',
                'Base price',
                'SKU',
                'Publish Date',
                'Image',
                'Status'
            ]);
        
    
                  //  Fetch and process data in chunks

                        $query = DB::table('catalogs');
                        if ((!empty($statusFilter5)) && $statusFilter5 !== "all") {
                            $query->where('status', '=', $statusFilter5);
                        }
                        $catalogs = $query->get();
       
          
                foreach ($catalogs as $val) {
                    $author = DB::table('users')
                     ->where('id', $val->author_id)
                     ->get();

                     if(empty($val->image))
                     {
                        $image_url = 'N/A';
                     }else{
                        $image_url =  url('/storage')."/".$val->image;
                     }
                  
               
           //  Extract data from each employee.
                    $data = [
                        isset($val->id)? $val->id : 'N/A',
                        isset($author[0]->first_name)? $author[0]->first_name : 'N/A',
                        isset($val->title)? $val->title : 'N/A',
                        isset($val->base_price)? $val->base_price : 'N/A',
                        isset($val->sku)? $val->sku : 'N/A',
                        isset($val->publish_date)? $val->publish_date : 'N/A',
                        isset($image_url)? $image_url : 'N/A',
                        isset($val->status)? $val->status : 'N/A'
                      
                    ];
    
           //  Write data to a CSV file.
                   fputcsv($handle, $data);
               }
        
    
           // Close CSV file handle
           fclose($handle);
        }, 200, $headers);
    }

    public function download_csv()
    {     $filename = 'Catalog-data.csv';
    
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
    
        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
    
          //  Add CSV headers
            fputcsv($handle, [
                
                'Title',
                'Base price',
                'SKU',
                'Publish Date',
                'Image',
                'Status'
            ]);
    


                
           //  Extract data from each employee.
                   $id = '';
                    $data = [
                      
                        ($id),
                        ($id),
                        ($id),
                        ($id),
                        ($id),
                        ($id),
                      
                    ];
    
           //  Write data to a CSV file.
                   fputcsv($handle, $data);
      
    
           // Close CSV file handle
           fclose($handle);
        }, 200, $headers);
    }

    public function importCSV(Request $request)
    {
      
      $auth =   $request->validate([
            'import_csv' => 'required',
        ]);
    
        //read csv file and skip data
        $file = $request->file('import_csv');
        $handle = fopen($file->path(), 'r');

        //skip the header row
       fgetcsv($handle);

       $chunksize = 25;
       while(!feof($handle))
       {
            $chunkdata = [];

            for($i = 0; $i<$chunksize; $i++)
            {
                $data = fgetcsv($handle);
                if($data === false)
                {
                    break;
                }
                $chunkdata[] = $data; 
            }

           $this->getchunkdata($chunkdata);
       }
         fclose($handle);

        return redirect()->route('catalogs.index')->with('success', 'CSV File Uploaded Successfully');
    }

    public function getchunkdata($chunkdata)
    {
        foreach($chunkdata as $column){
            $title = $column[0];
            $base_price = $column[1];
            $sku = $column[2];
            $publish_date = $column[3];
            $publish_date =  date('Y-m-d', strtotime($publish_date));
            $image = $column[4];
            $status = $column[5];
           
     
           // upload image in folder  
//---------------------------------------------------------------------------------------
            if(empty($image))
            {
                $employee = new Catalog();
                $employee->title = $title;
                $employee->base_price = $base_price;
                $employee->sku = $sku;
                $employee->publish_date = $publish_date;
                $employee->image = '';
                $employee->status = $status;
                $employee->save();
            } else{
                // Extracting filename from URL
                $filename = uniqid() . '_' . basename($image);
                // Initialize cURL session
                $ch = curl_init($image);
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
                
              $saveDirectory = storage_path('\app\public\Catalogs');
             
          
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
                // Returning the file path if successful, otherwise null
             


                $employee = new Catalog();
                $employee->author_id = auth()->user()->id;
                $employee->title = $title;
                $employee->base_price = $base_price;
                $employee->sku = $sku;
                $employee->publish_date = $publish_date;
                $employee->image = "Catalogs/".$filename;
                $employee->status = $status;
                $employee->save();

               return $saved ? $filePath : null; 
            }
//-------------------------------------------------------------------------------------------
        }
    }   

    public function  catalog_api()
    {
        $client = new Client(); 
        $response = $client->request('GET', 'https://staging.recollection.com/wp-json/custom/v3/user-count-by-catalog', ['verify' => false]);
        
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody());
            $result =  $data->data;
            foreach ($result as $val) {
              print_r($val);
            }
        } else {
            // Handle error
            return null;
        }
        
    }

}