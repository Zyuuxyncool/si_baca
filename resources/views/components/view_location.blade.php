<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 table-sm">
        <thead>
        <tr class="text-start bg-light text-gray-400 fw-bold fs-7 text-uppercase">
            <th class="ps-4 rounded-start">Lokasi</th>
            <th class="text-center w-50px pe-4 rounded-end">Pilih</th>
        </tr>
        </thead>
        <tbody class="fw-semibold text-gray-600">
        @foreach($lokasi as $item)
            <tr>
                <td class="ps-4">{{ $item['nama'] }}</td>
                <td class="text-end text-nowrap py-0 align-middle">
                    <button class="btn btn-sm btn-secondary py-1" type="button" onclick="select_location('{{ $item['id'] }}', '{{ $item['nama'] }}')">Pilih</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
