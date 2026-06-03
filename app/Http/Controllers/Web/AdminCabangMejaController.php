<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminCabangMejaController extends Controller
{
    public function index(): View
    {
        $mejas = Meja::where(
            'cabang_id',
            Auth::user()->cabang_id
        )
            ->orderBy('nomor_meja')
            ->get();

        return view(
            'admin-cabang.meja.index',
            compact('mejas')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_meja' => 'required|max:20',
            'kapasitas'  => 'required|integer|min:1|max:50',
        ]);

        Meja::create([
            'nomor_meja' => $validated['nomor_meja'],
            'kapasitas'  => (int) $validated['kapasitas'],
            'status'     => 'active',
            'cabang_id'  => Auth::user()->cabang_id,
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Meja berhasil ditambahkan'
            );
    }

    public function update(
        Request $request,
        string $id
    ): RedirectResponse {

        $validated = $request->validate([
            'nomor_meja' => 'required|max:20',
            'kapasitas'  => 'required|integer|min:1|max:50',
        ]);

        $meja = Meja::where(
            '_id',
            $id
        )
            ->where(
                'cabang_id',
                Auth::user()->cabang_id
            )
            ->firstOrFail();

        $meja->update([
            'nomor_meja' => $validated['nomor_meja'],
            'kapasitas'  => (int) $validated['kapasitas'],
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Meja berhasil diperbarui'
            );
    }

    public function destroy(
        string $id
    ): RedirectResponse {

        $meja = Meja::where(
            '_id',
            $id
        )
            ->where(
                'cabang_id',
                Auth::user()->cabang_id
            )
            ->firstOrFail();

        $meja->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Meja berhasil dihapus'
            );
    }

    public function aktifkan(
        string $id
    ): RedirectResponse {

        $meja = Meja::where(
            '_id',
            $id
        )
            ->where(
                'cabang_id',
                Auth::user()->cabang_id
            )
            ->firstOrFail();

        $meja->update([
            'status' => 'active'
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Meja berhasil diaktifkan'
            );
    }

    public function nonaktifkan(
        string $id
    ): RedirectResponse {

        $meja = Meja::where(
            '_id',
            $id
        )
            ->where(
                'cabang_id',
                Auth::user()->cabang_id
            )
            ->firstOrFail();

        $meja->update([
            'status' => 'inactive'
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Meja berhasil dinonaktifkan'
            );
    }
}
