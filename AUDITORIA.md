# Sistema de Auditoría - Guía de Implementación

## Descripción
Este sistema permite rastrear automáticamente qué usuario creó y modificó cada registro en la base de datos. Es especialmente útil para auditoría y seguimiento de cambios.

## Componentes del Sistema

### 1. Trait AuditableTrait
Ubicación: `app/Traits/AuditableTrait.php`
- Se encarga de asignar automáticamente los valores de `created_by` y `updated_by`
- Incluye relaciones para obtener los datos del usuario

### 2. Campos de Base de Datos
Para cada tabla que necesite auditoría, agregar:
- `created_by` (unsignedBigInteger, nullable)
- `updated_by` (unsignedBigInteger, nullable)
- Claves foráneas hacia la tabla `users`

### 3. Componente de Vista
Ubicación: `resources/views/components/audit-info.blade.php`
- Componente reutilizable para mostrar información de auditoría
- Se puede usar en cualquier vista que muestre detalles de un modelo

## Implementación paso a paso

### Para un nuevo modelo:

1. **Agregar el trait al modelo:**
```php
<?php
namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;

class MiModelo extends Model
{
    use AuditableTrait;
    
    protected $fillable = [
        // ... otros campos
        'created_by',
        'updated_by',
    ];
}
```

2. **Crear migración:**
```bash
php artisan make:migration add_audit_fields_to_mi_tabla_table --table=mi_tabla
```

3. **Configurar la migración:**
```php
public function up(): void
{
    Schema::table('mi_tabla', function (Blueprint $table) {
        $table->unsignedBigInteger('created_by')->nullable()->after('updated_at');
        $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
        
        $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('mi_tabla', function (Blueprint $table) {
        $table->dropForeign(['created_by']);
        $table->dropForeign(['updated_by']);
        $table->dropColumn(['created_by', 'updated_by']);
    });
}
```

4. **Ejecutar migración:**
```bash
php artisan migrate
```

5. **En el controlador (vista show):**
```php
public function show($id)
{
    $modelo = MiModelo::with(['createdBy', 'updatedBy'])->findOrFail($id);
    return view('mi-vista.show', compact('modelo'));
}
```

6. **En la vista:**
```blade
{{-- Mostrar información de auditoría --}}
<x-audit-info :model="$modelo" />
```

## Comportamiento Automático

- **Al crear:** Se asigna automáticamente el usuario autenticado a `created_by` y `updated_by`
- **Al actualizar:** Se asigna automáticamente el usuario autenticado solo a `updated_by`
- **Sin usuario:** Si no hay usuario autenticado, los campos permanecen como `null`

## Relaciones Disponibles

Una vez implementado el trait, el modelo tendrá estas relaciones:
- `createdBy()`: Usuario que creó el registro
- `updatedBy()`: Usuario que modificó por última vez el registro

## Ejemplos de Uso

```php
// Obtener quien creó el registro
$usuario = $clientes->createdBy;

// Obtener quien modificó el registro
$usuario = $clientes->updatedBy;

// En una consulta con eager loading
$clientes = Cliente::with(['createdBy', 'updatedBy'])->get();
```

## Modelos ya implementados

- [x] User (usuarios)
- [x] Cliente (clientes) - Ejemplo implementado

## Modelos pendientes (opcionales)

- [ ] Nodo
- [ ] Socio  
- [ ] Operacion
- [ ] Consulta
- [ ] Localidad

Para implementar en más modelos, seguir los pasos descritos arriba.