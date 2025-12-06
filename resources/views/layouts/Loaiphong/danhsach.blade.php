@extends ('layouts.app')
@section ('content')

<div class="card">
    <div class="card-header"> Loai Phòng </div>
        <div class="card-body table-reponsive">
            <p><a href="{{ route('loai-phong.them') }}" class="btn btn-info"><i class="fa-light fa-flus"></i> Thêm mới </a></p>
            <table class="table table-bordered table-hover table-sm mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="45%">Tên loại phòng</th>
                        <th width="40%">Giá tiền</th>
                        <th width="40%">Số người</th>
                        <th width="10%">sửa</th>
                        <th width="10%">xóa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loaiPhongs as $lp)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $lp->ten_loai_phong }}</td>
                        <td>{{ number_format($lp->gia) }} VND</td>
                        <td>{{ $lp->so_nguoi }}</td>
                        <td class="text-center"><a href="{{ route('loai-phong.sua', ['id' => $lp->id]) }}"><i class="fa-light fa-edit"></i></a></td>
                        <td class="text-center"><a href="{{ route('loai-phong.xoa', ['id' => $lp->id]) }}" onclick="return confirm('Bạn có muốn xóa loại phòng {{ $lp->ten_loai_phong }} không?')"><i class="fa-light fa-trash-alt text-danger"></i></a></td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>
</div>
@endsection