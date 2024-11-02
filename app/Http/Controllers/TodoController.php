<?php
namespace App\Http\Controllers;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class TodoController extends Controller
{
    public function index()
    {
        $auth = Auth::user();
        $todos = Todo::where("user_id", $auth->id)->orderBy("created_at", "desc")->get();
        $data = [
            "auth" => $auth,
            "todos" => $todos
        ];
        return view("app.home", $data);
    }
    public function postAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity' => 'required|string|max:255',
            'target' => 'nullable|integer|min:1' // Tambahkan validasi untuk target
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('home')
                ->withErrors($validator)
                ->withInput();
        }

        $auth = Auth::user();
        Todo::create([
            "user_id" => $auth->id,
            "activity" => $request->activity,
            "target" => $request->target ?? 1 // Default target = 1 jika tidak diisi
        ]);

        return redirect()->route("home");
    }

    public function postEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:todos',
            'activity' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->route('home')
                ->withErrors($validator)
                ->withInput();
        }
        $auth = Auth::user();
        $todo = Todo::where("id", $request->id)->where(
            "user_id",
            $auth->id
        )->first();
        if ($todo) {
            $todo->activity = $request->activity;
            $todo->status = $request->status;
            $todo->save();
        }
        return redirect()->route("home");
    }
    public function postDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:todos',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->route('home')
                ->withErrors($validator)
                ->withInput();
        }
        $auth = Auth::user();
        $todo = Todo::where("id", $request->id)->where(
            "user_id",
            $auth->id
        )->first();
        if ($todo) {
            $todo->delete();
        }
        return redirect()->route("home");
    }

    public function incrementProgress(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);

        // Hanya menambah progres jika belum mencapai target
        if ($todo->progress < $todo->target) {
            $todo->progress += 1;
            $todo->save();
        }

        return redirect()->back()->with('success', 'Progres bertambah!');
    }

}