<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\DiaryAnalysis; // Import model analisis jika perlu hapus manual
use App\Jobs\AnalyzeDiaryMoodJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiaryController extends Controller
{

    public function index()
    {
       $diaries = Diary::with('analysis')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('diaries.index', compact('diaries'));
    }

    public function show($id)
    {
       $diary = Diary::with('analysis')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('diaries.show', compact('diary'));
    }

   public function create()
    {
        return view('diaries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:10',
            'is_private' => 'required|boolean',
        ]);

        $diary = Diary::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'is_private' => $request->input('is_private'),
        ]);

        AnalyzeDiaryMoodJob::dispatch($diary->id);

        return redirect()->route('dashboard')->with('success', 'Diary berhasil disimpan dan sedang dianalisis KA.');
    }

    public function edit($id)
    {
        $diary = Diary::where('user_id', Auth::id())->findOrFail($id);

        return view('diaries.edit', compact('diary'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|min:10',
            'is_private' => 'required|boolean',
        ]);

        $diary = Diary::where('user_id', Auth::id())->findOrFail($id);

        $oldContent = $diary->content;
        $newContent = $request->input('content');

        $diary->update([
            'content' => $newContent,
            'is_private' => $request->input('is_private'),
        ]);

      if ($oldContent !== $newContent) {
            if ($diary->analysis) {
                $diary->analysis->delete();
            }

            AnalyzeDiaryMoodJob::dispatch($diary->id);

            $message = 'Diary diperbarui dan sedang dianalisis ulang oleh KA.';
        } else {
            $message = 'Status privasi diary berhasil diperbarui.';
        }

        return redirect()->route('diaries.index')->with('success', $message);
    }

    public function destroy($id)
    {
        $diary = Diary::where('user_id', Auth::id())->findOrFail($id);

       $diary->delete();

        return redirect()->route('diaries.index')->with('success', 'Diary berhasil dihapus.');
    }
}
