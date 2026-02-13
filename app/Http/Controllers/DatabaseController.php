<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use ZipArchive;
use App\Models\User;

class DatabaseController extends Controller
{
    private $backupPath;
    
    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        
        // Crear el directorio de backups si no existe
        if (!file_exists($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
        
        // Verificar que el usuario tenga rol admin o secretaria
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasRole(['admin', 'secretaria'])) {
                abort(403, 'No tienes permisos para acceder a esta sección');
            }
            return $next($request);
        });
    }

    /**
     * Mostrar la página de administración de base de datos
     */
    public function index()
    {
        return view('admin.administracion.basedatos');
    }

    /**
     * Mostrar usuarios conectados al sistema
     */
    public function conectados()
    {
        $usuariosConectados = $this->getUsuariosConectados();
        $estadisticas = $this->getEstadisticasConectados();
        
        return view('admin.administracion.conectados', compact('usuariosConectados', 'estadisticas'));
    }

    /**
     * AJAX para actualizar usuarios conectados
     */
    public function conectadosAjax()
    {
        $usuariosConectados = $this->getUsuariosConectados();
        
        return response()->json([
            'total' => $usuariosConectados->count(),
            'usuarios' => $usuariosConectados
        ]);
    }

    /**
     * Desconectar usuario específico eliminando su sesión
     */
    public function desconectarUsuario(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $sessionId = $request->input('session_id');
            
            if (!$userId) {
                return response()->json(['error' => 'ID de usuario requerido'], 400);
            }

            // Eliminar sesiones del usuario
            if ($sessionId) {
                // Eliminar sesión específica
                $deleted = DB::table('sessions')
                    ->where('id', $sessionId)
                    ->where('user_id', $userId)
                    ->delete();
            } else {
                // Eliminar todas las sesiones del usuario
                $deleted = DB::table('sessions')
                    ->where('user_id', $userId)
                    ->delete();
            }

            if ($deleted > 0) {
                // Obtener información del usuario para el log
                $user = User::find($userId);
                $userName = $user ? $user->name : 'Usuario desconocido';
                
                // Log de la acción
                \Log::info('Usuario desconectado por administrador', [
                    'admin_user' => auth()->user()->name,
                    'disconnected_user' => $userName,
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Usuario {$userName} desconectado correctamente",
                    'deleted' => $deleted
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron sesiones activas para este usuario'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error al desconectar usuario', [
                'error' => $e->getMessage(),
                'user_id' => $request->input('user_id'),
                'admin_user' => auth()->user()->name
            ]);
            
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desconectar todos los usuarios (excepto el actual)
     */
    public function desconectarTodos(Request $request)
    {
        try {
            $currentUserId = auth()->id();
            
            // Eliminar todas las sesiones excepto la del usuario actual
            $deleted = DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('user_id', '!=', $currentUserId)
                ->delete();

            // Log de la acción
            \Log::warning('Todos los usuarios desconectados por administrador', [
                'admin_user' => auth()->user()->name,
                'sessions_deleted' => $deleted,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Se desconectaron {$deleted} sesiones de usuario",
                'deleted' => $deleted
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al desconectar todos los usuarios', [
                'error' => $e->getMessage(),
                'admin_user' => auth()->user()->name
            ]);
            
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener usuarios conectados
     */
    private function getUsuariosConectados()
    {
        return DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->leftJoin('nodos', 'users.nodo_id', '=', 'nodos.id')
            ->leftJoin('socios', 'users.socio_id', '=', 'socios.id')
            ->leftJoin('model_has_roles', function($join) {
                $join->on('users.id', '=', 'model_has_roles.model_id')
                     ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
            })
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'sessions.id as session_id',
                'nodos.nombre as nodo_nombre',
                'socios.razon_social as socio_nombre',
                'roles.name as rol',
                'sessions.last_activity',
                'sessions.ip_address',
                'sessions.user_agent'
            )
            ->whereNotNull('sessions.user_id')
            ->where('sessions.last_activity', '>', now()->subMinutes(config('session.lifetime', 120))->timestamp)
            ->orderBy('sessions.last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->last_activity_human = date('d/m/Y H:i:s', $session->last_activity);
                $session->tiempo_inactivo = now()->diffInMinutes(date('Y-m-d H:i:s', $session->last_activity));
                return $session;
            });
    }

    /**
     * Obtener estadísticas de usuarios conectados
     */
    private function getEstadisticasConectados()
    {
        return [
            'total_usuarios' => DB::table('users')->count(),
            'usuarios_conectados' => $this->getUsuariosConectados()->count(),
            'sesiones_activas' => DB::table('sessions')->count(),
            'sesiones_autenticadas' => DB::table('sessions')->whereNotNull('user_id')->count(),
            'tiempo_sesion' => config('session.lifetime', 120)
        ];
    }

    /**
     * Generar backup de la base de datos
     */
    public function backup(Request $request)
    {
        try {
            $request->validate([
                'backup_name' => 'nullable|string|max:255',
                'backup_tables' => 'required|in:all,structure_only,data_only'
            ]);

            $backupName = $request->backup_name ?: 'backup_' . date('Y-m-d_H-i-s');
            $backupName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $backupName);
            $filename = $backupName . '.sql';
            $filepath = $this->backupPath . '/' . $filename;

            // Intentar usar mysqldump primero, si falla usar método PHP
            if ($this->mysqlDumpAvailable()) {
                $this->backupWithMysqlDump($filepath, $request->backup_tables);
            } else {
                $this->backupWithPHP($filepath, $request->backup_tables);
            }

            if (file_exists($filepath) && filesize($filepath) > 0) {
                return back()->with('success', 'Backup generado exitosamente: ' . $filename);
            } else {
                throw new \Exception('El archivo de backup está vacío o no se creó correctamente');
            }
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al generar backup: ' . $e->getMessage()]);
        }
    }

    /**
     * Verificar si mysqldump está disponible
     */
    private function mysqlDumpAvailable()
    {
        try {
            $process = new Process(['mysqldump', '--version']);
            $process->run();
            return $process->isSuccessful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Backup usando mysqldump
     */
    private function backupWithMysqlDump($filepath, $backupType)
    {
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port');

        $command = [
            'mysqldump',
            '--user=' . $dbUser,
            '--password=' . $dbPass,
            '--host=' . $dbHost,
            '--port=' . $dbPort,
            '--single-transaction',
            '--routines',
            '--triggers'
        ];

        // Agregar opciones según el tipo de backup
        switch ($backupType) {
            case 'structure_only':
                $command[] = '--no-data';
                break;
            case 'data_only':
                $command[] = '--no-create-info';
                break;
        }

        $command[] = $dbName;

        $process = new Process($command);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        file_put_contents($filepath, $process->getOutput());
    }

    /**
     * Backup usando PHP nativo
     */
    private function backupWithPHP($filepath, $backupType)
    {
        $dbName = config('database.connections.mysql.database');
        
        $sql = "-- Backup generado el " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Base de datos: {$dbName}\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        // Obtener todas las tablas
        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $dbName;

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            
            if ($backupType !== 'data_only') {
                // Obtener estructura de la tabla
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sql .= "-- Estructura de tabla para `{$tableName}`\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
            }

            if ($backupType !== 'structure_only') {
                // Obtener datos de la tabla
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $sql .= "-- Volcando datos para la tabla `{$tableName}`\n";
                    $sql .= "INSERT INTO `{$tableName}` VALUES\n";
                    
                    $values = [];
                    foreach ($rows as $row) {
                        $rowArray = (array) $row;
                        $escapedValues = array_map(function($value) {
                            return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                        }, $rowArray);
                        $values[] = '(' . implode(',', $escapedValues) . ')';
                    }
                    
                    $sql .= implode(",\n", $values) . ";\n\n";
                }
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        file_put_contents($filepath, $sql);
    }

    /**
     * Restaurar base de datos desde un archivo backup
     */
    public function restore(Request $request)
    {
        try {
            $request->validate([
                'backup_file' => 'required|file|max:51200', // 50MB máximo
                'restore_mode' => 'required|in:replace,merge',
                'confirm_restore' => 'required'
            ]);

            $file = $request->file('backup_file');
            $extension = $file->getClientOriginalExtension();
            
            if (!in_array($extension, ['sql', 'zip'])) {
                return back()->withErrors(['error' => 'Solo se permiten archivos .sql o .zip']);
            }

            $tempPath = storage_path('app/temp');
            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0755, true);
            }

            $uploadedFile = $tempPath . '/' . uniqid() . '.' . $extension;
            $file->move($tempPath, basename($uploadedFile));

            $sqlFile = $uploadedFile;

            // Si es un archivo ZIP, extraerlo
            if ($extension === 'zip') {
                $zip = new ZipArchive;
                if ($zip->open($uploadedFile) === TRUE) {
                    $zip->extractTo($tempPath);
                    $zip->close();
                    
                    // Buscar el archivo SQL en el ZIP
                    $sqlFiles = glob($tempPath . '/*.sql');
                    if (empty($sqlFiles)) {
                        throw new \Exception('No se encontró archivo SQL en el ZIP');
                    }
                    $sqlFile = $sqlFiles[0];
                } else {
                    throw new \Exception('No se pudo abrir el archivo ZIP');
                }
            }

            // Si el modo es 'replace', hacer backup automático antes de restaurar
            if ($request->restore_mode === 'replace') {
                $this->createAutoBackup();
            }

            // Intentar restauración con mysql, si falla usar PHP
            if ($this->mysqlAvailable()) {
                $this->restoreWithMysql($sqlFile);
            } else {
                $this->restoreWithPHP($sqlFile);
            }

            // Limpiar archivos temporales
            unlink($uploadedFile);
            if ($sqlFile !== $uploadedFile && file_exists($sqlFile)) {
                unlink($sqlFile);
            }

            return back()->with('success', 'Base de datos restaurada exitosamente');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al restaurar: ' . $e->getMessage()]);
        }
    }

    /**
     * Verificar si mysql está disponible
     */
    private function mysqlAvailable()
    {
        try {
            $process = new Process(['mysql', '--version']);
            $process->run();
            return $process->isSuccessful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Restaurar usando mysql
     */
    private function restoreWithMysql($sqlFile)
    {
        $sqlContent = file_get_contents($sqlFile);

        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port');

        $command = [
            'mysql',
            '--user=' . $dbUser,
            '--password=' . $dbPass,
            '--host=' . $dbHost,
            '--port=' . $dbPort,
            $dbName
        ];

        $process = new Process($command);
        $process->setInput($sqlContent);
        $process->setTimeout(600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    /**
     * Restaurar usando PHP nativo
     */
    private function restoreWithPHP($sqlFile)
    {
        $sql = file_get_contents($sqlFile);
        
        // Dividir el SQL en declaraciones individuales
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            function ($statement) {
                return !empty($statement) && !preg_match('/^\s*--/', $statement);
            }
        );

        DB::beginTransaction();
        
        try {
            // Desactivar verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            foreach ($statements as $statement) {
                if (!empty(trim($statement))) {
                    DB::statement($statement);
                }
            }
            
            // Reactivar verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Listar backups disponibles (AJAX)
     */
    public function listBackups()
    {
        try {
            $files = [];
            $backupFiles = glob($this->backupPath . '/*.sql');
            
            foreach ($backupFiles as $file) {
                $filename = basename($file);
                $files[] = [
                    'name' => $filename,
                    'size' => $this->formatBytes(filesize($file)),
                    'created_at' => date('d/m/Y H:i:s', filemtime($file)),
                    'download_url' => route('admin.administracion.basedatos.download', $filename),
                    'delete_url' => route('admin.administracion.basedatos.delete', $filename)
                ];
            }

            // Ordenar por fecha de creación descendente
            usort($files, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            return response()->json(['data' => $files]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Descargar un backup
     */
    public function downloadBackup($filename)
    {
        $filepath = $this->backupPath . '/' . $filename;
        
        if (!file_exists($filepath)) {
            abort(404, 'Archivo no encontrado');
        }

        return Response::download($filepath);
    }

    /**
     * Eliminar un backup
     */
    public function deleteBackup($filename)
    {
        try {
            $filepath = $this->backupPath . '/' . $filename;
            
            if (!file_exists($filepath)) {
                return response()->json(['error' => 'Archivo no encontrado'], 404);
            }

            unlink($filepath);
            return response()->json(['success' => 'Backup eliminado correctamente']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Crear backup automático antes de restaurar
     */
    private function createAutoBackup()
    {
        $backupName = 'auto_backup_before_restore_' . date('Y-m-d_H-i-s');
        $filename = $backupName . '.sql';
        $filepath = $this->backupPath . '/' . $filename;

        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port');

        $command = [
            'mysqldump',
            '--user=' . $dbUser,
            '--password=' . $dbPass,
            '--host=' . $dbHost,
            '--port=' . $dbPort,
            '--single-transaction',
            '--routines',
            '--triggers',
            $dbName
        ];

        $process = new Process($command);
        $process->setTimeout(300);
        $process->run();

        if ($process->isSuccessful()) {
            file_put_contents($filepath, $process->getOutput());
        }
    }

    /**
     * Formatear bytes en formato legible
     */
    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}