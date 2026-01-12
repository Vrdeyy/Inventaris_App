<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        return view('admin.settings.template');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'type' => 'required|in:items,history',
            'file' => 'required|file|mimes:xlsx,xls,bin,zip,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);

        $type = $request->input('type'); // 'items' or 'history'

        // Simpan file dengan nama tetap: template_items.xlsx atau template_history.xlsx
        // Sehingga file baru akan selalu menimpa file lama
        $path = $request->file('file')->storeAs('templates', "template_{$type}.xlsx");

        return back()->with('success', "Template excel untuk laporan " . ucfirst($type) . " berhasil diperbarui.");
    }

    public function download($type)
    {
        $path = "templates/template_{$type}.xlsx";

        if (!Storage::exists($path)) {
            return back()->with('error', "Template belum tersedia.");
        }

        return Storage::download($path);
    }
}
