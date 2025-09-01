<h1>Informe de Antecedentes (PDF)</h1>

@if(isset($datos))
	@php $data = $datos['data'] ?? null; @endphp
	@php $p = $datos['data'][0] ?? null; @endphp
	@if($p)
	<div style="font-size: 12px;">
		<strong>Datos Personales</strong><br>
		Apellido: {{ $p['apellido'] ?? '' }}<br>
		Nombre: {{ $p['nombre'] ?? '' }}<br>
		CUIL: {{ $p['cuil'] ?? '' }}<br>
		DNI: {{ $p['dni'] ?? '' }}<br>
		Fecha Nacimiento: {{ $p['fechaNacimiento'] ?? '' }}<br>
		Edad: {{ $p['edad'] ?? '' }}<br>
		Sexo: {{ $p['sexo'] ?? '' }}<br>
		Estado Civil: {{ $p['estadoCivil'] ?? '' }}<br>
		Nacionalidad: {{ $p['nacionalidad'] ?? '' }}<br>
		Localidad: {{ $p['localidad'] ?? '' }}<br>
		Provincia: {{ $p['provincia'] ?? '' }}<br>
		Domicilio: {{ $p['domicilio'] ?? '' }}<br>

		<br><strong>Teléfonos Fijos</strong><br>
		@php $tf = $data['contactacion']['telefonosFijos']['datos'] ?? []; @endphp
		@foreach($tf as $tel)
			{{ $tel['tipo'] ?? '' }}: {{ $tel['tel'] ?? '' }} ({{ $tel['operador'] ?? '' }})<br>
		@endforeach

		<br><strong>Teléfonos Celulares</strong><br>
		@php $tc = $data['contactacion']['telefonosCelulares']['datos'] ?? []; @endphp
		@foreach($tc as $cel)
			{{ $cel['tipo'] ?? '' }} {{ $cel['wsp'] ? '(WhatsApp)' : '' }}: {{ $cel['tel'] ?? '' }} ({{ $cel['operador'] ?? '' }})<br>
		@endforeach

		<br><strong>Mails</strong><br>
		@php $mails = $data['contactacion']['mails']['datos'] ?? []; @endphp
		@foreach($mails as $mail)
			Email: {{ $mail['email'] ?? '' }}<br>
		@endforeach

		<br><strong>Monotributo</strong><br>
		@php $monos = $data['datosLaborales']['monotributista']['datos'] ?? []; @endphp
		@foreach($monos as $mono)
			Tipo: {{ $mono['tipo'] ?? '' }}, Categoría: {{ $mono['categoria'] ?? '' }}, Estado: {{ $mono['estado'] ?? '' }}, Inicio: {{ $mono['fechaInicio'] ?? '' }}, Hasta: {{ $mono['fechaHasta'] ?? '' }}<br>
		@endforeach

		<br><strong>Actividad</strong><br>
		@php $acts = $data['datosLaborales']['actividad']['datos'] ?? []; @endphp
		@foreach($acts as $act)
			Descripción: {{ $act['descripcion'] ?? '' }}, CIIU: {{ $act['ciiu'] ?? '' }}<br>
		@endforeach

		<br><strong>Jubilación</strong><br>
		@php $jubs = $data['datosLaborales']['jubilacion']['datos'] ?? []; @endphp
		@foreach($jubs as $jub)
			Titular: {{ $jub['titular'] ?? '' }}, Sueldo Bruto: {{ $jub['sueldoBruto'] ?? '' }}, Sueldo Neto: {{ $jub['sueldoNeto'] ?? '' }}, Periodo: {{ $jub['periodo'] ?? '' }}, Rango: {{ $jub['rango'] ?? '' }}<br>
		@endforeach

		<br><strong>Automotores Historial</strong><br>
		@php $autos = $data['bienesPersonales']['automotores_historial']['datos'] ?? []; @endphp
		@foreach($autos as $auto)
			Dominio: {{ $auto['dominio'] ?? '' }}, Marca: {{ $auto['marca'] ?? '' }}, Modelo: {{ $auto['modelo'] ?? '' }}, Año: {{ $auto['anioModelo'] ?? '' }}<br>
		@endforeach

		<br><strong>Situación Financiera (BCRA)</strong><br>
		@php $bcra = $data['situacionFinanciera']['informacionBcra']['datos'] ?? []; @endphp
		@foreach($bcra as $b)
			Entidad: {{ $b['entidad']['entidad'] ?? '' }}, Tipo: {{ $b['entidad']['tipo'] ?? '' }}, Situación: {{ $b['situacion'] ?? '' }}, Periodo: {{ $b['periodo'] ?? '' }}, Préstamo: {{ $b['prestamo'] ?? '' }}<br>
		@endforeach
	</div>
	@else
		<div>No hay datos disponibles. Realice una consulta primero.</div>
	@endif
@else
	<div>No hay datos disponibles. Realice una consulta primero.</div>
@endif
