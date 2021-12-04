<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        // POSTデータをjsonで取れなかったので実装方法を変更
        // $params = $request->json()->all();
        // $content = $params['message'];

        list(, $image) = explode(';', $request->get('image'));
        list(, $image) = explode(',', $image);
        $decodedImage = base64_decode($image);
        $content = $request->get('message');

        $id = DB::transaction(function () use ($decodedImage, $content) {
            $id = Str::uuid();
            $file = $id->toString() . '.jpg';
            Message::created([
                'id' => $id,
                'content' => $content,
                'file_path' => $file
            ]);

            $isSuccess = Storage::disk('s3')->put($file, $decodedImage);
            if (!$isSuccess) {
                throw new \Exception('ファイルアップロードに失敗しました');
            }
            Storage::disk('s3')->setVisibility($file, 'public');

            return $id;
        });


        return response()->json($id);
    }
}
