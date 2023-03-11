<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ユーザーIDの取得
        $user_id = Auth::id();

        // tasksテーブル内のレコードを条件で絞り込み
        $tasks = Task::where('status', false)
            ->where('user_id', $user_id)
            ->get();

        // 絞り込んだレコードをトップページに渡す
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ユーザーIDの取得
        $user_id = Auth::id();

        // バリデーションルールの設定
        $rules = [
            'task_name' => 'required | max:100',
        ];

        // バリデーションメッセージの設定
        $messages = [
            'required' => 'タスクを入力してください。',
            'max' => '100文字以下で入力してください。',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        // 取得したデータをtasksテーブルの各カラムに追加
        $task = new Task();
        $task->user_id = $user_id;
        $task->name = $request->input('task_name');

        $task->save();

        return redirect('/tasks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //該当のタスクを検索
        $task = Task::find($id);

        // 前日のタスクに対して、日をまたいで編集ボタンを押した時
        if (empty($task)) {
            //リダイレクト
            return redirect('/tasks');
        } else {
            return view('tasks.edit', compact('task'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Taskモデルからidに該当するレコードを取得して変数に保存
        $task = Task::find($id);

        // 前日のタスクに対して、日をまたいで"完了"ボタンを押した時
        // 編集画面で前日のタスクに対して、日をまたいで"編集する"ボタンを押した時
        if (empty($task)) {
            //リダイレクト
            return redirect('/tasks');
        }

        //「編集する」ボタンを押したとき
        if ($request->status === null) {
            $rules = ['task_name' => 'required|max:100'];

            $messages = ['required' => 'タスクを入力してください。', 'max' => '100文字以下で入力してください。'];

            Validator::make($request->all(), $rules, $messages)->validate();

            //モデル->カラム名 = 値 で、データを割り当てる
            $task->name = $request->input('task_name');

            //データベースに保存
            $task->save();
        } else {
            //「完了」ボタンを押したとき

            //モデル->カラム名 = 値 で、データを割り当てる
            $task->status = true; //true:完了、false:未完了

            //データベースに保存
            $task->save();
        }


        //リダイレクト
        return redirect('/tasks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
