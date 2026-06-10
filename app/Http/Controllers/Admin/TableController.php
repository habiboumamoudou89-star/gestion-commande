<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableController extends Controller
{
    public function index()
    {
        $etab   = auth()->user()->etablissement;
        $tables = $etab ? $etab->tables()->with('commandeEnCours')->get() : collect();
        return view('admin.tables.index', compact('tables', 'etab'));
    }

    public function store(Request $request)
    {
        $request->validate(['numero' => 'required|string|max:50']);
        $etab  = auth()->user()->etablissement;
        $table = $etab->tables()->create(['numero' => $request->numero]);
        $this->generateQr($table);
        return back()->with('success', 'Table créée avec QR code.');
    }

    public function destroy(RestaurantTable $table)
    {
        if ($table->qr_code) Storage::disk('public')->delete($table->qr_code);
        $table->delete();
        return back()->with('success', 'Table supprimée.');
    }

    public function regenererQr(RestaurantTable $table)
    {
        $table->update(['qr_token' => \Str::uuid()]);
        $this->generateQr($table);
        return back()->with('success', 'QR code régénéré.');
    }

    public function downloadQr(RestaurantTable $table)
    {
        $path = storage_path('app/public/' . $table->qr_code);
        return response()->download($path, 'qr-table-' . $table->numero . '.png');
    }

    private function generateQr(RestaurantTable $table): void
    {
        $url  = route('client.menu', $table->qr_token);
        $qr   = QrCode::format('png')->size(400)->margin(2)->generate($url);
        $path = 'qrcodes/table_' . $table->id . '.png';
        Storage::disk('public')->put($path, $qr);
        $table->update(['qr_code' => $path]);
    }
}
