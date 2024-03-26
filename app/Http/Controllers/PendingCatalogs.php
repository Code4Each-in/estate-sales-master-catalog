<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\AddPendingCatalog;
use App\Http\Requests\UpdatePendingCatalog;
use App\Models\Catalog;
use App\Models\PendingCatalog;
use Illuminate\Http\Request;

class PendingCatalogs extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {    
        $catalogs = PendingCatalog::whereNot('status','publish')->get();
        // $catalogs = PendingCatalog::all();


        return view('pending-catalogs.index',compact('catalogs'));
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
    public function store(AddPendingCatalog $request)
    {
         //Request Data validation
         $validatedData = $request->validated();
        //  dd($validatedData);

                //Create User
                $pendingCatalog = PendingCatalog::create([
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
                        PendingCatalog::where('id', $pendingCatalog->id)->update(['image' => $path]);
        
                }
        
                $request->session()->flash('message','Catalog Added In Pending List.');
        
                return Response()->json(['status'=>200, 'catalog'=>$pendingCatalog]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $catalog = PendingCatalog::find($id);


        return Response()->json(['catalogs' =>$catalog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePendingCatalog $request, string $id)
    {
        //Request Data validation
        $validatedData = $request->validated();
       $pendingCatalogDetail = PendingCatalog::find($id);

        if($validatedData['status'] == 'decline'){
               PendingCatalog::where('id', $id)->update(['status'=> $validatedData['status']]);
            $request->session()->flash('message','Catalog Status Changes Successfully.');
            return Response()->json(['status'=>200]);
        }
        //Update User
        $catalogs = Catalog::create([
        'author_id' => auth()->user()->id,
         // 'name' => $validatedData['title'],
         'title' => $validatedData['title'],
         'wp_category_id' => $validatedData['category'],
         'content' => $validatedData['content'],
         'sku' => $validatedData['sku'],
         'base_price' => $validatedData['base_price'],
         'status' => $validatedData['status'],
         'image' => $pendingCatalogDetail->image,
         'publish_date' =>  now(),
         'updated_at' => now(),
     ]);

   

      //Only if Needs to update the preview image then this will update the image
      if ($request->hasFile('image')) {
         $oldFilePath = 'storage/'.$pendingCatalogDetail->image;
         //Delete The Old Stored Image in path And Upload New
         if (Helper::deleteFile($oldFilePath)) {
             $uploadedFile = $request->file('image');
             $filename = time() . '_' . $uploadedFile->getClientOriginalName();
             $uploadedFile->storeAs('public/Catalogs', $filename);
             $path = 'Catalogs/' . $filename;
             PendingCatalog::where('id', $id)->update(['image' => $path]);
             Catalog::where('id', $catalogs->id)->update(['image' => $path]);
         } else {
             return back()->with("File $oldFilePath not found.");
         }
     }
     PendingCatalog::where('id', $id)->update([
        'wp_category_id' => $validatedData['category'],
        'status'=> $validatedData['status'],
        'author_id' => auth()->user()->id,
        'sku' => $validatedData['sku'],
        'base_price' => $validatedData['base_price'],
        'master_catalog_id' => $catalogs->id
    ]);

     $request->session()->flash('message','Pending Catalog Moved to Catalogs Successfully.');
     return Response()->json(['status'=>200]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $catalog = PendingCatalog::findOrFail($id);

         $oldFilePath = 'storage/'.$catalog->image;
            // Delete The Old Stored Image in path And Upload New
            if (Helper::deleteFile($oldFilePath)) {
            } else {
                return back()->with("File $oldFilePath not found.");
            }

        $catalog->delete();

        session()->flash('message','Catalog Deleted From Pending List successfully.');
        return response()->json(['success' => true]);
    }

    // public function publishPendingCatalog(Request $request)
    // {
    //     // dd("here");
    //     // $response = "Success message";
    //     ;
    //     $pendingCatalog = PendingCatalog::find($request->id);
    

    //     return response()->json(['message' => $response]);
    // }
}