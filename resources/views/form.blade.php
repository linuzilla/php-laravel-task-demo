@extends('layouts.app')

@section('title', 'HOME')

@section('navbar')
    @parent
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h1 id="forms">表單填寫</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <form id="application-form" action="{{ url('form/application') }}" method="post">
                @csrf

                <fieldset>
                    <div class="form-group">
                        <label for="exampleInputEmail" class="form-label mt-4">Email address</label>
                        <input type="email" name='email' class="form-control" id="exampleInputEmail" required
                               placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPhone" class="form-label mt-4">Phone Number</label>
                        <input type="text" name='phone' class="form-control" id="exampleInputPhone"
                               placeholder="Enter phone number">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputAddress" class="form-label mt-4">Address</label>
                        <input type="text" name='address' class="form-control" id="exampleInputAddress"
                               placeholder="Enter Address">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputSeconds" class="form-label mt-4">執行時間 (seconds)</label>
                        <input type="number" name='seconds' class="form-control" id="exampleInputSeconds" required
                               placeholder="Enter job running time">
                    </div>

                    <br/>
                    <button type="submit" class="btn btn-primary">直接執行 (執行時間太長會 Bad Gateway)</button>
                    <button id="submit-job" type="button" class="btn btn-secondary">不發生 Bad Gateway 方式執行</button>
                </fieldset>
            </form>
        </div>
    </div>
    <div id="task-waiting-dialog" class="modal" data-toggle="modal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">指派工作執行中</h5>
                    <button type="button" class="btn-close" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>系統正在執行您指派的工作，請稍候，您可以點功能表的其它功能進行其它的工作，稍候再回來檢視結果。謝謝！</p>
                    <img src="{{asset('images/gears.gif')}}">
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-primary" href="{{ route('root') }}">回首頁做其它工作</a>
                    <button type="button" class="btn btn-primary" disabled>完成</button>
                </div>
            </div>
        </div>
    </div>
    <div id="task-done-dialog" class="modal" data-toggle="modal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">完成</h5>
                    <button type="button" class="btn-close" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body" id="task-result">
                    <img id="task-result-image" src="">
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-primary" href="{{ route('root') }}">回首頁做其它工作</a>
                    <button type="button" id="start-over" class="btn btn-primary">結束</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
        });
        let ajaxHelper = new AjaxHelper("/demo/");
        let widgetMap = {};

        function collectData() {
            let data = {};

            Object.keys(widgetMap).forEach(function (key) {
                data[key] = widgetMap[key].val();
            });
            return data;
        }

        $('#submit-job').click(function () {
            $('#task-waiting-dialog').modal('show');
            ajaxHelper.post("task/run", collectData(), function (err, result) {
                runningStatus();
                if (err == null) {
                }
            })
        });
        $('#start-over').click(function () {
            ajaxHelper.post("task/start-over", function () {
                location.reload();
            });
        });

        function fillDataFromSession() {
            ajaxHelper.get("form/application", function (err, result) {
                if (err == null) {
                    Object.entries(result).forEach(entry => {
                        const [key, value] = entry;

                        if (widgetMap[key] !== undefined) {
                            widgetMap[key].val(value);
                        }
                    });
                }
            })
        }

        function saveProgress() {
            ajaxHelper.patch("form/application", collectData(), function (err, result) {
            });
        }

        function runningStatus() {
            ajaxHelper.get("task/status", function (err, result) {
                if (err == null) {
                    if (result.taskStatus == 'running') {
                        $('#task-waiting-dialog').modal('show');
                        setTimeout(runningStatus, 5000);
                    } else {
                        $('#task-waiting-dialog').modal('hide');
                        retrieveResult();
                    }
                }
            });
        }

        function retrieveResult() {
            ajaxHelper.get("task/result", function (err, result) {
                if (err == null) {
                    if (result.code == 0) {
                        $('#task-result-image').prop('src', result.embedded);
                        $('#task-waiting-dialog').modal('hide');
                        $('#task-done-dialog').modal('show');
                    } else {
                        $('#task-done-dialog').modal('hide');
                    }
                }
            });
        }

        $(document).ready(function () {
            fillDataFromSession();

            $('#application-form input, #application-form select').each(function () {
                let input = $(this);
                widgetMap[input.attr('name')] = input;
                input.change(saveProgress);
            });

            runningStatus();
            // console.log(widgetMap);
        });
    </script>
@endsection
