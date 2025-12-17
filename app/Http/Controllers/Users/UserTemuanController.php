<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Temuan;
use App\Models\Patrol;
use App\Models\Member;

class UserTemuanController extends Controller
{
    /**
     * Tampilkan daftar temuan berdasarkan patrol
     */
    public function index($id)
    {
        if (!Session::has('login_id') || Session::get('login_type') != 2) {
            return redirect()->route('login')->withErrors(['unauthorized' => 'Silakan login terlebih dahulu.']);
        }

        $patrol = Patrol::findOrFail($id);
        $member = Member::where('nik', Session::get('login_nik'))->first();
        if (!$member) {
            return redirect()->route('login')->withErrors(['unauthorized' => 'Sesi login sudah berakhir.']);
        }

        $temuans = Temuan::with('patrol')
            ->where('Id_Patrol', $id)
            ->where('Id_Member', $member->id)
            ->get();

        return view('users.temuans.index', compact('temuans', 'patrol', 'member'));
    }

    /**
     * Simpan temuan baru (dari AJAX + TUI Editor)
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'Desc_Temuan' => 'required|string|max:1000',
            'Path_Temuan' => 'required', // bisa base64 atau file
        ]);

        $member = Member::where('nik', Session::get('login_nik'))->first();
        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid.']);
        }

        $pathTemuan = $this->handleImageInput($request, 'Path_Temuan');

        if (!$pathTemuan) {
            return response()->json(['success' => false, 'message' => 'Gagal memproses gambar.']);
        }

        $temuan = Temuan::create([
            'Path_Temuan'   => $pathTemuan,
            'Desc_Temuan'   => $request->Desc_Temuan,
            'Id_Patrol'     => $id,
            'Id_Member'     => $member->id,
            'Status_Temuan' => 'Pending',
        ]);

        return response()->json([
            'success' => true,
            'temuan' => [
                'id' => $temuan->Id_Temuan,
                'foto_url' => asset('uploads/' . $temuan->Path_Temuan),
                'desc' => $temuan->Desc_Temuan,
            ]
        ]);
    }

    /**
     * Update temuan (dari AJAX + TUI Editor)
     */
    public function update(Request $request, $id)
    {
        // Jika request berupa JSON (misal: edited_image base64), merge ke input
        if ($request->isJson()) {
            $requestData = $request->json()->all();
            $request->merge($requestData);
        }

        $request->validate([
            'Desc_Temuan' => 'required|string|max:1000',
        ]);

        $temuan = Temuan::findOrFail($id);

        // Prioritaskan edited_image (hasil edit TUI)
        if ($request->filled('edited_image') && Str::startsWith($request->edited_image, 'data:image')) {
            $newPath = $this->handleImageInput($request, 'edited_image');
            if ($newPath) {
                // Hapus file lama
                if ($temuan->Path_Temuan && file_exists(public_path('uploads/' . $temuan->Path_Temuan))) {
                    unlink(public_path('uploads/' . $temuan->Path_Temuan));
                }
                $temuan->Path_Temuan = $newPath;
            }
        }
        // Jika ada upload file baru (opsional)
        elseif ($request->hasFile('Path_Temuan')) {
            $newPath = $this->handleImageInput($request, 'Path_Temuan');
            if ($newPath) {
                if ($temuan->Path_Temuan && file_exists(public_path('uploads/' . $temuan->Path_Temuan))) {
                    unlink(public_path('uploads/' . $temuan->Path_Temuan));
                }
                $temuan->Path_Temuan = $newPath;
            }
        }

        $temuan->Desc_Temuan = $request->Desc_Temuan;
        $temuan->save();

        return response()->json(['success' => true, 'message' => 'Temuan berhasil diperbarui.']);
    }

    /**
     * Hapus temuan
     */
    public function destroy($id)
    {
        $temuan = Temuan::findOrFail($id);
        $idPatrol = $temuan->Id_Patrol;

        // Hapus file
        if ($temuan->Path_Temuan && file_exists(public_path('uploads/' . $temuan->Path_Temuan))) {
            unlink(public_path('uploads/' . $temuan->Path_Temuan));
        }

        $temuan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data temuan berhasil dihapus.'
        ]);
    }

    /**
     * Helper: Proses input gambar (base64 atau file upload)
     */
    private function handleImageInput(Request $request, $inputName)
    {
        $folder = public_path('uploads/temuans');
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        // === Jika input adalah base64 (dari TUI Editor) ===
        if ($request->filled($inputName) && Str::startsWith($request->{$inputName}, 'data:image')) {
            $base64 = $request->{$inputName};
            if (!preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
                return null;
            }

            $data = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
            $binary = base64_decode($data);
            if (!$binary) return null;

            $filename = Str::uuid() . '.jpg';
            file_put_contents($folder . '/' . $filename, $binary);
            return 'temuans/' . $filename;
        }

        // === Jika input adalah file upload ===
        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            if (!$file->isValid()) return null;

            // Resize & kompres
            $image = imagecreatefromstring(file_get_contents($file));
            if (!$image) return null;

            $width = imagesx($image);
            $height = imagesy($image);
            $maxDim = 1280;

            if ($width > $maxDim || $height > $maxDim) {
                $ratio = min($maxDim / $width, $maxDim / $height);
                $newWidth = intval($width * $ratio);
                $newHeight = intval($height * $ratio);
                $resized = imagecreatetruecolor($newWidth, $newHeight);

                if (imageistruecolor($image)) {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                    $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                    imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
                }

                imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($image);
                $image = $resized;
            }

            $filename = Str::uuid() . '.jpg';
            $fullPath = $folder . '/' . $filename;

            // Kompresi hingga <1MB
            $quality = 85;
            do {
                ob_start();
                imagejpeg($image, null, $quality);
                $data = ob_get_clean();
                $size = strlen($data);
                $quality -= 5;
            } while ($size > 1024 * 1024 && $quality > 10);

            file_put_contents($fullPath, $data);
            imagedestroy($image);

            return 'temuans/' . $filename;
        }

        return null;
    }
}
