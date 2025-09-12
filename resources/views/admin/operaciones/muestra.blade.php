                        {{-- DATOS INFORMACION DEL BCRA CHEQUES RECHAZADOS --}}
                        @php $chqrs = $data['morosidad']['chequesRechazados']['datos'] ?? []; @endphp
                        @if (!empty($chqrs) && is_array($chqrs))
                            <div class="col-12 mt-3">
                                <h3>BCRA Cheques Rechazados</h3>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Entidad</th>
                                        <th>Fecha Rechazo</th>
                                        <th>Importe</th>
                                        <th>Motivo</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($chqrs as $chq)
                                        <tr>
                                            <td>{{ $chq['entidad'] ?? '' }}</td>
                                            <td>{{ isset($chq['fechaRechazo']) ? \Carbon\Carbon::parse($chq['fechaRechazo'])->format('d-m-Y') : '' }}</td>
                                            <td>{{ isset($chq['importe']) ? number_format($chq['importe'], 2, ',', '.') : '' }}</td>
                                            <td>{{ $chq['motivo'] ?? '' }}</td>
                                            <td>{{ $chq['cantidad'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        {{-- DATOS INFORMACION DEL BCRA DEUDORES BANCO CENTRAL --}}
                        @php $deudores = $data['morosidad']['deudoresBancoCentral']['datos'] ?? []; @endphp
                        @if (!empty($deudores) && is_array($deudores))
                            <div class="col-12 mt-3">
                                <h3>BCRA Deudores Banco Central</h3>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Entidad</th>
                                        <th>Tipo Documento</th>
                                        <th>Número Documento</th>
                                        <th>Situación</th>
                                        <th>Mes</th>
                                        <th>Año</th>
                                        <th>Calificación</th>
                                        <th>Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deudores as $deudor)
                                        <tr>
                                            <td>{{ $deudor['entidad'] ?? '' }}</td>
                                            <td>{{ $deudor['tipoDocumento'] ?? '' }}</td>
                                            <td>{{ $deudor['numeroDocumento'] ?? '' }}</td>
                                            <td>{{ $deudor['situacion'] ?? '' }}</td>
                                            <td>{{ $deudor['mes'] ?? '' }}</td>
                                            <td>{{ $deudor['anio'] ?? '' }}</td>
                                            <td>{{ $deudor['calificacion'] ?? '' }}</td>
                                            <td>{{ isset($deudor['importe']) ? number_format($deudor['importe'], 2, ',', '.') : '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        {{-- DATOS INFORMACION DEL BCRA DEUDORES CENTRO COMERCIAL --}}
                        @php $nodo = $data['morosidad']['deudoresCentroComercial']['datos'] ?? []; @endphp
                        @if (!empty($nodo) && is_array($nodo))
                            <div class="col-12 mt-3">
                                <h3>BCRA Deudores Nodo Federal</h3>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Entidad</th>
                                        <th>Tipo Documento</th>
                                        <th>Número Documento</th>
                                        <th>Situación</th>
                                        <th>Mes</th>
                                        <th>Año</th>
                                        <th>Calificación</th>
                                        <th>Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($nodo as $item)
                                        <tr>
                                            <td>{{ $item['entidad'] ?? '' }}</td>
                                            <td>{{ $item['tipoDocumento'] ?? '' }}</td>
                                            <td>{{ $item['numeroDocumento'] ?? '' }}</td>
                                            <td>{{ $item['situacion'] ?? '' }}</td>
                                            <td>{{ $item['mes'] ?? '' }}</td>
                                            <td>{{ $item['anio'] ?? '' }}</td>
                                            <td>{{ $item['calificacion'] ?? '' }}</td>
                                            <td>{{ isset($item['importe']) ? number_format($item['importe'], 2, ',', '.') : '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
