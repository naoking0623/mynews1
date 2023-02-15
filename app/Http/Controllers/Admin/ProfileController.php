<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 以下の1行を追記することで、News Modelが扱えるようになる
use App\Models\profile;
class ProfileController extends Controller
{
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
        return redirect('admin/profile/create');
    }

    public function edit()
    {
        return view('admin.profile.edit');
    }

    public function update()
    {
        return redirect('admin/profile/edit');
    }
}
