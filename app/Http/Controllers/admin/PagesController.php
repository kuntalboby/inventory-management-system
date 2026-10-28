<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\admin\ParentController;

use App\Models\FrontendImage;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PagesController extends ParentController
{
    //

    public function index(Request $request){
        $pages = Page::get();
       // return $pages;
            return view("admin.pages.index",[
            "pages"=> $pages,
            ]);
            
    }
    public function imageDictionary( Request $request, $slug){
        $images = FrontendImage::where('type',$slug)->get();
        return view("admin.pages.images",[
        "images"=> $images,
        'slug'=>$slug
        ]);
        
    }


public function imageAdd(Request $request)
{
    $request->validate([
        'files'   => 'required|array',
        'files.*' => 'required|file|mimes:jpg,jpeg,png|max:3072',
        'type'=>'required'
    ]);

    
    foreach ($request->file('files') as $file) {
        $rand = $this->randnoGenerator('4') . $this->randnoGenerator('4');
        $filename = Str::uuid() . '_' . $rand . '.' . $file->getClientOriginalExtension();

        $file->storeAs('frontend/pages', $filename, 'public');
        FrontendImage::create(['name'=>$filename,'type'=>$request->type]);
    }

    return redirect()->route('admin.pages.images',$request->type)->with('message', 'Images have been uploaded successfully.');
}

public function imageDel( Request $request){
    if ($request->ajax()) {
        
        $tble = $request->tbl;
        if($tble == 'img' ){
                $imageName = $request->search;
                $results = FrontendImage::where(['name'=>$imageName,'type'=>'HS'])->delete();
        }elseif($tble == 'testimon' ){
                $imageName = $request->imageName;
                $id = $request->id;
                $results = Testimonial::where(['id'=>$id])->delete();
        }
        
        if (!empty($results)) {
            $oldImagePath = public_path("storage/frontend/pages/" . $imageName);
            if (file_exists($oldImagePath)) {
                @unlink($oldImagePath);
            }
        }
        return response()->json($results);
    }
}


public function testimonials( Request $request){
        $testimonials = Testimonial::get();
        return view("admin.pages.testimonials",[
        "testimonials"=> $testimonials,
        ]);
        
    }
    public function testimonialEdit( Request $request,$slug){
        $testimonial = Testimonial::where('id',$slug)->firstOrFail();
        //return $testimonial;
        return view("admin.pages.testimonial-edit",[
        "getInfo"=> $testimonial,
        ]);
        
    }

    public function testimonialStore(Request $request)
    {
        //dd($request->file);
        $request->validate([
            
            'image' => 'nullable|mimes:png,jpg,jpeg|max:3000',
            'author'=>'required',
            'content' => [
                'required', // Description can be empty
                'max:1200',
                'min:2',
            ]

        ]);
        

        if ($request->file('image') != null){

            $categoryImage = $request->file('image');
            $testimonialImageNm = $categoryImage->hashName();
            $request->image->storeAs('frontend/pages',$testimonialImageNm,'public');
            $savedInfo = array_merge($request->all(), ['image' =>  $testimonialImageNm]);
        }else{
            $savedInfo  = $request->all();
        }

        $updateRes = Testimonial::create($savedInfo);
       
        if($updateRes){
                   
            session()->flash('response','success');
            return redirect()->route('admin.pages.testimonials')->with('message','Information has been added successfully.');
            
            
        }else{
            session()->flash('response','danger');
            return redirect()->back()->with('message','Information has not been added.')->withInput();
        }
    }

    public function testimonialUpdate(Request $request, string $slug)
{
    // Find the category (returns a single model, not a collection)
    $testimonial = Testimonial::where('id', $slug)->firstOrFail();

    // Validate request data
    $request->validate([
            
            'image' => 'nullable|mimes:png,jpg,jpeg|max:3000',
            'author'=>'required',
            'content' => [
                'required', 
                'max:1200',
                'min:2',
            ]

        ]);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image');
        $imageFileName = $imagePath->hashName();
        $request->image->storeAs('frontend/pages', $imageFileName, 'public');

        // Delete old image if it exists
        if (!empty($testimonial->image)) {
            $oldImagePath = public_path("storage/frontend/pages/" . $testimonial->image);
            if (file_exists($oldImagePath)) {
                @unlink($oldImagePath);
            }
        }

        $newData['image'] = $imageFileName;
    } else {
        $newData['image'] = $testimonial->image; 
    }
   
    $requestData = array_merge($request->except(['_token', '_method']), $newData );

    // Update the category
    $updateRes = $testimonial->update($requestData);

    // Redirect based on success or failure
    if ($updateRes) {
         
                        
        session()->flash('response', 'success');
        return redirect()->route('admin.pages.testimonials')->with('message', 'Information has been updated successfully.');
    } else {
        session()->flash('response', 'danger');
        return redirect()->back()->with('message', 'Information has not been updated.')->withInput();
    }
}

    public function pageAdd(Request $request){

        //return $request;
        $request->validate([
                    
                    'title' => 'required',
                    'author'=>'nullable|max:220',
                    'body_class'=>'nullable|max:200',
                    'body_id'=>'nullable|max:50',
                    'meta_keyword' => [
                        'nullable', 
                        'max:1200',
                        'min:2',
                    ],
                    'meta_description' => [
                        'nullable', 
                        'max:1200',
                        'min:2',
                    ]
                ]);
            $page = Page::create([
                    'title' => $request->title,
                    'author' => $request->author,
                    'body_class' => $request->body_class,
                    'body_id' => $request->body_id,
                    'bg_image' => $request->bg_image,
                    'meta_keyword' => $request->meta_keyword,
                    'meta_description' => $request->meta_description
                ]);

                // 2. Insert Data Array into page_sections
                if ($request->has('data')) {
                    foreach ($request->data as $section) {
                        $page->pageSections()->create([
                            'sort_position' => $section['sort_position'],
                            'heading' => $section['heading'] ?? null,
                            'content' => $this->cleanCkeditorContent($section['content']) ?? null
                        ]);
                    }
                }

            if ($page) {
                session()->flash('response', 'success');
                return redirect()->route('admin.pages')->with('message', 'Information has been updated successfully.');
            } else {
                session()->flash('response', 'danger');
                return redirect()->back()->with('message', 'Information has not been updated.')->withInput();
            }
                
        //    $createdPage = Page::create($re) ;    
    }

    
    public function pageEdit(Request $request,string $slug){
       $page = Page::with(['pageSections' => function ($q) {
                    $q->orderBy('sort_position');
                }])
                ->where('slug', $slug)
                ->firstOrFail();

            return view("admin.pages.edit", [
                "page" => $page,
            ]);


            
    }

    public function pageUpdate(Request $request, $slug){
       // return $request;
            $request->validate([
            'title' => 'required|string',
            'data'  => 'nullable|array',
        ]);
            $page = Page::where('slug', $slug)->firstOrFail();

            $updatePage = $page->update($request->only([
            'title',
            'author',
            'body_class',
            'body_id',
            'meta_keyword',
            'meta_description',
            'bg_image'
        ]));
         PageSection::where('page_id', $page->id)->delete();
        if (isset($request->data) && count($request->data) > 0) {
       
   
       
        PageSection::where('page_id', $page->id)->delete();

        // Prepare insert data
        $insertData = [];
        foreach ($request->data as $section) {
            // Skip if heading & content are both empty
            if (
                (empty($section['heading']) || trim($section['heading']) === '') &&
                (empty($section['content']) || trim($section['content']) === '')
            ) {
                continue;
            }

            $insertData[] = [
                'page_id'       => $page->id,
                'sort_position' => $section['sort_position'] ?? 0,
                'heading'       => $section['heading'] ?? '',
                'content'       => $section['content'] ?? '',
            ];
        }
        if (!empty($insertData)) {
            PageSection::insert($insertData);
        }
        }

        if ($updatePage) {
            session()->flash('response', 'success');
            return redirect()->route('admin.pages')->with('message', 'Information has been updated successfully.');
        } else {
            session()->flash('response', 'danger');
            return redirect()->back()->with('message', 'Information has not been updated.')->withInput();
        }
    }

    

}
