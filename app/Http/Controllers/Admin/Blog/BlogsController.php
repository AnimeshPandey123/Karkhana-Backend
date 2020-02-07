<?php

namespace App\Http\Controllers\Admin\Blog;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Model\Blog;
use App\Model\Language;
use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Blog\BlogsController as BaseBlogsController;

class BlogsController extends BaseBlogsController
{

    public function __construct() {
        $this->showUnpublished = true;
        $this->language = Language::where('language', 'en')->first();
    }

    public function getUUID() {
        $uuid = Str::uuid();

        return response()->json([
            'message' => 'UUID generated successfully!',
            'status' => 'success',
            'data' => [
                'uuid'=> $uuid,
            ],
        ]);
    }

    /**
     * Function to create new BLOG
     * @return json
     */
    public function create(Request $request) {
        $this->validate($request, [
            'uuid' => 'required',
            'title' => 'required',
        ]);
        
        $uuid = $request->uuid;
        $user = Auth::user();
        $tags = $request->tags;

        \DB::beginTransaction();
        try {
            $blog = Blog::where('uuid', $uuid)->first();
            
            if ($blog) {
                $blog->update([
                    'author' => $user->id, 
                    'featured' => $request->featured,
                ]);
            } else {
                $blog = Blog::create([
                    'uuid' => $uuid,
                    'author' => $user->id,
                    'featured' => $request->featured,
                ]);
            }

            $blog->tags()->sync($tags);

            $translation = $blog->translations()->where('language_id', $this->language->id)->first();
            if($translation) {
                $translation->update([
                    'language_id' => $this->language->id,
                    'title' => $request->title,
                    'body' => $request->body,
                    'read_time' => round(strlen(strip_tags($request->body))/200),
                ]);
            } else {
                $blog->translations()->create([
                    'language_id' => $this->language->id,
                    'title' => $request->title,
                    'body' => $request->body,
                    'read_time' => round(strlen(strip_tags($request->body))/200),
                ]);
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error($e);
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error',
            ]);
        }

        return response()->json([
            'message' => 'Blog created/updated successfully!',
            'status' => 'success',
            'data' => [
                'uuid'=> $uuid,
            ],
        ]);
    }

    /**
     * AWS S3 only provides fully qulaified url if temporaryURL is used like this: 
     * Storage::disk('s3')->temporaryURL('images/3b8ad2c7b1be2caf24321c852103598a.jpg', \Carbon\Carbon::now()->addMinutes(15));
     */
}
