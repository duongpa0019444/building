

@extends('layouts.admin')
@section('content')

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Đăng Ký Mượn Phòng Học</h2>
                <p class="text-center text-muted mb-4">Vui lòng điền đầy đủ thông tin để đăng ký mượn phòng.</p>
                <form action="/submit-booking" method="POST">
                    <!-- Thông tin người đăng ký -->
                    <div class="my-3">
                        <label for="applicant" class="form-label">Người đăng ký:</label>
                        <input type="text" class="form-control" id="applicant" name="applicant"  >
                    </div>
                    <div class="my-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email"  >
                    </div>

                    <!-- Thông tin phòng -->
                    <div class="my-3">
                        <label for="building" class="form-label">Tòa nhà:</label>
                        <select id="building" name="building" class="form-lable" required>
                            <option value="" disabled selected hidden>-Chọn tòa nhà-</option>
                            <!-- Dữ liệu tĩnh, trong thực tế sẽ được backend điền -->
                            @foreach ($buildings as $builder)
                                <option value="{{  $builder->id }}">{{ $builder->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="my-3">
                        <label for="room" class="form-label ">Phòng:</label>
                        <select id="room" name="room_id" class="form-lable" required>
                            <option value="" disabled selected hidden>-Chọn phòng-</option>
                        </select>
                    </div>

                    <div class="my-3">
                        <label for="room" class="form-label ">Ngày:</label>
                        <input type="date" id="dateInput">
                    </div>

                    <!-- Thời gian mượn -->
                    {{-- <div class="my-3">
                        <label for="startTime" class="form-label">Thời gian bắt đầu:</label>
                        <input type="time" class="form-control" id="startTime" name="start_time" required>
                    </div> --}}

                    {{-- <div class="my-3">
                        <label for="endTime" class="form-label">Thời gian kết thúc:</label>
                        <input type="time" class="form-control" id="endTime" name="end_time" required>
                    </div> --}}

                    <!-- Input chọn thời gian -->











                    <!-- Lý do mượn -->
                    <div class="my-3">
                        <label for="reason" class="form-label">Lý do mượn:</label>
                        <textarea class="form-control" id="reason" name="reason" maxlength="500" required placeholder="Nhập lý do mượn phòng..."></textarea>
                    </div>

                    <!-- Thiết bị đi kèm (tùy chọn) -->
                    <div class="my-3">
                        <label class="form-label">Thiết bị đi kèm (tùy chọn):</label>
                        <div>
                            <!-- Dữ liệu tĩnh, trong thực tế sẽ được backend điền -->
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="equipment[]" value="1" id="equip1">
                                <label class="form-check-label" for="equip1">Máy chiếu (Còn 2)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="equipment[]" value="2" id="equip2">
                                <label class="form-check-label" for="equip2">Loa (Còn 5)</label>
                            </div>
                        </div>
                    </div>

                    <!-- Nút hành động -->
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
                        <button type="reset" class="btn btn-secondary">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    @endsection

@push('scripts')
    <script>


    $('#building').change(function() {
        // Lấy giá trị được chọn
        var buildingId = $(this).val();
        // console.log(buildingId);
        // Kiểm tra nếu giá trị không rỗng
        if(buildingId) {
            $.ajax({
                url: '/admin/selectBuiding', // Thay bằng URL endpoint của bạn
                type: 'POST', // Hoặc GET tùy yêu cầu
                data: {
                    building_id: buildingId,
                    _token: '{{ csrf_token() }}' // Nếu dùng Laravel cần token CSRF
                },
                success: function(response) {
                    // Xử lý khi thành công
                    // console.log('Gửi thành công:', response.rooms);
                    // Có thể thêm code để cập nhật giao diện
                    $('#room').html('<option value="" disabled selected hidden>-Chọn phòng-</option>');
                    $.each(response.rooms, function(key, value) {
                        $('#room').append('<option value="' + value.id + '">' + value.room_name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    // Xử lý khi lỗi
                    console.log('Lỗi:', error);
                }
            });
        }
    });


    //xử lý khi chọn phòng
    $('#room').change(function() {
        // Lấy giá trị được chọn
        var roomsId = $(this).val();
        console.log(roomsId);
        // Kiểm tra nếu giá trị không rỗng
        if(roomsId) {
            $.ajax({
                url: '/admin/getDateRoom',
                type: 'POST', // Hoặc GET tùy yêu cầu
                data: {
                    rooms_id: roomsId,
                    _token: '{{ csrf_token() }}' // Nếu dùng Laravel cần token CSRF
                },
                success: function(response) {
                    // Xử lý khi thành công
                    console.log('Gửi thành công:', response.dates);
                        //chặn ngày đã hết lịch

                },
                error: function(xhr, status, error) {
                    // Xử lý khi lỗi
                    console.log('Lỗi:', error);
                }
            });
        }
    });




    document.addEventListener("DOMContentLoaded", function () {

        let disabledDatesRaw = ["2025-3-30", "2025-3-31", "2025-4-1", "2025-12-1"]; // Ngày không chuẩn
        let disabledDates = disabledDatesRaw.map(date => {
            let parts = date.split("-");
            let formattedDate = `${parts[0]}-${parts[1].padStart(2, '0')}-${parts[2].padStart(2, '0')}`;
            return formattedDate;
        });

        flatpickr("#dateInput", {
            dateFormat: "Y-m-d",
            disable: disabledDates // Sử dụng danh sách đã chuẩn hóa
        });
        });


</script>
@endpush


