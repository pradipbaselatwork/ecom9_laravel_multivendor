    <!-- Form-Fields /- -->
    <h4 class="section-h4 deliveryText">Add New Delivery Address</h4>
    <div class="u-s-m-b-24">
        <input type="checkbox" class="check-box" id="ship-to-different-address" data-toggle="collapse"
            data-target="#showdifferent">
        @if (count($deliveryAddresses) > 0)
            <label class="label-text newAddress" for="ship-to-different-address">Ship to a different address ?</label>
        @else
            <label class="label-text newAddress" for="ship-to-different-address">Ship to a delivery address ?</label>
        @endif
    </div>
    <div class="collapse" id="showdifferent">
        <form id="addressAddEditForm" action="javascript:void(0)" method="post">@csrf
            <input type="hidden" id="delivery_id" name="delivery_id" class="text-field">
            <!-- Form-Fields -->
            <div class="group-inline u-s-m-b-13">
                <div class="group-1 u-s-p-r-16">
                    <label for="delivery_name">Name
                        <span class="astk">*</span>
                    </label>
                    <input type="text" id="delivery_name" name="delivery_name" class="text-field">
                    <p id="delivery-delivery_name"></p>
                </div>
                <div class="group-2">
                    <label for="delivery_address">Address
                        <span class="astk">*</span>
                    </label>
                    <input type="text" id="delivery_address" name="delivery_address" class="text-field">
                    <p id="delivery-delivery_address"></p>
                </div>
            </div>
            <div class="group-inline u-s-m-b-13">
                <div class="group-1 u-s-p-r-16">
                    <label for="delivery_city">City
                        <span class="astk">*</span>
                    </label>
                    <input type="text" id="delivery_city" name="delivery_city" class="text-field">
                    <p id="delivery-delivery_city"></p>
                </div>
                <div class="group-2">
                    <label for="delivery_state">State
                        <span class="astk">*</span>
                    </label>
                    <input type="text" id="delivery_state" name="delivery_state" class="text-field">
                    <p id="delivery-delivery_state"></p>
                </div>
            </div>
            <div class="u-s-m-b-13">
                <label for="delivery_country">Country
                    <span class="astk">*</span>
                </label>
                <div class="select-box-wrapper">
                    <select class="select-box" id="delivery_country" name="delivery_country">
                        <option selected="selected" value="">Choose your country...</option>
                        @foreach ($countries as $item)
                            <option value="{{ $item['country_name'] }}">{{ $item['country_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <p id="delivery-delivery_country"></p>
            </div>
            <div class="group-inline u-s-m-b-13">
                <div class="group-1 u-s-p-r-16">
                    <label for="delivery_pincode">Pincode
                        <span class="astk">*</span>
                    </label>
                    <input type="text" id="delivery_pincode" name="delivery_pincode" class="text-field">
                    <p id="delivery-delivery_pincode"></p>
                </div>
                <div class="group-2">
                    <label for="delivery_mobile">Mobile
                        <span class="astk">*</span>
                    </label>
                    <input type="text" id="delivery_mobile" name="delivery_mobile" class="text-field">
                    <p id="delivery-delivery_mobile"></p>
                </div>
            </div>
            <div class="group-inline u-s-m-b-13">
                <button type="submit" style="width: 100%" class="button button-outline-secondary">Save</button>
            </div>
            <!-- Form-Fields /- -->
        </form>
    </div>
