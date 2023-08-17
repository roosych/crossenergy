<div class="modal fade" id="addDriverToOwnerModal" tabindex="-1" aria-labelledby="addDriverToOwnerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addDriverToOwnerModalLabel">Assign drivers</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info py-2">
                    Select drivers to assign to this owner
                </div>
                <style>
                    .bootstrap-duallistbox-container .buttons {
                        display: none;
                    }
                </style>

                <form id="driverToOwner">
                    @csrf
                    <input type="hidden" name="owner_id" value="{{$owner->id}}">
                    <select id="drivers" name="drivers[]" multiple="multiple">
                        @foreach($ownerless_drivers as $driver)
                            <option value="{{$driver->id}}">
                                {{$driver->number ? $driver->number . ' -' : ''}}
                                {{$driver->fullname}}
                            </option>
                        @endforeach
                    </select>
                </form>
                <div id="errorMessages"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="driverToOwnerSubmit" class="btn btn-primary delete-link">Save</button>
            </div>
        </div>
    </div>
</div>
