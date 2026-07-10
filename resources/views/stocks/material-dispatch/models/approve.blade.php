<div class="modal fade"
     id="approveModal"
     tabindex="-1">

    <div class="modal-dialog">

        <form action="{{ route('material-dispatch.approve') }}"
              method="POST">

            @csrf

            <input type="hidden"
                   name="material_request_id"
                   id="approve_request_id">

            <div class="modal-content">

                <div class="modal-header">

                    <h5>

                        Approve Material Request

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    Are you sure you want to approve this request?

                </div>

                <div class="modal-footer">

                    <button class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            type="button">

                        Cancel

                    </button>

                    <button class="btn btn-success">

                        Approve

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<div class="modal fade"
     id="rejectModal">

    <div class="modal-dialog">

        <form action="{{ route('material-dispatch.reject') }}"
              method="POST">

            @csrf

            <input type="hidden"
                   name="material_request_id"
                   id="reject_request_id">

            <div class="modal-content">

                <div class="modal-header">

                    <h5>

                        Reject Request

                    </h5>

                </div>

                <div class="modal-body">

                    <label>

                        Reject Reason

                    </label>

                    <textarea class="form-control"
                              name="reject_reason"
                              rows="4"></textarea>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-secondary"
                            type="button"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button class="btn btn-danger">

                        Reject

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<script>
    $(function () {

    $('.approveBtn').click(function () {

        $('#approve_request_id').val($(this).data('id'));

    });

    $('.rejectBtn').click(function () {

        $('#reject_request_id').val($(this).data('id'));

    });

});
</script>