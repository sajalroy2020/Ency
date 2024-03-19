<div class="d-flex justify-content-between align-items-center cg-10 pb-16">
    <h4 class="fs-18 fw-600 lh-24 text-textBlack">{{__("Payment Status Change")}}</h4>
    <button type="button"
        class="w-30 h-30 rounded-circle d-flex justify-content-center align-items-center bd-one bd-c-stroke-color bg-transparent"
        data-bs-dismiss="modal" aria-label="Close"><img src="{{asset('assets/images/icon/close.svg')}}" alt="" />
    </button>
</div>
<hr>
<form class="ajax reset" action="{{ route('admin.client-invoice.store') }}" method="POST"
    data-handler="settingCommonHandler">
    @csrf
    <input type="hidden" value="{{$clientInvoice->id}}" name="id">
    <input type="hidden" value="{{$clientInvoice->client_id}}" name="client_id">
    <input type="hidden" value="{{$clientInvoice->due_date}}" name="due_date">
    <input type="hidden" value="{{$clientInvoice->payable_amount}}" name="payable_amount">
    <input type="hidden" value="{{$clientInvoice->order_id}}" name="order_id">
    <div class="col-12 ">
        <div class="zForm-wrap ">
            <label class="zForm-label">{{ __('Payment Status') }}</label>
            <select class="sf-select-without-search cs-select-form" id="product-id" name="payment_status">
                <option {{ $clientInvoice->payment_status == PAYMENT_STATUS_PAID ? 'selected' : ''}} value="{{
                    PAYMENT_STATUS_PAID}}">{{__('Paid')}}</option>
                <option {{ $clientInvoice->payment_status == PAYMENT_STATUS_PENDING ? 'selected' : ''}} value="{{
                    PAYMENT_STATUS_PENDING }}">{{__('Pending')}}</option>
                <option {{ $clientInvoice->payment_status == PAYMENT_STATUS_CANCELLED? 'selected' : ''}} value="{{
                    PAYMENT_STATUS_CANCELLED }}">{{__('Cancel')}}</option>
            </select>
        </div>
    </div>

    <button type="submit"
        class="mt-25 border-0 bd-ra-12 py-13 px-25 bg-main-color fs-16 fw-600 lh-19 text-white">{{__('Update')}}</button>
</form>
