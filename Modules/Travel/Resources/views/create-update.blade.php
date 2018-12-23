@extends('backend.master')
@section('content')
@if(isset($travel))
<div class="row">
    <form action="{{route('travel.update',['id'=>$travel->id])}}" method="POST" enctype="multipart/form-data">
        {{method_field('PUT')}}
        @else
        <form action="{{route('travel.store')}}" method="POST" enctype="multipart/form-data">
            @endif
            @csrf
            <div class="card">
                <div class="card-header" style="background:white">
                    <h3 class="card-title"><i class="fe fe-file-text"></i> {{isset($travel) ? 'Edit
                        Application':'New Application'}}</h3>
                    <div class="card-options" id="">
                        @include('travel::components._form-action-buttons')
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#profile">
                                <i class="fe fe-user"></i> View My Profile
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="help-block">Please fill in the form below accordingly. Field with asterisk (<span
                                    class="text-danger">*</span>) sign is compulsory.</p>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" value="{{Auth::id()}}">
                    @include('travel::components._application-type')
                    @include('travel::components._participants')
                    @include('travel::components._supervisor')
                    @include('travel::components._college-fellow')
                    @include('travel::components._travel-information')
                    @include('travel::components._financial-aid')
                </div>
            </div>


        </form>
</div>
@endsection

<!-- Page CSS -->
@section('page-css')
<link rel="stylesheet" href="{{asset('vendor/flag-icon-css-3/css/flag-icon.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/min/dropzone.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@include('asset-partials.dropzone.css.file')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('css/select2.bootstrap4.min.css')}}">
<style>
    .participants {
        display: none;
    }

</style>
@endsection
<!-- Page JS -->
@section('page-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/min/dropzone.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var selector = function (dateStr) {
            var d1 = $('.event-from').datepicker('getDate');
            var d2 = $('.event-to').datepicker('getDate');
            var diff = 1;
            if (d1 && d2) {
                diff = diff + Math.floor((d2.getTime() - d1.getTime()) / 86400000);
            }
            $('.calculated').val(diff);
        }
        // Event
        $(".event-from").datepicker({
            dateFormat: "{{config('app.date_format_js ')}}",
            changeMonth: true,
            numberOfMonths: 1,
            minDate: 0,
            onClose: function (selectedDate) {
                $(".event-to").datepicker("option", "minDate", selectedDate);
            }
        });
        $(".event-to").datepicker({
            defaultDate: "+1w",
            dateFormat: "{{config('app.date_format_js')}}",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function (selectedDate) {
                $(".event-from").datepicker("option", "maxDate", selectedDate);
            }
        });
        $(".event-from,.event-to").change(selector);
        // Tavel
        $(".travel-from").datepicker({
            defaultDate: "+1w",
            dateFormat: "{{config('app.date_format_js ')}}",
            changeMonth: true,
            numberOfMonths: 1,
            minDate: 0,
            onClose: function (selectedDate) {
                $(".event-from").datepicker("option", "minDate", selectedDate);
                $(".travel-to").datepicker("option", "minDate", selectedDate);
            }
        });
        $(".travel-to").datepicker({
            defaultDate: "+1w",
            dateFormat: "{{config('app.date_format_js ')}}",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function (selectedDate) {
                $(".event-to").datepicker("option", "maxDate", selectedDate);
                $(".event-from").datepicker("option", "maxDate", selectedDate);
                $(".travel-from").datepicker("option", "maxDate", selectedDate);
            }
        });


        $(function () {
            var f = 1;

            $('#add-financial').click(function () {
                f++;
                $('#dynamic_field').append('<tr id="row-financial' + f +
                    '" class="dynamic-added"><td>' + f +
                    '</td><td><select name="" id="" class="form-control selectize">@foreach($instrument as $n)<option value="{{$n->name}}">{{$n->name}}</option>@endforeach</select></td><td><input type="text" class="form-control" name="remarks[]"></td><td class="text-center"><a  name="remove" id="' +
                    f +
                    '" class="btn btn-danger btn-sm remove-financial text-white"><i class="fe fe-trash"></i> Delete</a></td></tr>'
                );
            });

            $(document).on('click', '.remove-financial', function () {
                var financial_button_id = $(this).attr("id");
                $('#row-financial' + financial_button_id + '').remove();
            });

        });


        $(function () {
            var p = 1;

            $('#add-participant').click(function () {
                p++;
                $('#dynamic_field_participant').append('<tr id="row-participant' + p +
                    '" class="dynamic-added"><td>' + p +
                    '</td><td><input type="text" class="form-control" name="matric_nums[]"></td><td></td><td class="text-center"><a id="' +
                    p +
                    '" class="btn btn-danger btn-sm remove-participant text-white "><i class="fe fe-trash"></i> Delete</a></td></tr>'
                );
            });

            $(document).on('click', '.remove-participant', function () {
                var participant_button_id = $(this).attr("id");
                $('#row-participant' + participant_button_id + '').remove();
            });

        });

        $(function () {

            $('.num_participants').change(function () {

                var selected_option = $('.num_participants').val();

                if (selected_option == 1 || selected_option == 0) {
                    $('.participants').hide();
                }
                if (selected_option == 2) {
                    $('.participants').show();
                }
            });
        });

        $(function () {
            $('.college-fellow').hide();
            $('.application_type').change(function () {

                var selected_option = $('.application_type').val();

                if (selected_option == 'faculty') {
                    $('.college-fellow').hide();
                    $('.immediate-supervisor').show();
                }
                if (selected_option == 'college') {
                    $('.immediate-supervisor').hide();
                    $('.college-fellow').show();
                }
            });
        });

        $(function () {

            $('.supervisor').select2({
                placeholder: 'Please Select',
                theme: 'bootstrap4',
                ajax: {
                    url: "",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,

                                }
                            })
                        };
                    },
                    cache: true,
                    allowClear: true
                }
            });

            $('.college_fellow').select2({
                placeholder: 'Please Select',
                theme: 'bootstrap4',
                ajax: {
                    url: '/applications/college_fellow/search',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.email,
                                    id: item.email,

                                }
                            })
                        };
                    },
                    cache: true,
                    allowClear: true
                }
            });

            $('.students').select2({
                placeholder: 'Please Select',
                theme: 'bootstrap4',
                ajax: {
                    url: "",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.MBUT_NAMA,
                                    id: item.id,

                                }
                            })
                        };
                    },
                    cache: true,
                    allowClear: true
                }
            });
        });
    });

</script>
<script>
    function changeplh() {
        var sel = document.getElementById("financial-aid-selector");
        var textbx = document.getElementById("financial-aid-placeholder");
        var indexe = sel.selectedIndex;

        if (indexe == 1) {
            $("#financial-aid-placeholder").attr("placeholder", "Account Number");

        }
        if (indexe == 2) {
            $("#financial-aid-placeholder").attr("placeholder", "Account Number");
        }
        if (indexe == 3) {
            $("#financial-aid-placeholder").attr("placeholder", "Account Number");
        }
        if (indexe == 4) {
            $("#financial-aid-placeholder").attr("placeholder", "Name of Sponsor");
        }
        if (indexe == 5) {
            $("#financial-aid-placeholder").attr("placeholder", "Please specify");
        }
    }

</script>
@endsection
