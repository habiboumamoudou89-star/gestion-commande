<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;

class EtablissementController extends Controller
{
    public function index()
    {
        $etablissements = Etablissement::with('admin')->withCount('tables', 'menus')->get();
        return view('superadmin.etablissements.index', compact('etablissements'));
    }
}