<?php
namespace App\Http\Controllers;

use App\Models\Localidad;

class LocalidadController extends Controller
{
    public function getLocalidades($idProv)
    {
        try {
            $localidades = Localidad::where('id_prov', $idProv)->orderBy('localidad', 'asc')->get();
            return response()->json($localidades);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCodigosPostales($idLocal)
    {
        Try {
            $codigosPostales = Localidad::where('id_local', $idLocal)->get();
            return response()->json($codigosPostales);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
