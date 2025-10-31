@if($content->type === 'text')
    @if($content->content != '' && replace_paramster($content->content) !== '...') <p class="mb-0">{{ replace_paramster($content->content) }}</p> @endif
    <x-input :name="$content->name" :caption="$content->options ?? ''" :value="$content->value ?? ''" class="form-control-sm" />
@endif
@if($content->type === 'date')
    @if($content->content != '' && replace_paramster($content->content) !== '...') <p class="mb-0">{{ replace_paramster($content->content) }}</p> @endif
    <x-input :name="$content->name" :caption="$content->options ??$content->name" :value="format_date($content->value ?? '')" class="form-control-sm datepicker" />
@endif
@if($content->type === 'image')
    <x-checkbox name="{{ $content->name }}" :caption="str_unslug($content->name, '_')" :value="1" :checked="$content->value == 1" class="mb-3" />
    <img src="{{ asset('storage/' . $content->options) }}" alt="" class="w-lg-50 w-100">
@endif
@if(in_array($content->type, ['longtext', 'bullet', 'fixed_text']))
    @if($content->content != '' && replace_paramster($content->content) !== '...') <p class="mb-0">{{ replace_paramster($content->content) }}</p> @endif
    <x-textarea :name="$content->name" :caption="$content->options" :value="$content->value ?? ''" rows="6" class="form-control-sm" />
@endif
@if($content->type === 'options')
    @php($options = explode(';', $content->options))
    @php($options = array_combine($options, $options))
    @if($content->content != '' && replace_paramster($content->content) !== '...') <p class="mb-0">{{ replace_paramster($content->content) }}</p> @endif
    <x-select :name="$content->name" :caption="$content->name" :value="$content->value ?? ''" :options="$options" caption="-Pilih-" class="form-select-sm" />
@endif
@if($content->type === 'checklist')
    @php($list = explode(';', $content->options))
    @foreach($list as $item)
        <x-checkbox name="{{ $content->name }}[]" :caption="$item" :value="$item" :checked="in_array($item, json_decode($content->value ?? '[]', true))" class="mb-3" />
    @endforeach
@endif
@if($content->type === 'radio')
    @php($list = explode(';', $content->options))
    @foreach($list as $item)
        <x-radio name="{{ $content->name }}" :caption="$item" :value="$item" :checked="$item == $content->value" class="mb-3" />
    @endforeach
@endif
@if($content->type == 'table')
    @php($value_data = json_decode($content->value ?? '[]', true))
    @php($content_data = json_decode($content->options, true))
    @if($value_data == []) @php($value_data = $content_data) @endif
    @php($keys = ($value_data == null) ? [] : array_values(array_keys($value_data)))
    <table class="table table-bordered table-sm border border-dark mb-1 fs-7">
        <thead>
        @foreach($keys as $key)
            <th class="{{ head($keys) == $key ? 'ps-4' : '' }} {{ last($keys) == $key ? 'pe-4' : '' }}">{{ $key }}</th>
        @endforeach
        </thead>
        <tbody>
        @php($key_values = [])
        @if($value_data !== null)
            @php($count = head($value_data) == false ? 0 : count(head($value_data)))
            @for($i = 0; $i < $count; $i++)
                <tr class="item_{{ $content->name }}">
                    @foreach($keys as $key)
                        <td class="p-0" style="{{ head($keys) == $key ? 'width:30px' : 'width:200px' }}">
                            <x-input name="{{ $content->name }}[{{ $key }}][{{ $i }}]" :value="$value_data[$key][$i] ?? ($key_values[$key][$i] ?? '')" class="border-0 fs-8 px-1" alert="0" />
                        </td>
                    @endforeach
                </tr>
            @endfor
        @endif
        </tbody>
    </table>
    <div class="d-flex flex-row gap-6 mb-3">
        <button class="btn btn-sm btn-primary py-1" type="button" onclick="tambah_item('{{ $content->name }}')">Tambah Item</button>
        <button class="btn btn-sm btn-secondary py-1" type="button" onclick="kurangi_item('{{ $content->name }}')">Kurangi Item</button>
    </div>
@endif
