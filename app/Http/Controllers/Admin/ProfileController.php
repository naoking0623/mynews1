<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 以下の1行を追記することで、News Modelが扱えるようになる
use App\Models\Profile;
use App\Models\ProfileHistory;
use Carbon\Carbon;
class ProfileController extends Controller
{
     public function index(Request $request)
    {
        $cond_name = $request->cond_name;
        if ($cond_name != '') {
            // 検索されたら検索結果を取得する
            $posts = Profile::where('name', $cond_name)->get();
        } else {
            // それ以外はすべてのニュースを取得する
            $posts = Profile::all();
        }
        
        return view('admin.profile.index', ['posts' => $posts, 'cond_name' => $cond_name]);
    }
    //
    public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
         // 以下を追記
        // Validationを行う
        $this->validate($request, profile::$rules);

        $profile = new profile;
        $form = $request->all();
        

        // フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
       

        // データベースに保存する
        $profile->fill($form);
        $profile->save();
        return redirect('admin/profile/');
    }

    public function edit(Request $request)
    {
         // News Modelからデータを取得する
        $profile = Profile::find($request->id);
        if (empty($profile)) {
            abort(404);
        }
           return view('admin.profile.edit', ['profile_form' => $profile]);
    }
    public function update(Request $request)
    {
        // dd($request);
           // Validationをかける
        $this->validate($request, Profile::$rules);
        // News Modelからデータを取得する
        $profile = Profile::find($request->id);
        // 送信されてきたフォームデータを格納する
        $profile_form = $request->all();
        // dd($profile_form);
        unset($profile_form['_token']);
        // 該当するデータを上書きして保存する
        // $profile->fill($profile_form)->save();
        $profile->fill($request->all())->save();
        // dd($profile);
         // 以下を追記
        $history = new ProfileHistory();
        $history->profile_id = $profile->id;
        $history->edited_at = Carbon::now();
        $history->save();

        return redirect('admin/profile');
    }
    
     public function delete(Request $request)
    {
        // 該当するNews Modelを取得
        $profile = Profile::find($request->id);

        // 削除する
        $profile->delete();

        return redirect('admin/profile/');
    }
}

