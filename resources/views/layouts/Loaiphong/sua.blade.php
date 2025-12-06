@extends('layouts.app')
@section()
<div class="card">
    <div class="card-header"> Sửa loại phòng</div>
    <div class="card-body">
        <form action="{{ route('loai-phong.sua', ['id' => $loaiPhong->id]) }}"method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="ten_loai_phong">Tên loại phòng</label>
                <input type="text" class="form-control" id="ten_loai_phong" name="ten_loai_phong" value="{{ $loaiPhong->ten_loai_phong }}" required />
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-light fa-save"></i> Thêm </button>
    </div>
</div>
@endsection