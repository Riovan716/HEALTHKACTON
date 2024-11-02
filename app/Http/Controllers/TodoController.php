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
            'target' => 'nullable|integer|min:1', // Validasi untuk target
            'frequency' => 'required|in:daily,weekly' // Validasi untuk frekuensi (daily atau weekly)
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
            "target" => $request->target ?? 1, // Default target = 1 jika tidak diisi
            "frequency" => $request->frequency // Simpan frekuensi yang dipilih oleh pengguna
        ]);

        return redirect()->route("home")->with('success', 'Todo berhasil ditambahkan!');
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

        $todo = Todo::findOrFail($request->id);

        // Tambah koin jika task selesai
        if (!$todo->status && $request->status == 1) {
            $todo->coins += 10; // Tambahkan 10 koin ke task
            $todo->save(); // Simpan perubahan koin di todo

            // Tambahkan koin ke pengguna
            $user = Auth::user();
            $user->coins += 10; // Menambahkan 10 koin ke pengguna
            $user->save(); // Simpan perubahan koin di pengguna
        }

        $todo->activity = $request->activity;
        $todo->status = $request->status;
        $todo->save();

        return redirect()->route("home")->with('success', 'Todo berhasil diubah! Koin Anda telah bertambah.');
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

    public function incrementProgress($id)
    {
        $todo = Todo::findOrFail($id);
        $user = Auth::user(); // Ambil pengguna yang sedang login

        // Tambahkan progres jika belum mencapai target
        if ($todo->progress < $todo->target) {
            $todo->progress++;

            // Jika progres telah mencapai target, set status menjadi selesai
            if ($todo->progress >= $todo->target) {
                $todo->status = true; // Atur status menjadi selesai
                $todo->coins += 10; // Tambahkan koin ke tugas
                $user->coins += 10; // Tambahkan koin ke pengguna
            }

            $todo->save(); // Simpan perubahan pada todo
            $user->save(); // Simpan perubahan pada pengguna
        }

        return redirect()->route('home')->with('success', 'Progres berhasil ditambahkan!');
    }


}