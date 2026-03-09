<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use \App\Helpers\CuitHelper;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function index()
    {
        return view('admin.clientes.index');
    }

    public function getData(Request $request)
    {
        try {
            $search = $request->get('search')['value'] ?? '';
            
            \Log::info('Petición DataTable completa:', $request->all());
            
            // Solo procesar si hay un término de búsqueda válido
            if (empty($search) || strlen(trim($search)) < 1) {
                \Log::info('Búsqueda vacía, devolviendo resultados vacíos');
                return response()->json([
                    'draw' => intval($request->get('draw')),
                    'data' => [],
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0
                ]);
            }
            
            $searchTerm = trim($search);
            \Log::info("Búsqueda de clientes con término: '{$searchTerm}'");
            
            $query = Cliente::query();
            
            // Aplicar filtros más específicos
            $query->where(function($q) use ($searchTerm) {
                $q->where('tipodoc', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('documento', 'LIKE', "%{$searchTerm}%") 
                  ->orWhere('sexo', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('apelnombres', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('cuit', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('localidad', function($localQuery) use ($searchTerm) {
                      $localQuery->where('provincia', 'LIKE', "%{$searchTerm}%");
                  });
            });
            
            $clientes = $query->with('localidad')->get();
            \Log::info("Encontrados " . count($clientes) . " clientes");
            
            // Filtro adicional en PHP para asegurar coincidencia
            $clientesFiltrados = $clientes->filter(function($cliente) use ($searchTerm) {
                $contiene = (
                    stripos($cliente->tipodoc ?? '', $searchTerm) !== false ||
                    stripos($cliente->documento ?? '', $searchTerm) !== false ||
                    stripos($cliente->sexo ?? '', $searchTerm) !== false ||
                    stripos($cliente->apelnombres ?? '', $searchTerm) !== false ||
                    stripos($cliente->cuit ?? '', $searchTerm) !== false ||
                    stripos($cliente->localidad->provincia ?? '', $searchTerm) !== false
                );
                
                if (!$contiene) {
                    \Log::debug("Cliente ID {$cliente->id} ({$cliente->apelnombres}) NO contiene '{$searchTerm}' - Excluido");
                }
                
                return $contiene;
            });
            
            $data = [];
            $linea = 1;
            
            foreach ($clientesFiltrados as $cliente) {
                $acciones = '<a href="' . url('admin/clientes/' . $cliente->id) . '" type="button" class="btn btn-success btn-sm" title="Ver cliente"><i class="bi bi-eye"></i></a> ';
                $acciones .= '<a href="' . url('admin/clientes/' . $cliente->id . '/edit') . '" type="button" class="btn btn-info btn-sm" title="Editar cliente"><i class="bi bi-pencil"></i></a>';
                
                if (Auth::user() && Auth::user()->hasRole('admin')) {
                    $acciones .= ' <a href="' . url('admin/clientes/' . $cliente->id . '/confirm-delete') . '" type="button" class="btn btn-danger btn-sm" title="Eliminar cliente"><i class="bi bi-trash"></i></a>';
                }
                
                // Calcular edad
                $edad = '-';
                if ($cliente->nacimiento) {
                    $fechaNac = \Carbon\Carbon::parse($cliente->nacimiento);
                    $edad = $fechaNac->age;
                }
                
                $data[] = [
                    $linea++,
                    $cliente->tipodoc ?? '-',
                    $cliente->documento ?? '-', 
                    $cliente->sexo ?? '-',
                    $cliente->apelnombres ?? '-',
                    $cliente->cuit ?? '-',
                    $cliente->nacimiento ? \Carbon\Carbon::parse($cliente->nacimiento)->format('d-m-Y') : '-',
                    $edad,
                    $cliente->localidad ? $cliente->localidad->provincia : '-',
                    $acciones
                ];
            }
            
            \Log::info("Registros finales después de filtro: " . count($data));
            
            return response()->json([
                'draw' => intval($request->get('draw')),
                'data' => $data,
                'recordsTotal' => count($data),
                'recordsFiltered' => count($data)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in ClienteController::getData: ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->get('draw')),
                'data' => [],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        // Si hay datos previos, calcular el CUIT sugerido
        $cuit = null;
        $dni = request('documento');
        $sexo = request('sexo');
        if ($dni && $sexo) {
            $cuit = CuitHelper::calcularCuit($dni, $sexo);
        }
        return view('admin.clientes.create', compact('cuit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipodoc' => 'required|string|max:5',
            'sexo' => 'required|string|max:1',
            'documento' => 'required|string|max:8',
            'cuit' => 'string|max:11',
            'apelnombres' => 'string|max:50',
            'nacimiento' => 'date|max:20',
            'nacionalidad' => 'string|max:30',
            'domicilio' => 'string|max:100',
            'cod_postal' => 'required|exists:localidades,id',
            'telefono' => 'string|max:50',
            'email' => 'string|max:80',
            'estado' => 'string|max:20',
            'cod_postal' => 'required',
        ]);

        $cliente = new Cliente();
        $cliente->tipodoc = $request->tipodoc;
        $cliente->documento = $request->documento;
        $cliente->sexo = $request->sexo;
        $cliente->cuit = $request->cuit;
        $cliente->apelnombres = strtoupper($request->apelnombres);
        $cliente->nacimiento = $request->nacimiento;
        $cliente->nacionalidad = $request->nacionalidad;
        $cliente->domicilio = $request->domicilio;
        $cliente->cod_postal_id = $request->cod_postal;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->estado = empty($request->estado) ? 'Activo' : $request->estado;
        $cliente->fechaestado = now();
        $cliente->observacion = $request->observacion;
        $cliente->save();

        return redirect()->route('admin.clientes.index')
            ->with('mensaje', 'Cliente creado con éxito.')
            ->with('icono', 'success');
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.show', compact('cliente'));
    }

    public function edit($id)
    {
        $cliente = Cliente::with(['localidad'])->findOrFail($id);
        return view('admin.clientes.edit', compact('cliente'));
    }

   public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'tipodoc' => 'required|string|max:5',
            'sexo' => 'required|string|max:1',
            'documento' => 'required|string|max:8',
            'cuit' => 'string|max:11',
            'apelnombres' => 'string|max:50',
            'nacimiento' => 'date|max:20',
            'nacionalidad' => 'string|max:30',
            'domicilio' => 'string|max:100',
            'cod_postal' => 'required|exists:localidades,id',
            'telefono' => 'string|max:50',
            'email' => 'string|max:80',
            'estado' => 'string|max:20',
            'cod_postal' => 'required',
        ]);

        $cliente->tipodoc = $request->tipodoc;
        $cliente->documento = $request->documento;
        $cliente->sexo = $request->sexo;
        $cliente->cuit = $request->cuit;
        $cliente->apelnombres = strtoupper($request->apelnombres);
        $cliente->nacimiento = $request->nacimiento ? date('Y-m-d', strtotime($request->nacimiento)) : null;
        $cliente->nacionalidad = $request->nacionalidad;
        $cliente->domicilio = $request->domicilio;
        $cliente->cod_postal_id = $request->cod_postal;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->estado = empty($request->estado) ? 'Activo' : $request->estado;
        $cliente->fechaestado = $request->fechaestado ? date('Y-m-d', strtotime($request->fechaestado)) : null;
        $cliente->observacion = $request->observacion;
        $cliente->save();

        return redirect()->route('admin.clientes.index')
            ->with('mensaje', 'Cliente actualizado con éxito.')
            ->with('icono', 'success');
    }

    public function confirmDelete($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.delete', compact('cliente'));
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('admin.clientes.index')
            ->with('mensaje', 'Cliente eliminado con éxito.')
            ->with('icono', 'success');
    }

    /**
    * Endpoint para calcular el CUIT vía AJAX
    */
    public function calcularCuit(Request $request)
    {
        $dni = $request->input('dni');
        $sexo = $request->input('sexo');
        $cuit = null;
        if ($dni && $sexo) {
            $cuit = CuitHelper::calcularCuit($dni, $sexo);
        }
        return response()->json(['cuit' => $cuit]);
    }
    
    /**
     * Buscar cliente por CUIT (AJAX)
     */
    public function buscarPorCuit($cuit)
    {
        $cliente = Cliente::where('cuit', $cuit)->first();
        if ($cliente) {
            return response()->json(['success' => true, 'cliente' => $cliente]);
        } else {
            return response()->json(['success' => false]);
        }
    }

}
