@if($template->type === 'text')
    <x-input :name="$template->name" :value="$template->value->value ?? ''" class="form-control-sm" />
@endif
@if($template->type === 'longtext')
    <x-textarea :name="$template->name" :value="$template->value->value ?? ''" class="form-control-sm" rows="5" />
@endif
@if($template->type === 'date')
    <x-input :name="$template->name" :value="format_date($template->value->value ?? '')" class="form-control-sm datepicker" />
@endif
@if($template->type === 'options')
    @php($options = explode(';', $template->options))
    @php($options = array_combine($options, $options))
    <x-select :name="$template->name" :value="$template->value->value ?? ''" :options="$options" caption="-Pilih-" class="form-select-sm" />
@endif
@if($template->type === 'checklist')
    @php($list = explode(';', $template->options))
    @if($template->value->value == 1)
        @php($template->value->value = '[]')
    @endif
    <div class="row justify-content-between">
        @foreach($list as $item)
            <div class="col-lg-4">
                <x-checkbox name="{{ $template->name }}[]" :caption="$item" :value="$item" :checked="in_array($item, json_decode($template->value->value ?? '[]', true) ?? [])" class="mb-3" />
            </div>
        @endforeach
    </div>

@endif
@if($template->type === 'radio')
    @php($list = explode(';', $template->options))
    @foreach($list as $key => $item)
        <x-radio prefix="id_{{ $key }}" name="{{ $template->name }}" :caption="$item" :value="$item" :checked="$item == ($template->value->value ?? '')" class="mb-3" />
    @endforeach
@endif
@if($template->type == 'table')
    @php($value_data = json_decode($template->value->value ?? '[]', true))
    @php($content_data = json_decode($template->options, true))
    @if($value_data == []) @php($value_data = $content_data) @endif
    @php($keys = ($value_data == null) ? [] : array_values(array_keys($value_data)))
    <table class="table table-bordered table-sm border border-dark mb-1 fs-7">
        <thead>
        @foreach($keys as $key)
            <th class="{{ head($keys) == $key ? 'ps-4' : '' }} {{ last($keys) == $key ? 'pe-4' : '' }}">{{ $key }}</th>
        @endforeach
        <th></th>
        </thead>
        <tbody>
        @php($key_values = [])
        @if($value_data !== null)
            @php($count = head($value_data) == false ? 0 : count(head($value_data)))
            @for($i = 0; $i < $count; $i++)
                @if(($value_data[$key][$i] ?? '') != '')
                    <tr class="item_{{ $template->name }}" id="item_{{ $template->name }}_{{ $i }}">
                        @foreach($keys as $key)
                            <td class="py-1" >
                                {{ $value_data[$key][$i] ?? ($key_values[$key][$i] ?? '') }}
                                <div class="d-none">
                                    <x-input name="{{ $template->name }}[{{ $key }}][{{ $i }}]" :value="$value_data[$key][$i] ?? ($key_values[$key][$i] ?? '')" class="border-0 form-control-sm fs-8 px-1" alert="0" />
                                </div>
                            </td>
                        @endforeach
                        <td class="py-1 align-middle" width="5%">
                            <button class="btn btn-sm btn-danger py-1" type="button" onclick="delete_template('item_{{ $template->name }}_{{ $i }}')"> delete </button>
                        </td>
                    </tr>
                @endif
            @endfor
        @endif
        </tbody>
    </table>
@endif
