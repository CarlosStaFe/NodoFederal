<div class="container mt-4">
	<div class="row">
		<div class="col-12 mb-3">
			<div class="d-flex align-items-center">
				<img src="{{ public_path('assets/img/NF-LOGO.jpg') }}" alt="Nodo Federal Logo" style="height: 80px; margin-right: 15px; display: inline-block; vertical-align: middle;">
				<h1 class="mb-0" style="display: inline-block; vertical-align: middle;">Informe Comercial</h1>
			</div>
		</div>
		<div class="col-12">
            @php $data = $datos['data'] ?? null; @endphp

			@php $p = $data['datosParticulares'] ?? []; @endphp
            <style>
                body, table, th, td, h2, h3 {
                    font-size: 11px !important;
                }
                .table {
                    width: 100% !important;
                    table-layout: fixed;
                }
                .table thead th {
                    background-color: #37a395;
                    color: #fff;
                    font-weight: bold;
                }
                #datos-personales th {
                    background-color: #3750a3;
                    color: #fff;
                    font-weight: bold;
                }
                .section-title {
                    color: #0d6efd;
                    font-size: 1.1rem;
                    font-weight: bold;
                    margin-bottom: 0.5em;
                }
				.watermark {
					position: fixed;
					top: 50%;
					left: 50%;
					transform: translate(-50%, -50%) rotate(-40deg);
					width: 100vw;
					opacity: 0.20;
					z-index: 0;
					color: #3750a3;
					font-size: 5em;
					text-align: center;
					white-space: nowrap;
					pointer-events: none;
				}
            </style>

			<div class="watermark">Nodo Federal - Tel. 0342 156267364</div>

			@if($p)
				<table class="table table-bordered">
					<div class="col-12 mt-3">
						<h3>Datos Personales</h3>
					</div>
					<thead id="datos-personales">
						<tr>
							<th style="width: 30%; border: 1px solid #dee2e6;">Apellido y Nombres</th>
							<th style="width: 15%; border: 1px solid #dee2e6;">CUIL</th>
							<th style="width: 10%; border: 1px solid #dee2e6;">DNI</th>
							<th style="width: 10%; border: 1px solid #dee2e6;">Tipo</th>
							<th style="width: 15%; border: 1px solid #dee2e6;">Fecha Nac.</th>
							<th style="width: 10%; border: 1px solid #dee2e6;">Edad</th>
							<th style="width: 10%; border: 1px solid #dee2e6;">Sexo</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="border: 1px solid #dee2e6;">{{ $p['apellidoNombre'] ?? '' }}</td>
							<td style="border: 1px solid #dee2e6;">{{ $p['cuil'] ?? '' }}</td>
							<td style="border: 1px solid #dee2e6;">{{ $p['dni'] ?? '' }}</td>
							<td style="border: 1px solid #dee2e6;">{{ $p['tipo'] ?? '' }}</td>
							<td style="border: 1px solid #dee2e6;">{{ isset($p['fechaNacimiento']) ? \Carbon\Carbon::parse($p['fechaNacimiento'])->format('d-m-Y') : '' }}</td>
							<td style="border: 1px solid #dee2e6;">{{ $p['edad'] ?? '' }}</td>
							<td style="border: 1px solid #dee2e6;">{{ $p['sexo'] ?? '' }}</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-bordered">
					<thead id="datos-personales">
						<tr>
							<th style="width: 15%; border: 1px solid #dee2e6;">Nacionalidad</th>
							<th style="width: 25%; border: 1px solid #dee2e6;">Localidad</th>
							<th style="width: 25%; border: 1px solid #dee2e6;">Provincia</th>
							<th style="width: 10%; border: 1px solid #dee2e6;">Cód.Postal</th>
							<th style="width: 54%; border: 1px solid #dee2e6;">Domicilio</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="border: 1px solid #dee2e6;">{{ $p['nacionalidad'] ?? '' }}</td>
							<td style="border: 1px solid #dee2e6;">{{ $p['localidad'] ?? '' }}</td>
							<td style="border: 1px solid #dee2e6;">{{ $p['provincia'] ?? '' }}</td>
							<td style="border: 1px solid #dee2e6;">{{ $p['cp'] ?? '' }}</td>
							<td style="border: 1px solid #dee2e6;" colspan="5">{{ $p['domicilio'] ?? '' }}</td>
						</tr>
					</tbody>
				</table>
			@endif

			@php $ver = $data['veraz'] ?? []; @endphp
			@if($ver)
				<h3>Datos Veraz</h3>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th style="width: 10%;">Categoría</th>
							<th style="width: 15%;">Documento</th>
							<th style="width: 10%;">Predictor</th>
							<th style="width: 25%;">Ingresos</th>
							<th style="width: 15%;">Score Rango</th>
							<th style="width: 15%;">Score Segmento</th>
							<th style="width: 20%;">Score Texto</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: center;">{{ $ver['categoria'] ?? '' }}</td>
							<td style="text-align: center;">{{ $ver['documento'] ?? '' }}</td>
							<td style="text-align: center;">{{ $ver['incomePredictor'] ?? '' }}</td>
							<td style="text-align: center;">{{ $ver['incomePredictorRango'] ?? '' }}</td>
							<td style="text-align: center;">{{ $ver['scoreRango'] ?? '' }}</td>
							<td style="text-align: center;">{{ $ver['scoreSegmento'] ?? '' }}</td>
							<td style="text-align: center;">{{ $ver['scoreTexto'] ?? '' }}</td>
						</tr>
					</tbody>
				</table>
			@endif

			@php $monos = $data['datosLaborales']['monotributista']['datos'] ?? []; @endphp
			@if (count($monos) > 0)
				<h3>Monotributo</h3>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Tipo</th>
							<th>Categoría</th>
							<th>Estado</th>
							<th>Inicio</th>
							<th>Hasta</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($monos as $mono)
							<tr>
								<td>{{ $mono['tipo'] ?? '' }}</td>
								<td>{{ $mono['categoria'] ?? '' }}</td>
								<td>{{ $mono['estado'] ?? '' }}</td>
								<td>{{ $mono['fechaInicio'] ?? '' }}</td>
								<td>{{ $mono['fechaHasta'] ?? '' }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif

			@php $acts = $data['datosLaborales']['actividad']['datos'] ?? []; @endphp
			@if (count($acts) > 0)
				<h3>Actividad</h3>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Descripción</th>
							<th>CIIU</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($acts as $act)
							<tr>
								<td>{{ $act['descripcion'] ?? '' }}</td>
								<td>{{ $act['ciiu'] ?? '' }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif

			@php $jubs = $data['datosLaborales']['jubilacion']['datos'] ?? []; @endphp
			@if (count($jubs) > 0)
				<h3>Jubilación</h3>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Titular</th>
							<th>Sueldo Bruto</th>
							<th>Sueldo Neto</th>
							<th>Periodo</th>
							<th>Rango</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($jubs as $jub)
							<tr>
								<td>{{ $jub['titular'] ?? '' }}</td>
								<td>{{ isset($jub['sueldoBruto']) ? number_format($jub['sueldoBruto'], 2, ',', '.') : '' }}</td>
								<td>{{ isset($jub['sueldoNeto']) ? number_format($jub['sueldoNeto'], 2, ',', '.') : '' }}</td>
								<td>{{ isset($jub['periodo']) ? \Carbon\Carbon::parse($jub['periodo'])->format('d-m-Y') : '' }}</td>
								<td>{{ $jub['rango'] ?? '' }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif

			@php $autos = $data['bienesPersonales']['automotores_historial']['datos'] ?? []; @endphp
			@if (count($autos) > 0)
				<h3>Automotores Historial</h3>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Dominio</th>
							<th>Marca</th>
							<th>Modelo</th>
							<th>Año</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($autos as $auto)
							<tr>
								<td>{{ $auto['dominio'] ?? '' }}</td>
								<td>{{ $auto['marca'] ?? '' }}</td>
								<td>{{ $auto['modelo'] ?? '' }}</td>
								<td>{{ $auto['anioModelo'] ?? '' }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif

			@php $bcra = $data['situacionFinanciera']['informacionBcra']['datos'] ?? []; @endphp
			@if(count($bcra) > 0)
				<h3>Situación Financiera (BCRA)</h3>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Entidad</th>
							<th>Tipo</th>
							<th>Situación</th>
							<th>Periodo</th>
							<th>Préstamo</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($bcra as $b)
							<tr>
								<td>{{ $b['entidad']['entidad'] ?? '' }}</td>
								<td>{{ $b['entidad']['tipo'] ?? '' }}</td>
								<td>{{ $b['situacion'] ?? '' }}</td>
								<td>{{ $b['periodo'] ?? '' }}</td>
								<td>{{ $b['prestamo'] ?? '' }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<div class="alert alert-info">No hay datos de situación financiera BCRA.</div>
			@endif
		</div>
	</div>
</div>
