<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cabang;
use Illuminate\Support\Facades\File;

$publicCabangs = public_path('uploads/cabangs');
$publicDenah = public_path('uploads/denah');

if (!File::exists($publicCabangs)) {
    File::makeDirectory($publicCabangs, 0755, true);
}
if (!File::exists($publicDenah)) {
    File::makeDirectory($publicDenah, 0755, true);
}

$cabangs = Cabang::all();
$updatedCount = 0;

foreach ($cabangs as $cabang) {
    $updated = false;

    // Migrate foto_cabang
    if ($cabang->foto_cabang) {
        $filename = basename($cabang->foto_cabang);
        $oldPath = storage_path('app/public/cabangs/' . $filename);
        $newPath = public_path('uploads/cabangs/' . $filename);
        
        if (File::exists($oldPath) && !File::exists($newPath)) {
            File::copy($oldPath, $newPath);
        }
        
        if ($cabang->foto_cabang !== 'uploads/cabangs/' . $filename) {
            $cabang->foto_cabang = 'uploads/cabangs/' . $filename;
            $updated = true;
        }
    }

    // Migrate denah_cabang
    if ($cabang->denah_cabang) {
        $filename = basename($cabang->denah_cabang);
        $oldPath = storage_path('app/public/denah/' . $filename);
        $newPath = public_path('uploads/denah/' . $filename);
        
        if (File::exists($oldPath) && !File::exists($newPath)) {
            File::copy($oldPath, $newPath);
        }
        
        if ($cabang->denah_cabang !== 'uploads/denah/' . $filename) {
            $cabang->denah_cabang = 'uploads/denah/' . $filename;
            $updated = true;
        }
    }

    if ($updated) {
        $cabang->save();
        $updatedCount++;
        echo "Updated Cabang ID {$cabang->id}\n";
    }
}

echo "Migration complete! Updated {$updatedCount} records.\n";
