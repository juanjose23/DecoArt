<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
class ExportPdfController extends Controller
{
    //
public function pdf($record)
    {
        // Usar transacción para asegurar la consistencia de los datos
        DB::beginTransaction();
        $user = User::find($record);

        try {
            $html = view('pdf.user_credentials', ['user' => $user])->render();

            // Instantiate Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // Set paper size and orientation
            $dompdf->setPaper('A4', 'portrait'); // Cambié 'A1' a 'A4'

            // Render PDF (important step!)
            $dompdf->render();

            // Cambia la contraseña y estado del usuario
            $user->password = User::encryptPassword($user->password); // Asegúrate que este método esté implementado
            $user->estado = 1;
            $user->save();

            // Confirmar la transacción
            DB::commit();

            // Output PDF to browser
            return $dompdf->stream('document.pdf');

        } catch (\Exception $e) {
            // Rollback de la transacción en caso de error
            DB::rollback();

            // Manejar el error según sea necesario
            return response()->json(['error' => 'Error al generar el PDF: ' . $e->getMessage()], 500);
        }
    }

}
