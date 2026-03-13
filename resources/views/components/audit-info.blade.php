{{-- Componente para mostrar información de auditoría --}}
@props(['model'])

<div class="card mt-3">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0">
            <i class="fas fa-history"></i> Información de Auditoría
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <strong>Creado:</strong>
                <br>
                <small class="text-muted">
                    {{ $model->created_at ? $model->created_at->format('d/m/Y H:i:s') : 'No disponible' }}
                    @if($model->createdBy)
                        <br>por: {{ $model->createdBy->name }} ({{ $model->createdBy->email }})
                    @endif
                </small>
            </div>
            <div class="col-md-6">
                <strong>Última modificación:</strong>
                <br>
                <small class="text-muted">
                    {{ $model->updated_at ? $model->updated_at->format('d/m/Y H:i:s') : 'No disponible' }}
                    @if($model->updatedBy)
                        <br>por: {{ $model->updatedBy->name }} ({{ $model->updatedBy->email }})
                    @endif
                </small>
            </div>
        </div>
    </div>
</div>