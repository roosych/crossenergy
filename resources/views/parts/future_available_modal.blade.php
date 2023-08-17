<div class="modal fade" id="future_ava_modal" tabindex="-1" aria-labelledby="future_ava_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="future_ava_modalLabel">Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-0">
                <div class="alert alert-info py-2 mt-3">
                    If you want to assign a future date and location, fill in the fields
                </div>

                <form id="futureAvaForm">

                    <div class="row justify-content-between">
                        <div class="col-lg-5 col-12">
                            <label for="zipcode" class="form-label">Future Zip Code</label>
                            <input type="text" class="form-control" name="future_zipcode" id="zipcode">

                            <div class="mt-2">
                                <label for="location" class="form-label">Future Location</label>
                                <input type="text" class="form-control" name="future_location" id="location" readonly="readonly">
                            </div>

                            <div class="mt-2">
                                <label for="latitude" class="form-label">Future Latitude</label>
                                <input type="text" class="form-control" name="future_latitude" id="latitude" readonly="readonly">
                            </div>

                            <div class="mt-2">
                                <label for="longitude" class="form-label">Future Longitude</label>
                                <input type="text" class="form-control" name="future_longitude" id="longitude" readonly="readonly">
                            </div>

                            <div id="errorMsg" class="alert alert-danger mt-2" hidden="hidden"></div>

                            <div class="row mt-2">
                                <div class="col-12 mt-3">
                                    <button id="checkBtn" class="btn btn-primary w-100">Fill coords</button>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6 col-12">
                            <p class="form-label">Future Available Date</p>
                            <input id="future_date" type="text" name="future_datetime" class="form-control">

                            <div class="mt-2">
                                <label for="note" class="form-label">Note</label>
                                <textarea class="form-control" id="note" cols="30" rows="2" name="note"></textarea>
                            </div>
                        </div>

                    </div>

                </form>
                <div id="avaFormError" class="alert alert-danger mt-16 p-8" hidden="hidden">Check if the date is correct?</div>

            </div>

            <div class="modal-footer pt-2">
                <button type="button" class="btn btn-theme" data-bs-dismiss="modal">Only switch off availability</button>
                <button id="setFutureAvaBtn" type="button" class="btn btn-primary btn-block" hidden="hidden">Save future availability data</button>
            </div>
        </div>
    </div>
</div>

<style>
    .perfect-datetimepicker {
        width: 100% !important;
    }
</style>
