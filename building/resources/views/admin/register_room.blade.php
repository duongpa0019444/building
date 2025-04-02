

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
                        <select id="building" name="building" class="form-lable">
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
                            @foreach ($rooms as $room)
                                <option value="{{  $room->id }}">{{ $room->room_name }}</option>
                            @endforeach


                        </select>
                    </div>

                    <div class="my-3">
                        <label for="dateInput" class="form-label ">Ngày:</label>
                        <input type="date" id="dateInput">
                    </div>


                    <!-- Input chọn thời gian -->

                    <div class="row">
                        <!-- Giờ Bắt Đầu -->
                        <div class="col-md-3">
                            <p class="fw-bold pb-1">Giờ Bắt Đầu</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="timeInputStartHour" class="form-label">Giờ:</label>
                                    <select class="form-lable" id="timeInputStartHour">
                                        <option value="">-Chọn Giờ-</option>
                                        @for ($i = 7; $i < 21; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="timeInputStartMinute" class="form-label">Phút:</label>
                                    <select class="form-lable" id="timeInputStartMinute">
                                        @for ($i = 0; $i < 60; $i+=5)  <!-- Bước 5 phút để dễ chọn -->
                                            <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-1">

                        </div>
                        <!-- Giờ Kết Thúc -->
                        <div class="col-md-3">
                            <p class="fw-bold pb-1">Giờ Kết Thúc</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="timeInputEndHour" class="form-label">Giờ:</label>
                                    <select class="form-lable" id="timeInputEndHour">
                                        @for ($i = 7; $i < 21; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="timeInputEndMinute" class="form-label">Phút:</label>
                                    <select class="form-lable" id="timeInputEndMinute">
                                        @for ($i = 0; $i < 60; $i+=5)
                                            <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary">Tình trạng sử dụng phòng</button>
                        </div>
                    </div>

                    {{-- <div class="">
                        <div class="my-3">
                            <label for="timeInputStart" class="form-label">Giờ bắt đầu:</label>
                            <input type="time" id="timeInputStart" class="form-control">
                        </div>
                        <div class="my-3">
                            <label for="timeInputEnd" class="form-label">Giờ kết thúc:</label>
                            <input type="time" id="timeInputEnd" class="form-control">
                        </div>
                    </div> --}}

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
$(document).ready(function () {
    const buildingSelect = $("#building");
    const roomSelect = $("#room");
    const dateInput = $("#dateInput");
    const timeInputStart = $("#timeInputStart");

    roomSelect.change(function () {
        if (!buildingSelect.val()) {
            alert("Vui lòng chọn tòa trước khi chọn phòng!");
            roomSelect.val(""); // Reset phòng nếu chưa chọn ngày
        }
    });

    dateInput.change(function () {
        if (!roomSelect.val()) {
            alert("Vui lòng chọn phòng trước khi chọn ngày!");
            $(this).val(""); // Reset ngày nếu chưa chọn tòa
        }
    });

    timeInputStart.focus(function () {
        if (!$dateInput.val()) {
            alert("Vui lòng chọn ngày trước khi chọn giờ!");
            $(this).blur(); // Mất focus input giờ
        }
    });


    //Cấu hình flatpickr ngày
    flatpickr("#dateInput", {
        dateFormat: "d-m-Y",
        minDate: "today"
    });


});



    //Xử lý khi thực hiện chọn tòa
    $('#building').change(function() {
        // Lấy giá trị được chọn
        var buildingId = $(this).val();
        // console.log(buildingId);

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




    //xử lý khi chọn phòng thì hiện ra ngày bị chặn
    $('#room').change(function() {
        var roomsId = $(this).val();

        console.log(roomsId);

        if (roomsId) {
            $.ajax({
                url: '/admin/getDateRoom',
                type: 'POST',
                data: {
                    rooms_id: roomsId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Gửi thành công:', response.dates);
                    // Cập nhật danh sách ngày không hợp lệ
                    let dateNone = response.dates.map(item => item.specific_date);
                    let formattedDateNone = dateNone.map(date => {
                        let parts = date.split("-"); // Tách năm, tháng, ngày
                        return `${parts[2]}-${parts[1]}-${parts[0]}`; // Sắp xếp lại thành DD-MM-YYYY
                    });

                    console.log("Ngày bị vô hiệu hóa:", dateNone);

                    // Cập nhật flatpickr với danh sách ngày mới
                    flatpickr("#dateInput", {
                        dateFormat: "d-m-Y",
                        disable: formattedDateNone,
                        minDate: "today" //chặn ngày trong quá khứ
                    });
                },
                error: function(xhr, status, error) {
                    console.log('Lỗi:', error);
                }
            });
        }
    });


    //xử lý khi chọn ngày thì hiện ra giờ bị chặn
    $('#dateInput').change(function() {
    var date = $(this).val();
    var room_id = $('#room').val();

    var parts = date.split("-"); // Tách chuỗi thành mảng
    var formattedDate = parts[2] + "-" + parts[1] + "-" + parts[0];
    console.log(formattedDate, room_id);

    if (date) {
        $.ajax({
            url: '/admin/getTimeNone',
            type: 'POST',
            data: {
                date: formattedDate,
                room_id: room_id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Gửi thành công:', response.blockedHours);

                // Làm rỗng danh sách giờ trước khi thêm mới
                var selectBox = $('#timeInputStartHour');
                selectBox.empty();


                // Tạo danh sách giờ chẵn từ 7 đến 20
                for (let i = 7; i < 21; i++) {
                    let isBlocked = false;

                    // Kiểm tra xem giờ này có bị chặn không
                    $.each(response.blockedHours, function(key, value) {

                        if (value.hour_start === i || value.hour_end === i || value.hour_between === i) {
                            isBlocked = true;
                            return isBlocked; // Thoát khỏi vòng lặp each khi tìm thấy giá trị trùng
                        }


                    });

                    if (isBlocked) {
                        selectBox.append('<option value="" disabled hidden>' + i + '</option>');
                    } else {
                        selectBox.append('<option value="' + i + '">' + i + '</option>');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log('Lỗi:', error);
            }
        });
    }
});



</script>
@endpush


