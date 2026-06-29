
    <tr style="text-align: center; font-weight: 600; padding: 0px">
        <td style="width: 1%; border: 1px solid #000;">{{ $loop->iteration }}</td>
        <td style="width: 15%; border: 1px solid #000; background-color: #FFF; text-align: left; font-weight: 500;">
            <div style="padding: 5px;">
                <div style="display: flex; justify-content: space-between; font-size: 9px;">
                    <div><strong>PROTOCOLO:</strong> {{ $record['protocol'] }}</div>
                    <div style="font-weight: bolder; background-color: #CCC;">GUIA: {{ $record['guide_number'] }}</div>
                </div>
                <div style="font-size: 9px;">{{ $record['patient_name'] }}</div>
                <div style="font-size: 9px;">{{ $record['patient_gender'] }} {{ $record['patient_age'] }}</div>
            </div>
        </td>
        <td style="width: 80%; padding: 0px; border: 1px solid #000;">
            <div style="display: flex; flex-wrap: wrap;">
                @foreach ($record['exams'] as $exam)
                    <div style="display: flex; flex-direction: column; border-right: 1px solid #000;">
                        <div style="font-size: 8px; margin-top: 1px; margin-bottom: 3px; background-color: #F2F2F2;">{{ $exam->name }}</div>
                        <div style="display: flex; background-color: #FFF;">
                            @foreach ($exam->parameters as $parameter)
                                <div style="height: 15px; padding: 0px 25px; text-align: center; border-bottom: 1px dashed #000;
                                {{ ! $loop->last ? 'border-right: 1px dashed #000;' : '' }}"
                                >
                                    {{ str_replace('##', '', $parameter->parameter) }}
                                </div>
                            @endforeach
                        </div>
                        <div style="display: flex; background-color: #FFF;">
                            @foreach ($exam->parameters as $parameter)
                                <div style="visibility: hidden; height: 20px; padding: 0px 25px; text-align: center;">
                                    {{ str_replace('##', '', $parameter->parameter) }}
                                </div>
                                @if (! $loop->last)
                                    <div style="border-right: 1px dashed #000; height: 20px;"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </td>
    </tr>
    
